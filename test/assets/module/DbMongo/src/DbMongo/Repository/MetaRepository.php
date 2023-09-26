<?php

declare(strict_types=1);

namespace DbMongo\Repository;

use DbMongo\Document\Meta;
use Doctrine\ODM\MongoDB\Repository\DocumentRepository;

/**
 * @extends DocumentRepository<Meta>
 */
class MetaRepository extends DocumentRepository
{
}
