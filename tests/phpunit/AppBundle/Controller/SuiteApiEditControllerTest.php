<?php

namespace Tests\AppBundle\Controller;

class SuiteApiEditControllerTest extends AbstractKernelControllerTest
{
    public function testDeleteSuites()
    {
        $client = static::createClient();

        $client->request(
            'DELETE',
            '/api/projects/project-one/campaigns/1/suites/1',
            array(),
            array(),
            array('HTTP_X-CIR-TKN' => '1f4ffb19e4b9-02278af07b7d-4e370a76f001')
        );

        $this->assertEquals(204, $client->getResponse()->getStatusCode());
    }
}
