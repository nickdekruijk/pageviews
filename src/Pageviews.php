<?php

namespace NickDeKruijk\Pageviews;

use DB;
use Session;
use NickDeKruijk\Pageviews\Models\PageviewSession;
use NickDeKruijk\Pageviews\Models\PageviewHit;

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
            ];
            $session['id'] = DB::table(config('pageviews.database_prefix', 'pageviews_') . 'sessions')->insertGetId($session);
            Session::put(config('pageviews.session_variable', 'pageviews'), $session);
            Session::save();
        }

        DB::table(config('pageviews.database_prefix', 'pageviews_') . 'hits')->insert([
            'session_id' => $session['id'],
            'url' => url()->full(),
        ]);
    }
}
