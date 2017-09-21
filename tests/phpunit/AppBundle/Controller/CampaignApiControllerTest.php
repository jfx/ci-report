<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CampaignApiControllerTest extends WebTestCase
{
    public function testGetCampaigns()
    {
        $client = static::createClient();

        $client->request('GET', '/api/projects/project-one/campaigns');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
    
    public function testGetCampaign()
    {
        $client = static::createClient();

        $client->request('GET', '/api/projects/project-one/campaigns/1');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
    
    public function testGetLastCampaign()
    {
        $client = static::createClient();

        $client->request('GET', '/api/projects/project-one/campaigns/last');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}
