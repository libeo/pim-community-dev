<?php

namespace Libeo\Bundle\ApiBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\RouteResource;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class CategoryController
 * @package Libeo\Bundle\ApiBundle\Controller
 * @RouteResource("category")
 */
class CategoryController extends FOSRestController
{
    public function getAction(Request $request, $code)
    {
        // locales
        $userContext       = $this->get('pim_user.context.user');
        $locales  = $this->getLocales($request);

        // channels
        $channels = array_keys($userContext->getChannelChoicesWithUserChannel());

        $manager = $this->get('pim_catalog.manager.category');
        $repo = $manager->getEntityRepository();

        $category = $repo->findByReference($code);

        if(!$category)
        {
            throw new NotFoundHttpException(sprintf('Category "%s" not found or unavailable.', $code));
        }

        // instanciate serializer
        $serializer = $this->get('pim_webservice.serializer');

        $url = $this->generateUrl(
            'libeo_api_get_category',
            array(
                'code' => $category->getCode(),
            ),
            true
        );

        $data = $serializer->serialize($category,
            'json',
            [
                'locales'  => $locales,
                'channels' => $channels,
                'resource' => $url,
            ]);

        return new Response($data);
    }

    public function cgetAction(Request $request)
    {
        // locales
        $userContext       = $this->get('pim_user.context.user');
        $locales  = $this->getLocales($request);

        // channels
        $channels = array_keys($userContext->getChannelChoicesWithUserChannel());

        $manager = $this->get('pim_catalog.manager.category');
        $repo = $manager->getEntityRepository();
        $categories = $manager->getTrees();

        // instanciate serializer
        $serializer = $this->get('pim_webservice.serializer');

        $trees = array();

        foreach($categories as $tree)
        {
            $trees[] = $this->buildTree($tree);
        }



        $tempo4 = $serializer->serialize($trees,
            'json',
            [
                'locales'  => $locales,
                'channels' => $channels
            ]);

        // serialize each product and put 'em in a json array
//        $res = array();
//        foreach($categories as $c)
//        {
//            $res[] = $this->serializeCategory($serializer, $c, $locales, $channels);
//        }
//
//        return new Response("[".implode(',', $res)."]");
        return new Response($tempo4);
    }

    public function buildTree($category)
    {
        $tree = array();

        // ajoute l'url de cette resource
        $url = $this->generateUrl(
            'libeo_api_get_category',
            array(
                'code' => $category->getCode(),
            ),
            true
        );
        $category->patate = $url;
        $tree['item'] = $category;
        $tree['children'] = array();
        $tree['resource'] = $url;

        $children = $category->getChildren();

        foreach($children as $child)
        {
            $tree['children'][] = $this->buildTree($child);
        }

        return $tree;
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
}