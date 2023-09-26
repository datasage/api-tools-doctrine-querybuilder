<?php

declare(strict_types=1);

namespace Laminas\ApiTools\Doctrine\QueryBuilder\Filter\ORM;

use Doctrine\ORM\QueryBuilder;

class NotLike extends AbstractFilter
{
    /**
     * {@inheritDoc}
     *
     * @param QueryBuilder $queryBuilder
     * @param array{where?: string|null, alias?: string|null, field: string, value: scalar} $option
     */
    public function filter($queryBuilder, $metadata, $option)
    {
        if (isset($option['where'])) {
            if ($option['where'] === 'and') {
                $queryType = 'andWhere';
            } elseif ($option['where'] === 'or') {
                $queryType = 'orWhere';
            }
        }

        if (! isset($queryType)) {
            $queryType = 'andWhere';
        }

        if (! isset($option['alias'])) {
            $option['alias'] = 'row';
        }

        $queryBuilder->$queryType(
            $queryBuilder
                ->expr()
                ->notlike(
                    $option['alias'] . '.' . $option['field'],
                    $queryBuilder->expr()->literal($option['value'])
                )
        );
    }
}
