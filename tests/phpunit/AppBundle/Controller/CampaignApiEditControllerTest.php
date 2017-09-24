<?php

namespace Tests\AppBundle\Controller;

class CamapaignApiEditControllerTest extends AbstractKernelControllerTest
{
    public function testPostCampaigns()
    {
        $client = static::createClient();

        $client->request(
            'POST',
            '/api/projects/project-one/campaigns',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json', 'HTTP_X-CIR-TKN' => '1f4ffb19e4b9-02278af07b7d-4e370a76f001'),
            '{"warning":80, "success":95, "start":"2017-07-01 12:30:01", "end":"2017-07-03 12:30:01"}'
        );

        $this->assertEquals(201, $client->getResponse()->getStatusCode());
    }
}
