<?php

/**
 * @see       https://github.com/laminas-api-tools/api-tools-doctrine-querybuilder for the canonical source repository
 * @copyright https://github.com/laminas-api-tools/api-tools-doctrine-querybuilder/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas-api-tools/api-tools-doctrine-querybuilder/blob/master/LICENSE.md New BSD License
 */

namespace Laminas\ApiTools\Doctrine\QueryBuilder\Query\Provider;

use Laminas\ServiceManager\AbstractPluginManager;
use Psr\Container\ContainerInterface;

class DefaultOrmFactory
{
    /**
     * Create and return DefaultOrm instance.
     *
     * @return DefaultOrm
     */
    public function __invoke(ContainerInterface $container)
    {
        if ($container instanceof AbstractPluginManager) {
            $container = $container->getServiceLocator() ?: $container;
        }

        $provider = new DefaultOrm();
        $provider->setServiceLocator($container);

        return $provider;
    }
}
