<?php

declare(strict_types=1);

namespace Laminas\ApiTools\Doctrine\QueryBuilder\Filter\ODM;

use MongoDB\BSON\Regex;

use function str_replace;

class Like extends AbstractFilter
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

        $regex = str_replace('%', '.*?', $option['value']);

        $queryBuilder->$queryType(
            $queryBuilder
              ->expr()
              ->field($option['field'])
              ->equals(new Regex($regex, 'i'))
        );
    }
}
