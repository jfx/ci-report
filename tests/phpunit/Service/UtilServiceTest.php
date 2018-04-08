<?php

namespace App\Tests\Service;

use App\Service\UtilService;
use PHPUnit\Framework\TestCase;

class UtilServiceTest extends TestCase
{
    protected $utilService;

    public function setUp()
    {
        $this->utilService = new UtilService();
    }

    public function testToAsciiFrench()
    {
        $result = $this->utilService->toAscii("Peux-tu m\'aider s'il te plaît?");
        $this->assertEquals('peux-tu-m-aider-s-il-te-plait', $result);
    }

    public function testToAsciiStress()
    {
        $result = $this->utilService->toAscii("Mess'd up --text-- just (to) stress /test/ ?our! `little` clean url fun.ction!?-->");
        $this->assertEquals('mess-d-up-text-just-to-stress-test-our-little-clean-url-fun-ction', $result);
    }

    public function testToAsciiTrim()
    {
        $result = $this->utilService->toAscii('Álix----_Ãxel!?!?');
        $this->assertEquals('alix-axel', $result);
    }

    public function testToAsciiSpecialChar()
    {
        $result = $this->utilService->toAscii('ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖÙÚÛÜÝßàáâãäåæçèéêëìíîïðñòóôõöùúûüýÿ');
        $this->assertEquals('aaaaaaaeceeeeiiiienooooouuuuyszaaaaaaaeceeeeiiiienooooouuuuyy', $result);
    }

    public function testGenerateToken()
    {
        $result = $this->utilService->generateToken();
        $this->assertEquals(38, strlen($result));
    }
}
