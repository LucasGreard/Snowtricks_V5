<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class SignOnTest extends WebTestCase
{
    public function testDisplaySignOnView()
    {
        $client = static::createClient();
        $client->request('GET', '/signOn');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }
}
