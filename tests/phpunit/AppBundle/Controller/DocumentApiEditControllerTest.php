<?php

namespace Tests\AppBundle\Controller;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class DocumentApiEditControllerTest extends AbstractKernelControllerTest
{
    protected $testFilesDir = __DIR__.'/../../../files';

    public function testPostDocuments()
    {
        copy(
            $this->testFilesDir.'/zipfile-ok.zip',
            $this->testFilesDir.'/zipfile2upload.zip'
        );
        $zipFile = new UploadedFile(
            $this->testFilesDir.'/zipfile2upload.zip',
            'zipfile2upload.zip'
        );

        $client = static::createClient();
        $client->request(
            'POST',
            '/api/projects/project-one/campaigns/4/suites/2/doc/zip',
            array('warning' => 50, 'success' => 60),
            array('zipfile' => $zipFile),
            array('CONTENT_TYPE' => 'multipart/form-data', 'HTTP_X-CIR-TKN' => '1f4ffb19e4b9-02278af07b7d-4e370a76f001')
        );

        $this->assertEquals(201, $client->getResponse()->getStatusCode());
    }

    public function testDeleteDocuments()
    {
        $client = static::createClient();

        $client->request(
            'DELETE',
            '/api/projects/project-one/campaigns/4/suites/1/doc/zip',
            array(),
            array(),
            array('HTTP_X-CIR-TKN' => '1f4ffb19e4b9-02278af07b7d-4e370a76f001')
        );

        $this->assertEquals(204, $client->getResponse()->getStatusCode());
    }
}
