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

    public function testGetProject()
    {
        $client = static::createClient();

        $client->request('GET', '/api/projects/project-one');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testGetProjectPrivate()
    {
        $client = static::createClient();

        $client->request(
            'GET',
            '/api/projects/project-one/private',
            array(),
            array(),
            array('HTTP_X-CIR-TKN' => '1f4ffb19e4b9-02278af07b7d-4e370a76f001')
        );

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testGetProjectsNotFound()
    {
        $client = static::createClient();

        $client->request('GET', '/api/projects/X');

        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }
}
