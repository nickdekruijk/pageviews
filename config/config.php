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
    | By default we create a 'pageviews' session as a array
    */
    'session_variable' => 'pageviews',

];
