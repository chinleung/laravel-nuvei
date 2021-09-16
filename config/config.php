<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Terminal
    |--------------------------------------------------------------------------
    |
    | The information of the terminal.
    |
    */

    'terminal' => [
        'id' => env('NUVEI_TERMINAL_ID'),
        'secret' => env('NUVEI_TERMINAL_SECRET'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Transaction
    |--------------------------------------------------------------------------
    |
    | Information related to transactions.
    |
    */

    'currency' => env('NUVEI_CURRENCY', 'USD'),

    /*
    |--------------------------------------------------------------------------
    | Debugging
    |--------------------------------------------------------------------------
    |
    | Indicates if the responses of Nuvei should be logged.
    |
    */

    'debug' => [
        'log' => env('NUVEI_LOG', false),
    ],

    /*
    |--------------------------------------------------------------------------
    | Demo Mode
    |--------------------------------------------------------------------------
    |
    | Incidates if the requests should be sent to the demo environment instead
    | of the production environment.
    |
    */

    'demo' => env('NUVEI_DEMO', false),

];
