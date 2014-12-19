<?php

namespace Libeo\Bundle\ApiBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\RouteResource;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class CategoryController
 * @package Libeo\Bundle\ApiBundle\Controller
 * @RouteResource("category")
 */
class CategoryController extends FOSRestController
{
    public function getAction(Request $request, $identifier)
    {
        return new Response("single cat");
    }

    public function cgetAction(Request $request)
    {
        return new Response("all cats");
    }
}