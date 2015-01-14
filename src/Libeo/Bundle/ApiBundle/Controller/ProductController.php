<?php

namespace Libeo\Bundle\ApiBundle\Controller;

use Doctrine\Common\Proxy\Exception\InvalidArgumentException;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\RouteResource;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class ProductController
 * @package Libeo\Bundle\ApiBundle\Controller
 * @RouteResource("product")
 */
class ProductController extends FOSRestController
{
    public function cgetAction(Request $request)
    {
        // get the locales
        $locales = $this->getLocales($request);

        // get the channels
        $channels = $this->getChannels($request);

        // get all the products
        $manager = $this->get('pim_catalog.manager.product');
        $repo = $manager->getProductRepository();
        $products = $repo->findAll();

        // instanciate serializer
        $serializer = $this->get('pim_webservice.serializer');

        // serialize each product and put 'em in a json array
        $res = array();
        foreach($products as $p)
        {
            $res[] = $this->serializeProduct($serializer, $p, $locales, $channels);
        }

        return new Response("[".implode(',', $res)."]");
    }

    public function getAction(Request $request, $sku)
    {
        // get the locales
        $locales = $this->getLocales($request);

        // get the channels
        $channels = $this->getChannels($request);

        // get the product
        $manager = $this->get('pim_catalog.manager.product');
        $product = $manager->findByIdentifier($sku);

        if(!$product)
        {
            throw new NotFoundHttpException(sprintf('Product "%s" not found or unavailable.', $sku));
        }

        // serialize it
        $serializer = $this->get('pim_webservice.serializer');
        $data = $this->serializeProduct($serializer, $product, $locales, $channels);

        // return it
        return new Response($data);
    }

    public function getLocales($request)
    {
        // system's locales
        $userContext       = $this->get('pim_user.context.user');
        $locales  = $userContext->getUserLocaleCodes();

        // is a locale defined in the request?
        if($locale = $request->get('locale'))
        {
            // check if is a valid locale
            if(in_array($locale, $locales))
            {
                $locales = array($locale);
            }
            else
            {
                throw new NotFoundHttpException(sprintf('Locale "%s" not found or unavailable.', $locale));
            }
        }

        return $locales;
    }

    public function getChannels($request)
    {
        $userContext       = $this->get('pim_user.context.user');
        $channels = array_keys($userContext->getChannelChoicesWithUserChannel());

        return $channels;
    }

    public function serializeProduct($serializer, $product, $locales, $channels)
    {
        $url = $this->generateUrl(
            'libeo_api_get_product',
            array(
                'sku' => $product->getIdentifier()->getData(),
            ),
            true
        );

        $data = $serializer->serialize(
            $product,
            'json',
            [
                'locales'  => $locales,
                'channels' => $channels,
                'resource' => $url
            ]
        );

        return $data;
    }
}