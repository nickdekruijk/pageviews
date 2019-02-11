<?php

namespace NickDeKruijk\Pageviews;

use DB;
use Session;
use NickDeKruijk\Pageviews\Models\PageviewSession;
use NickDeKruijk\Pageviews\Models\PageviewHit;
use Carbon\Carbon;
use DeviceDetector\DeviceDetector;
use DeviceDetector\Parser\OperatingSystem;
use GeoIp2\Database\Reader;

class Pageviews
{
    protected static $botBrowsers = [
        'curl',
        'python-requests',
        'python-urllib',
        'wget',
        'unk',
        'perl',
        'go-http-client',
    ];

    private static function anonymize_ip($ip)
    {
        $ip = inet_pton($ip);
        if (strlen($ip) == 4) {
            return inet_ntop($ip & inet_pton('255.255.255.0'));
        } elseif (strlen($ip) == 16) {
            return net_ntop($ip & inet_pton('ffff:ffff:ffff:ffff:0000:0000:0000:0000'));
        } else {
            return false;
        }
    }

    // Track a visitor pageview
    public static function track($forceNewSession = false)
    {
        // Check for blacklisted uri
        $path = request()->getPathInfo();
        foreach(config('pageviews.blacklist') as $pattern) {
            if (preg_match($pattern, $path)) {
                return false;
            }
        }

        // Create session if needed
        $session = Session::get(config('pageviews.session_variable', 'pageviews'));
        if ($forceNewSession || !$session) {
            $session = static::getVisitData(request()->header('User-Agent'));
            $geoip = new Reader(base_path().'/vendor/bobey/geoip2-geolite2-composer/GeoIP2/GeoLite2-City.mmdb');
            try {
                $record = $geoip->city(request()->ip());
                $session['city'] = $record->city->name;
                $session['country'] = $record->country->isoCode;
                $session['lat'] = $record->location->latitude;
                $session['lng'] = $record->location->longitude;
            } catch(\Exception $e) {
            }
            $session['id'] = DB::table(config('pageviews.database_prefix', 'pageviews_') . 'sessions')->insertGetId($session);
            Session::put(config('pageviews.session_variable', 'pageviews'), $session);
            Session::save();
        }

        // Try writing pageview hit
        try {
            DB::table(config('pageviews.database_prefix', 'pageviews_') . 'hits')->insert([
                'session_id' => $session['id'],
                'url' => url()->full(),
                'referer' => request()->header('Referer'),
                'method' => request()->method(),
                'time' => Carbon::now(),
            ]);
        } catch(\Exception $e) {
            if ($forceNewSession) {
                throw new \Exception($e);
            } else {
                static::track(true);
            }
        }

        return true;
    }

    // Based on voerro/laravel-visitor-tracker/src/Tracker.php
    protected static function getVisitData($agent)
    {
        $dd = new DeviceDetector($agent);
        $dd->parse();

        // Browser
        $browser = $dd->getClient('version') ? $dd->getClient('name') . ' ' . $dd->getClient('version') : $dd->getClient('name');
        $browserFamily = str_replace(' ', '-', strtolower($dd->getClient('name')));

        // OS
        $os = $dd->getOs('version') ? $dd->getOs('name') . ' ' . $dd->getOs('version') : $dd->getOs('name');
        $osFamily = str_replace(' ', '-', strtolower(OperatingSystem::getOsFamily($dd->getOs('short_name'))));
        $osFamily = $osFamily == 'gnu/linux' ? 'linux' : $osFamily;

        // "UNK UNK" browser and OS
        $browserFamily = ($browser == 'UNK UNK') ? 'unk' : $browserFamily;
        $osFamily = ($os == 'UNK UNK') ? 'unk' : $osFamily;

        // Whether it's a bot
        $bot = null;
        $isBot = $dd->isBot();
        if ($isBot) {
            $bot = $dd->getBot();
        } else {
            if (in_array($browserFamily, static::$botBrowsers)) {
                $isBot = true;
                $bot = ['name' => $browserFamily];
            }
        }

        return [
            'ip' => config('pageviews.anonymize_ip', true) ? self::anonymize_ip(request()->ip()) : request()->ip(),
            'is_ajax' => request()->ajax(),
            'user_agent' => $agent,
            'is_mobile' => $dd->isMobile(),
            'is_desktop' => $dd->isDesktop(),
            'is_bot' => $isBot,
            'bot' => $bot ? $bot['name'] : null,
            'os' => $os,
            'os_family' => $osFamily,
            'browser_family' => $browserFamily,
            'browser' => $browser,
            'browser_language_family' => explode('-', request()->server('HTTP_ACCEPT_LANGUAGE'))[0],
            'browser_language' => request()->server('HTTP_ACCEPT_LANGUAGE'),
            'device' => $dd->getDeviceName(),
            'brand' => $dd->getBrandName(),
            'model' => $dd->getModel(),
            'time' => Carbon::now(),
        ];
    }

    public static function parseCarbon($var, $input, $default = null)
    {
        return self::parseInput($var, $input, $default, true);
    }

    public static function parseInput($var, $input, $default = null, $carbon = false)
    {
        if ($carbon) {
            return $var ?: (request()->input($input) ? Carbon::parse(request()->input($input)) : $default);
        } else {
            return $var ?: (request()->input($input) ?: $default);
        }
    }

    // Return array
    public static function visitors($density = null, $from = null, $to = null)
    {
        $density = self::parseInput($density, 'density', 24*3600);
        $from = self::parseCarbon($from, 'from');
        $to = self::parseCarbon($to, 'to');

        $data = [];
        $start = $end = time();
        foreach(PageviewSession::from($from)->to($to)->get() as $session) {
            $timeslot = floor($session->time->getTimestamp() / $density) * $density;
            $start = min($start, $timeslot);
            $end = max($end, $timeslot);
            $data[$timeslot]['sessions'] = ($data[$timeslot]['sessions'] ?? 0) + 1;
        }
        foreach(PageviewHit::from($from)->to($to)->get() as $hit) {
            $timeslot = floor($hit->time->getTimestamp() / $density) * $density;
            $start = min($start, $timeslot);
            $end = max($end, $timeslot);
            $data[$timeslot]['hits'] = ($data[$timeslot]['hits'] ?? 0) + 1;
        }
        for($timeslot = $start; $timeslot <= $end; $timeslot += $density) {
            $data[$timeslot]['sessions'] = $data[$timeslot]['sessions'] ?? 0;
            $data[$timeslot]['hits'] = $data[$timeslot]['hits'] ?? 0;
        }
        ksort($data);
        $labels = [];
        foreach($data as $timeslot => $row) {
            $labels[$timeslot] = Carbon::createFromTimestamp($timeslot)->formatLocalized('%a %e %h %H:%M');
        }
        return json_encode([
            'labels' => array_values($labels),
            'datasets' => [
                [
                    'label' => 'Unique visitors',
                    'data' => array_column($data, 'sessions'),
                    'borderColor' => '#4455CC',
                    'backgroundColor' => '#ccddee'
                ],[
                    'label' => 'Pageviews',
                    'data' => array_column($data, 'hits'),
                    'borderWidth' => 1
/*
                ],[
                    'label' => 'Active users',
                    'data' => array_column($data, 'active'),
                    'borderColor' => '#44CC55',
                    'borderWidth' => 1
*/
                ]
            ]
        ], JSON_PRETTY_PRINT);
    }

    public static function referers($density = null, $from = null, $to = null)
    {
        $density = self::parseInput($density, 'density', 24*3600);
        $from = self::parseCarbon($from, 'from');
        $to = self::parseCarbon($to, 'to');
        return PageviewHit::select(DB::raw('count(referer) as count, referer'))
                ->from($from)
                ->to($to)
                ->groupBy('referer')
                ->orderByDesc(DB::raw('count(referer)'))
                ->get();
    }

    public static function urls($density = null, $from = null, $to = null)
    {
        $density = self::parseInput($density, 'density', 24*3600);
        $from = self::parseCarbon($from, 'from');
        $to = self::parseCarbon($to, 'to');
        return PageviewHit::select(DB::raw('count(url) as count, url'))
                ->from($from)
                ->to($to)
                ->groupBy('url')
                ->orderByDesc(DB::raw('count(url)'))
                ->get();
    }
}
