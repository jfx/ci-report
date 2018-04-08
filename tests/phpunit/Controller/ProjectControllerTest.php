<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProjectControllerTest extends WebTestCase
{
    public function testProject1()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/project/project-one');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains(
            'ci-report / Project One',
            $crawler->filter('title')->text()
        );
    }

    public function testProjectNotFound()
    {
        $client = static::createClient();

        $client->request('GET', '/project/0');

        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }
}
