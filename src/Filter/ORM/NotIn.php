<?php

declare(strict_types=1);

namespace Laminas\ApiTools\Doctrine\QueryBuilder\Filter\ORM;

use function uniqid;

class NotIn extends AbstractFilter
{
    /**
     * {@inheritDoc}
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

        $format = $option['format'] ?? null;

        $queryValues = [];
        foreach ($option['values'] as $value) {
            $queryValues[]             = $this->typeCastField(
                $metadata,
                $option['field'],
                $value,
                $format,
                $doNotTypecastDatetime = true
            );
        }

        $parameter = uniqid('a');
        $queryBuilder->$queryType(
            $queryBuilder
                ->expr()
                ->notIn($option['alias'] . '.' . $option['field'], ':' . $parameter)
        );
        $queryBuilder->setParameter($parameter, $queryValues);
    }
}
