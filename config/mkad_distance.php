<?php
return array(

    /**
     * Default driver for use
     */
    'driver' => 'yandex',

    /**
     * Drivers list
     */
    'drivers' => [
        'yandex' => [
            'key' => env('YANDEX_API_KEY')
        ]
    ]

);
