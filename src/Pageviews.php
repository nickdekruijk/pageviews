<?php

namespace NickDeKruijk\Pageviews;

use DB;
use Session;
use NickDeKruijk\Pageviews\Models\PageviewSession;
use NickDeKruijk\Pageviews\Models\PageviewHit;
use Carbon\Carbon;

class Pageviews
{
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
    public static function track($request)
    {
        $session = Session::get(config('pageviews.session_variable', 'pageviews'));
        if (!$session) {
            $session = [
                'ip' => config('pageviews.anonymize_ip', true) ? self::anonymize_ip($request->ip()) : $request->ip(),
                'agent' => $request->header('User-Agent'),
                'time' => Carbon::now(),
            ];
            $session['id'] = DB::table(config('pageviews.database_prefix', 'pageviews_') . 'sessions')->insertGetId($session);
            Session::put(config('pageviews.session_variable', 'pageviews'), $session);
            Session::save();
        }

        DB::table(config('pageviews.database_prefix', 'pageviews_') . 'hits')->insert([
            'session_id' => $session['id'],
            'url' => url()->full(),
            'referer' => $request->header('Referer'),
            'time' => Carbon::now(),
        ]);
    }

    // Return array
    public static function visitors($density = 24 * 3600, Carbon $from = null, Carbon $to = null)
    {
        $sessions = PageviewSession::from($from)->to($to)->get();
        $data = [];
        $start = $end = null;
        foreach($sessions as $session) {
            $timeslot = floor($session->time->getTimestamp() / $density) * $density;
            if ($timeslot < $start || $start == null) {
                $start = $timeslot;
            }
            if ($timeslot > $end || $end == null) {
                $end = $timeslot;
            }
            if (empty($data[$timeslot])) {
                $data[$timeslot] = 1;
            } else {
                $data[$timeslot] += 1;
            }
        }
        for($timeslot = $start; $timeslot <= $end; $timeslot += $density) {
            if (empty($data[$timeslot])) {
                $data[$timeslot] = 0;
            }
        }
        ksort($data);
        $labels = [];
        foreach($data as $timeslot => $visitors) {
            $labels[$timeslot] = Carbon::createFromTimestamp($timeslot)->formatLocalized('%a %e %h %H:%M');
        }
        return json_encode(['labels' => array_values($labels), 'datasets' => [['label' => 'Unique visitors', 'data' => array_values($data)]]],JSON_PRETTY_PRINT);
    }
}
