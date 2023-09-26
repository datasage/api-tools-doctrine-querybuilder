<?php

declare(strict_types=1);

namespace Laminas\ApiTools\Doctrine\QueryBuilder\Filter\ORM;

use function uniqid;

class IsMemberOf extends AbstractFilter
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

        $format = null;
        if (isset($option['format'])) {
            $format = $option['format'];
        }

        $value = $this->typeCastField($metadata, $option['field'], $option['value'], $format);

        $parameter = uniqid('a');
        $queryBuilder->$queryType(
            $queryBuilder
                ->expr()
                ->isMemberOf(':' . $parameter, $option['alias'] . '.' . $option['field'])
        );
        $queryBuilder->setParameter($parameter, $value);
    }
}
