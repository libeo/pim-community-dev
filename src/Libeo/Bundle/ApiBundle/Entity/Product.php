<?php

namespace Libeo\ApiBundle\Entity\Product;

use Pim\Bundle\CatalogBundle\Model\Product as BaseProduct;
use JMS\Serializer\Annotation\ExclusionPolicy;


/**
 * @ExclusionPolicy("all")
 */
class Product extends BaseProduct
{
    /**
     * @Expose
     */
    protected $id;
}