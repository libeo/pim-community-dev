<?php

namespace Libeo\Bundle\ApiBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\RouteResource;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class LocaleController
 * @package Libeo\Bundle\ApiBundle\Controller
 * @RouteResource("locales")
 */
class LocaleController extends FOSRestController
{
    public function cgetAction(Request $request)
    {
        $userContext       = $this->get('pim_user.context.user');
        $locales  = $userContext->getUserLocaleCodes();

        return new Response(json_encode($locales));
    }
}
