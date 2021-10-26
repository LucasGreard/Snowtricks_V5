<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class HomeTest extends WebTestCase
{
    public function testDisplayHelloWord()
    {
        $client = static::createClient();
        $client->request('GET', '/HelloWord');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }
}
