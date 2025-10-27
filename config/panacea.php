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
    | The From value can be alpha or numeric and will not be displayed on the
    | handset unless it is a sender ID requirement for international messages
    | outside South Africa. (Will be displayed in the dashboard)
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
