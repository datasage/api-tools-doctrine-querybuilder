<?php

declare(strict_types=1);

namespace Laminas\ApiTools\Doctrine\QueryBuilder\Filter\ODM;

class LessThan extends AbstractFilter
{
    /**
     * {@inheritDoc}
     */
    public function filter($queryBuilder, $metadata, $option)
    {
        $queryType = 'addAnd';
        if (isset($option['where'])) {
            if ($option['where'] === 'and') {
                $queryType = 'addAnd';
            } elseif ($option['where'] === 'or') {
                $queryType = 'addOr';
            }
        }

        $format = $option['format'] ?? null;

        $value = $this->typeCastField($metadata, $option['field'], $option['value'], $format);

        $queryBuilder->$queryType($queryBuilder->expr()->field($option['field'])->lt($value));
    }
}
