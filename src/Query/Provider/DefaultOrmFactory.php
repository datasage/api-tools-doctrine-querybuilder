<?php

declare(strict_types=1);

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
