<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CampaignControllerTest extends WebTestCase
{
    public function testCampaign1()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/project/1/campaign/1');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains(
            'ci-report / Project One / Campaign #1',
            $crawler->filter('title')->text()
        );
    }
    
    public function testProjectNotFound()
    {
        $client = static::createClient();

        $client->request('GET', '/project/0/campaign/1');

        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }
    
    public function testCampaignNotFound()
    {
        $client = static::createClient();

        $client->request('GET', '/project/1/campaign/0');

        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }
}
