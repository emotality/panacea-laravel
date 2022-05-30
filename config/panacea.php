<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Credentials
    |--------------------------------------------------------------------------
    |
    | Your PanaceaMobile.com login details.
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
    | Using PanaceaMobile to send SMSes to certain other countries requires
    | you to specify a name. This can be left blank if only sending to
    | local (South African) numbers.
    |
    | Note: Only alpha numeric values accepted!
    |
    */
    'from' => env('PANACEA_FROM'),

    /*
    |--------------------------------------------------------------------------
    | Verify SSL
    |--------------------------------------------------------------------------
    |
    | If Guzzle (cURL wrapper) should verify the server's SSL certificate.
    | ONLY switch it off IF you get SSL errors (very rare!)
    |
    */
    'ssl' => env('PANACEA_SSL', true)

];
