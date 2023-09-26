<?php

/**
 * Alias Laminas\ApiTools\Hal\Extractor\EntityExtractor to the appropriate class based on
 * which version of laminas-hydrator we detect. HydratorPluginManagerInterface
 * is added in v3.
 */

declare(strict_types=1);

namespace Laminas\ApiTools\Doctrine\QueryBuilder;

use Doctrine\Laminas\Hydrator\Strategy\CollectionStrategyInterface;
use Laminas\Hydrator\HydratorPluginManagerInterface;

use function class_alias;
use function interface_exists;

if (interface_exists(CollectionStrategyInterface::class, true)) {
    // phpcs:ignore
    class_alias(Hydrator\Strategy\CollectionLinkHydratorV4::class, Hydrator\Strategy\Collectionlink::class, true);
} elseif (interface_exists(HydratorPluginManagerInterface::class, true)) {
    // phpcs:ignore
    class_alias(Hydrator\Strategy\CollectionLinkHydratorV3::class, Hydrator\Strategy\Collectionlink::class, true);
} else {
    // phpcs:ignore
    class_alias(Hydrator\Strategy\CollectionLinkHydratorV2::class, Hydrator\Strategy\Collectionlink::class, true);
}
