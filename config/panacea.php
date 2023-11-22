<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Credentials
    |--------------------------------------------------------------------------
    |
    | Your PanaceaMobile.com credentials.
    | Password can also be your API key.
    |
    */
    'username' => env('PANACEA_USERNAME'),

    // Using your API key is preferred!
    'password' => env('PANACEA_PASSWORD'),

    /*
    |--------------------------------------------------------------------------
    | From
    |--------------------------------------------------------------------------
    |
    | The from name is used for reference in your PanaceaMobile dashboard.
    |
    | Note: Only alphanumeric values are accepted!
    |
    */
    'from' => env('PANACEA_FROM'),

    /*
    |--------------------------------------------------------------------------
    | Exceptions
    |--------------------------------------------------------------------------
    |
    | If these are set to true, response errors from PanaceaMobile API will
    | throw exceptions instead of just logging them silently.
    |
    | Note: If you send an SMS to multiple recipients and exceptions are
    | enabled, the rest of the recipients will not receive their SMS.
    |
    */
    'exceptions' => false, // Default: false

];
