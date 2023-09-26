<?php

declare(strict_types=1);

use Doctrine\DBAL\Driver\PDO\SQLite\Driver;
use Laminas\ApiTools\Doctrine\QueryBuilder\OrderBy;
use Laminas\ServiceManager\Factory\InvokableFactory;

return [
    'doctrine'                                    => [
        'connection' => [
            'orm_default' => [
                'configuration' => 'orm_default',
                'eventmanager'  => 'orm_default',
                'driverClass'   => Driver::class,
                'params'        => [
                    'memory' => true,
                ],
            ],
            'odm_default' => [
                'server'   => 'mongodb',
                'port'     => '27017',
                'user'     => '',
                'password' => '',
                'dbname'   => 'laminas_doctrine_querybuilder_filter_test',
            ],
        ],
    ],
    'api-tools-doctrine-querybuilder-orderby-orm' => [
        'aliases'   => [
            'field' => OrderBy\ORM\Field::class,
        ],
        'factories' => [
            OrderBy\ORM\Field::class => InvokableFactory::class,
        ],
    ],
    'api-tools-doctrine-querybuilder-orderby-odm' => [
        'aliases'   => [
            'field' => OrderBy\ODM\Field::class,
        ],
        'factories' => [
            OrderBy\ODM\Field::class => InvokableFactory::class,
        ],
    ],
];
