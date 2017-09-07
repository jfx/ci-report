<?php

namespace Tests\AppBundle\Controller;

class ProjectApiEditControllerTest extends AbstractKernelControllerTest
{
    public function testPostProjects()
    {
        $client = static::createClient();

        $client->request(
            'POST',
            '/api/projects',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            '{"name":"Project Added","email":"ci-report.test@example.com","warning":50,"success":60}'
        );

        $this->assertEquals(201, $client->getResponse()->getStatusCode());
    }
    
    public function testPutProjects()
    {
        $client = static::createClient();

        $client->request(
            'PUT',
            '/api/projects/project-five',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json', 'HTTP_X-CIR-TKN' => '1f4ffb19e4b9-02278af07b7d-4e370a76f005'),
            '{"name":"Project modify","email":"ci-report.test@example.com","warning":50,"success":60}'
        );

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
    
    public function testDeleteProjects()
    {
        $client = static::createClient();

        $client->request(
            'DELETE',
            '/api/projects/project-one',
            array(),
            array(),
            array('HTTP_X-CIR-TKN' => '1f4ffb19e4b9-02278af07b7d-4e370a76f001')
        );

        $this->assertEquals(204, $client->getResponse()->getStatusCode());
    }
}
