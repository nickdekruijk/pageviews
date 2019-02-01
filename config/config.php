<?php

/*
 * You can place your custom package configuration in here.
 */
return [

    /*
    |--------------------------------------------------------------------------
    | database_prefix
    |--------------------------------------------------------------------------
    | Prefix the database tables created by the package migration with this
    | By default we create a 'pageviews_sessions' and 'pageviews_hits' table
    */
    'database_prefix' => 'pageviews_',

    /*
    |--------------------------------------------------------------------------
    | session_variable
    |--------------------------------------------------------------------------
    | The session variable we use to track a visitor session
    | By default we create a 'pageviews_session' session array variable
    */
    'session_variable' => 'pageviews_session',

    /*
    |--------------------------------------------------------------------------
    | middleware
    |--------------------------------------------------------------------------
    | Enable or disable visitor tracking for all web middleware group requests
    */
    'middleware' => true,

    /*
    |--------------------------------------------------------------------------
    | anonymize_ip
    |--------------------------------------------------------------------------
    | Anonymize the IP addresses gathered for privacy reasons
    */
    'anonymize_ip' => true,

];
