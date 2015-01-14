<?php

namespace Libeo\Bundle\ApiBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LocaleControllerTest extends WebTestCase
{
    public function testAll()
    {
        $client = static::createClient();
        $client->request('GET', '/api/v1/locales');

        $response = $client->getResponse();
        $code = $response->getStatusCode();
        $content = $response->getContent();

        $this->assertSame(200, $code);
        $this->assertContains("en_US",$content);
        $this->assertContains("de_DE", $content);
        $this->assertContains("fr_FR", $content);
    }
}