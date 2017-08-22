<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProjectApiControllerTest extends WebTestCase
{
    public function testGetProjects()
    {
        $client = static::createClient();

        $client->request('GET', '/api/projects');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
    
    public function testGetProjectsNotFound()
    {
        $client = static::createClient();

        $client->request('GET', '/api/projects/X');

        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }
    
    public function testPostProjects()
    {
        $client = static::createClient();

        $client->request(
            'POST',
            '/api/projects',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            '{"name":"Project Added"}'
        );

        $this->assertEquals(201, $client->getResponse()->getStatusCode());
    }
}
