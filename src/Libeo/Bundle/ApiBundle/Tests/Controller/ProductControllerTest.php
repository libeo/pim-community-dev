<?php

namespace Libeo\Bundle\ApiBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProductControllerTest extends WebTestCase
{
    public function testAllWithLocale()
    {
        $client = static::createClient();
        $client->request('GET', '/api/v1/products?locale=fr_FR');

        $response = $client->getResponse();
        $code = $response->getStatusCode();
        $content = $response->getContent();

        $this->assertSame(200, $code);
        $this->assertContains("AKNSTK",$content);
        $this->assertContains("13565312", $content);
        $this->assertContains("fr_FR", $content);
        $this->assertNotContains("en_US", $content);
        $this->assertNotContains("de_DE", $content);
    }

    public function testAllWithoutLocale()
    {
        $client = static::createClient();
        $client->request('GET', '/api/v1/products');

        $response = $client->getResponse();
        $code = $response->getStatusCode();
        $content = $response->getContent();

        $this->assertSame(200, $code);
        $this->assertContains("AKNSTK",$content);
        $this->assertContains("13565312", $content);
        $this->assertContains("fr_FR", $content);
        $this->assertContains("en_US", $content);
        $this->assertContains("de_DE", $content);
    }

    public function testAllInvalidLocale()
    {
        $client = static::createClient();
        $client->request('GET', '/api/v1/products?locale=invalid_LOCALE');

        $response = $client->getResponse();
        $code = $response->getStatusCode();
        $content = $response->getContent();

        $this->assertSame(404, $code);
        $this->assertContains('Locale \"invalid_LOCALE\" not found or unavailable.',$content);
    }

    public function testSingleWithLocale()
    {
        $client = static::createClient();
        $client->request('GET', '/api/v1/products/1209128?locale=fr_FR');

        $response = $client->getResponse();
        $code = $response->getStatusCode();
        $content = $response->getContent();

        $this->assertSame(200, $code);
        $this->assertContains('webcams', $content);
        $this->assertContains('Trust Megapixel Pro', $content);
        $this->AssertContains('1209128-Trust.jpg', $content);
        $this->assertContains('fr_FR', $content);
        $this->assertNotContains('de_DE', $content);
        $this->assertNotContains('en_US', $content);
    }

    public function testSingleWithoutLocale()
    {
        $client = static::createClient();
        $client->request('GET', '/api/v1/products/1209128');

        $response = $client->getResponse();
        $code = $response->getStatusCode();
        $content = $response->getContent();

        $this->assertSame(200, $code);
        $this->assertContains('webcams', $content);
        $this->assertContains('Trust Megapixel Pro', $content);
        $this->AssertContains('1209128-Trust.jpg', $content);
        $this->assertContains('fr_FR', $content);
        $this->assertContains('de_DE', $content);
        $this->assertContains('en_US', $content);
    }

    public function testSingleInvalidLocale()
    {
        $client = static::createClient();
        $client->request('GET', '/api/v1/products/1209128?locale=invalid_LOCALE');

        $response = $client->getResponse();
        $code = $response->getStatusCode();
        $content = $response->getContent();

        $this->assertSame(404, $code);
        $this->assertContains('Locale \"invalid_LOCALE\" not found or unavailable.', $content);

    }

    public function testInvalidSingle()
    {
        $client = static::createClient();
        $client->request('GET', '/api/v1/products/666666');

        $response = $client->getResponse();
        $code = $response->getStatusCode();
        $content = $response->getContent();

        $this->assertSame(404, $code);
        $this->assertContains('Product \"666666\" not found or unavailable.', $content);

    }
}
