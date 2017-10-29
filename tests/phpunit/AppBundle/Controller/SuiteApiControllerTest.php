<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SuiteApiControllerTest extends WebTestCase
{
    public function testGetSuites()
    {
        $client = static::createClient();

        $client->request('GET', '/api/projects/project-one/campaigns/1/suites');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testGetSuite()
    {
        $client = static::createClient();

        $client->request('GET', '/api/projects/project-one/campaigns/1/suites/1');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}
