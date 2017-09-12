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
}
