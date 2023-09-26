<?php

declare(strict_types=1);

namespace DbMongo;

use Doctrine\ODM\MongoDB\Mapping\Driver\XmlDriver;

return [
    'doctrine' => [
        'driver' => [
            'odm_driver'  => [
                'class' => XmlDriver::class,
                'paths' => [__DIR__ . '/xml'],
            ],
            'odm_default' => [
                'drivers' => [
                    __NAMESPACE__ . '\Document' => 'odm_driver',
                ],
            ],
        ],
    ],
];
