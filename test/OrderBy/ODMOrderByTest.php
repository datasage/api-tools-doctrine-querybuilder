<?php

/**
 * @see       https://github.com/laminas-api-tools/api-tools-doctrine-querybuilder for the canonical source repository
 * @copyright https://github.com/laminas-api-tools/api-tools-doctrine-querybuilder/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas-api-tools/api-tools-doctrine-querybuilder/blob/master/LICENSE.md New BSD License
 */

namespace LaminasTest\ApiTools\Doctrine\QueryBuilder\OrderBy;

use DateTime;
use DbMongo\Document;
use Doctrine\ODM\MongoDB\DocumentManager;
use Laminas\ApiTools\Doctrine\QueryBuilder\OrderBy\Service\ODMOrderByManager;
use LaminasTest\ApiTools\Doctrine\QueryBuilder\TestCase;
use MongoDB\Client;

class ODMOrderByTest extends TestCase
{
    /**
     * @return mixed
     */
    private function fetchResult(iterable $orderBy, string $entity = 'DbMongo\Document\Meta')
    {
        $serviceManager = $this->getApplication()->getServiceManager();
        /** @var ODMOrderByManager $orderByManager */
        $orderByManager = $serviceManager->get('LaminasDoctrineQueryBuilderOrderByManagerOdm');
        /** @var DocumentManager $objectManager */
        $objectManager  = $serviceManager->get('doctrine.documentmanager.odm_default');
        $queryBuilder   = $objectManager->createQueryBuilder($entity);
        // NOTE:  the metadata is an array with one element in testing :\

        $metadata = $objectManager->getMetadataFactory()->getAllMetadata();

        $orderByManager->orderBy($queryBuilder, $metadata[0], $orderBy);

        return $queryBuilder->getQuery()->execute();
    }

    public function setUp(): void
    {
        $this->setApplicationConfig(
            include __DIR__ . '/application.config.php'
        );
        parent::setUp();

        /** @var array $config */
        $config = $this->getApplication()->getConfig();
        /** @var array $config */
        $config = $config['doctrine']['connection']['odm_default'];

        $connection = new Client('mongodb://' . $config['server'] . ':' . $config['port']);
        $db         = $connection->{$config['dbname']};
        $collection = $db->meta;
        $collection->drop();

        $serviceManager = $this->getApplication()->getServiceManager();
        $objectManager  = $serviceManager->get('doctrine.documentmanager.odm_default');

        $meta1 = new Document\Meta();
        $meta1->setName('One');
        $meta1->setCreatedAt(new DateTime('2011-12-18 13:17:17'));
        $objectManager->persist($meta1);

        $meta2 = new Document\Meta();
        $meta2->setName('Two');
        $meta2->setCreatedAt(new DateTime('2014-12-18 13:17:17'));
        $objectManager->persist($meta2);

        $meta3 = new Document\Meta();
        $meta3->setName('Three');
        $meta3->setCreatedAt(new DateTime('2012-12-18 13:17:17'));
        $objectManager->persist($meta3);

        $meta4 = new Document\Meta();
        $meta4->setName('Four');
        $meta4->setCreatedAt(new DateTime('2013-12-18 13:17:17'));
        $objectManager->persist($meta4);

        $meta5 = new Document\Meta();
        $meta5->setName('Five');
        $objectManager->persist($meta5);

        $objectManager->flush();
    }

    public function testField(): void
    {
        $orderBy = [
            ['type' => 'field', 'field' => 'name', 'direction' => 'asc'],
        ];

        $result = $this->fetchResult($orderBy);
        foreach ($result as $meta) {
            $this->assertEquals('Five', $meta->getName());
            break;
        }

        $orderBy = [
            ['type' => 'field', 'field' => 'name', 'direction' => 'desc'],
        ];

        $result = $this->fetchResult($orderBy);
        foreach ($result as $meta) {
            $this->assertEquals('Two', $meta->getName());
            break;
        }
    }
}
