<?php

/**
 * @see       https://github.com/laminas-api-tools/api-tools-doctrine-querybuilder for the canonical source repository
 * @copyright https://github.com/laminas-api-tools/api-tools-doctrine-querybuilder/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas-api-tools/api-tools-doctrine-querybuilder/blob/master/LICENSE.md New BSD License
 */

namespace Laminas\ApiTools\Doctrine\QueryBuilder\Hydrator\Strategy;

use Doctrine\Laminas\Hydrator\Strategy\CollectionStrategyInterface;
use Doctrine\Persistence\Mapping\ClassMetadata;
use Laminas\ApiTools\Hal\Link\Link;
use Laminas\Filter\FilterChain;
use Laminas\Hydrator\Strategy\StrategyInterface;
use Laminas\ServiceManager\ServiceManager;
use LogicException;

use function method_exists;

/**
 * A field-specific hydrator for collections.
 *
 * This version is for use with laminas-hyrator v3 and up, and doctrine-hydrator 3 and will be aliased to
 * CollectionLink in those versions.
 */
class CollectionLinkHydratorV4 implements StrategyInterface, CollectionStrategyInterface
{
    protected ServiceManager $serviceManager;
    private ?string $collectionName = null;

    private ?ClassMetadata $metadata = null;

    private ?object $object = null;

    /**
     * @return self
     */
    public function setServiceManager(ServiceManager $serviceManager)
    {
        $this->serviceManager = $serviceManager;

        return $this;
    }

    /**
     * @return ServiceManager
     */
    public function getServiceManager()
    {
        return $this->serviceManager;
    }

    public function setCollectionName(string $collectionName): void
    {
        $this->collectionName = $collectionName;
    }

    public function getCollectionName(): string
    {
        if ($this->collectionName === null) {
            throw new LogicException('Collection name has not been set.');
        }

        return $this->collectionName;
    }

    public function setClassMetadata(ClassMetadata $classMetadata): void
    {
        $this->metadata = $classMetadata;
    }

    public function getClassMetadata(): ClassMetadata
    {
        if ($this->metadata === null) {
            throw new LogicException('Class metadata has not been set.');
        }

        return $this->metadata;
    }

    public function setObject(object $object): void
    {
        $this->object = $object;
    }

    public function getObject(): object
    {
        if ($this->object === null) {
            throw new LogicException('Object has not been set.');
        }

        return $this->object;
    }

    /**
     * @inheritDoc
     */
    public function extract($value, ?object $object = null)
    {
        $config = $this->getServiceManager()->get('config');
        if (
            ! method_exists($value, 'getTypeClass')
            || ! isset($config['api-tools-hal']['metadata_map'][$value->getTypeClass()->name])
        ) {
            return null;
        }

        $config  = $config['api-tools-hal']['metadata_map'][$value->getTypeClass()->name];
        $mapping = $value->getMapping();

        $filter = new FilterChain();
        $filter->attachByName('WordCamelCaseToUnderscore')
            ->attachByName('StringToLower');

        $link = new Link($filter($mapping['fieldName']));
        $link->setRoute($config['route_name']);
        $link->setRouteParams(['id' => null]);

        if (isset($config['api-tools-doctrine-querybuilder-options']['filter_key'])) {
            $filterKey = $config['api-tools-doctrine-querybuilder-options']['filter_key'];
        } else {
            $filterKey = 'filter';
        }

        $filterValue = [
            'field' => $mapping['mappedBy'] ? : $mapping['inversedBy'],
            'type'  => isset($mapping['joinTable']) ? 'ismemberof' : 'eq',
            'value' => $value->getOwner()->getId(),
        ];

        $link->setRouteOptions([
            'query' => [
                $filterKey => [
                    $filterValue,
                ],
            ],
        ]);

        return $link;
    }

    /**
     * @inheritDoc
     */
    public function hydrate($value, ?array $data = null)
    {
        // Hydration is not supported for collections.
        // A call to PATCH will use hydration to extract then hydrate
        // an entity. In this process a collection will be included
        // so no error is thrown here.
    }
}
