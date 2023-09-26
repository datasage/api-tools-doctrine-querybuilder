<?php

declare(strict_types=1);

namespace Laminas\ApiTools\Doctrine\QueryBuilder\Filter\ODM;

class Regex extends AbstractFilter
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

        $queryBuilder->$queryType(
            $queryBuilder
                ->expr()
                ->field($option['field'])
                ->equals(new \MongoDB\BSON\Regex($option['value']))
        );
    }
}
