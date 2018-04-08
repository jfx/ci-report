<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\NullOutput;

abstract class AbstractKernelControllerTest extends WebTestCase
{
    protected $application;

    protected function setUp()
    {
        parent::setUp();
        self::bootKernel();

        $this->application = new Application(self::$kernel);
        $this->application->setAutoExit(false);
    }

    protected function execute($command)
    {
        $input = new StringInput($command);
        $output = new NullOutput();

        $this->application->run($input, $output);
    }

    protected function tearDown()
    {
        parent::tearDown();

        $this->execute('doctrine:database:drop --force --no-interaction');
        $this->execute('doctrine:database:create --no-interaction');
        $this->execute('doctrine:schema:create --no-interaction');
        $this->execute('doctrine:fixtures:load --no-interaction');
    }
}
