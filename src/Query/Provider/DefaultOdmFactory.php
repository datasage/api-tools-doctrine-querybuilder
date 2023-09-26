<?php

declare(strict_types=1);

namespace Laminas\ApiTools\Doctrine\QueryBuilder\Query\Provider;

use Laminas\ServiceManager\AbstractPluginManager;
use Psr\Container\ContainerInterface;

class DefaultOdmFactory
{
    /**
     * Create and return DefaultOdm instance.
     *
     * @return DefaultOdm
     */
    public function __invoke(ContainerInterface $container)
    {
        if ($container instanceof AbstractPluginManager) {
            $container = $container->getServiceLocator() ?: $container;
        }

        $provider = new DefaultOdm();
        $provider->setServiceLocator($container);

        return $provider;
    }
}
