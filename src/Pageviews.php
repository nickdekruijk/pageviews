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
        $data = [];
        $start = $end = time();
        foreach(PageviewSession::from($from)->to($to)->get() as $session) {
            $timeslot = floor($session->time->getTimestamp() / $density) * $density;
            $start = min($start, $timeslot);
            $end = max($end, $timeslot);
            if (empty($data[$timeslot]['sessions'])) {
                $data[$timeslot]['sessions'] = 1;
            } else {
                $data[$timeslot]['sessions'] += 1;
            }
        }
        foreach(PageviewHit::from($from)->to($to)->get() as $hit) {
            $timeslot = floor($hit->time->getTimestamp() / $density) * $density;
            $start = min($start, $timeslot);
            $end = max($end, $timeslot);
            if (empty($data[$timeslot]['hits'])) {
                $data[$timeslot]['hits'] = 1;
            } else {
                $data[$timeslot]['hits'] += 1;
            }
        }
        for($timeslot = $start; $timeslot <= $end; $timeslot += $density) {
            if (empty($data[$timeslot]['sessions'])) {
                $data[$timeslot]['sessions'] = 0;
            }
            if (empty($data[$timeslot]['hits'])) {
                $data[$timeslot]['hits'] = 0;
            }
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
                ],[
                    'label' => 'Active users',
                    'data' => array_column($data, 'active'),
                    'borderColor' => '#44CC55',
                    'borderWidth' => 1
                ]
            ]
        ], JSON_PRETTY_PRINT);
    }
}
