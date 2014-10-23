<?php

use Monolog\Logger;

return [
    'elasticsearch' => [
        'hosts' => ['127.0.0.1:9200'],
        'logPath' => storage_path() . '/logs/searchable.log',
        'logLevel' => Logger::INFO
    ]
];