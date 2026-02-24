<?php

return [
    'parsers' => [
        'olx_ua' => [
            'base_url' => 'https://www.olx.ua',
            'class' => App\Services\Parsers\OlxUaParser::class,
        ],
        // Add more parsers here
    ],
    'check_interval_in_minutes' => 30,
];
