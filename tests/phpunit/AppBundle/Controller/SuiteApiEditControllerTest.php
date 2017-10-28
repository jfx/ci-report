<?php

namespace Tests\AppBundle\Controller;

class SuiteApiEditControllerTest extends AbstractKernelControllerTest
{
    public function testPutLimitsSuites()
    {
        $client = static::createClient();

        $client->request(
            'PUT',
            '/api/projects/project-one/campaigns/1/suites/1/limits',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json', 'HTTP_X-CIR-TKN' => '1f4ffb19e4b9-02278af07b7d-4e370a76f001'),
            '{"warning":50, "success":60}'
        );

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
    
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
