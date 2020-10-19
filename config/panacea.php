<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Credentials
    |--------------------------------------------------------------------------
    |
    | Your PanaceaMobile.com login details.
    |
    */
    'username' => env('PANACEA_USERNAME'),
    'password' => env('PANACEA_PASSWORD'),

    /*
    |--------------------------------------------------------------------------
    | Verify SSL
    |--------------------------------------------------------------------------
    |
    | If Guzzle (cURL wrapper) should verify the SSL.
    | ONLY switch it off IF you get SSL errors (very rare!)
    |
    */
    'ssl' => env('PANACEA_SSL', true)

];
