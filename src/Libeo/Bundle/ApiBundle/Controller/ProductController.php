<?php

namespace Libeo\Bundle\ApiBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\RouteResource;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ProductController
 * @package Libeo\Bundle\ApiBundle\Controller
 * @RouteResource("product")
 */
class ProductController extends FOSRestController
{
    public function cgetAction(Request $request)
    {
        $manager = $this->get('pim_catalog.manager.product');
        $repo = $manager->getProductRepository();
        $products = $repo->findAll();

        $res = array();
        foreach($products as $p)
        {
            $res[] = $p->getValues();
        }

        //$res = json_encode($res);
        return $res;
    }

    public function getAction(Request $request, $sku)
    {
        $manager = $this->get('pim_catalog.manager.product');
        $product = $manager->findByIdentifier($sku);
//
//        $product->setLocale('en_US');
//        $product->setScope('ecommerce');

        $data = (Object)[];
        $data->id = $product->getId();
        $data->sku = $sku;
        $data->family = $product->getFamily()->getCode();
        $data->label = $product->getLabel();
        $data->values = array();
        $values = $product->getValues();
        foreach($values as $key => $value)
        {
            $data->values[] = (Object)array($key => $value);
        }

        return new Response(json_encode($data));
    }

}