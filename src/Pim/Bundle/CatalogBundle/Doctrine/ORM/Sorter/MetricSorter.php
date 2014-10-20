<?php

namespace Pim\Bundle\CatalogBundle\Doctrine\ORM\Sorter;

use Doctrine\ORM\QueryBuilder;
use Pim\Bundle\CatalogBundle\Model\AbstractAttribute;
use Pim\Bundle\CatalogBundle\Doctrine\Query\AttributeSorterInterface;
use Pim\Bundle\CatalogBundle\Doctrine\ORM\ValueJoin;

/**
 * Metric sorter
 *
 * @author    Nicolas Dupont <nicolas@akeneo.com>
 * @copyright 2014 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 *
 * TODO : never used cause disabled on frontend ?
 */
class MetricSorter implements AttributeSorterInterface
{
    /** @var QueryBuilder */
    protected $qb;

    /**
     * {@inheritdoc}
     */
    public function setQueryBuilder($queryBuilder)
    {
        $this->qb = $queryBuilder;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsAttribute(AbstractAttribute $attribute)
    {
        return $attribute->getAttributeType() === 'pim_catalog_metric';
    }

    /**
     * {@inheritdoc}
     */
    public function addAttributeSorter(AbstractAttribute $attribute, $direction, $context = [])
    {
        $aliasPrefix = 'sorter';
        $joinAlias   = $aliasPrefix.'V'.$attribute->getCode();
        $backendType = $attribute->getBackendType();

        // join to value
        $condition = $this->prepareAttributeJoinCondition($attribute, $joinAlias, $context);
        $this->qb->leftJoin(
            $this->qb->getRootAlias().'.values',
            $joinAlias,
            'WITH',
            $condition
        );

        $joinAliasMetric = $aliasPrefix.'M'.$attribute->getCode();
        $this->qb->leftJoin($joinAlias.'.'.$backendType, $joinAliasMetric);

        $this->qb->addOrderBy($joinAliasMetric.'.baseData', $direction);

        $idField = $this->qb->getRootAlias().'.id';
        $this->qb->addOrderBy($idField);

        return $this;
    }

    /**
     * Prepare join to attribute condition with current locale and scope criterias
     *
     * @param AbstractAttribute $attribute the attribute
     * @param string            $joinAlias the value join alias
     * @param array             $context   the context
     *
     * @throws ProductQueryException
     *
     * @return string
     */
    protected function prepareAttributeJoinCondition(AbstractAttribute $attribute, $joinAlias, $context)
    {
        $joinHelper = new ValueJoin($this->qb);

        return $joinHelper->prepareCondition($attribute, $joinAlias, $context);
    }
}
