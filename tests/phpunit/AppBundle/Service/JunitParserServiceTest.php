<?php

namespace Tests\AppBundle\Service;

use AppBundle\Entity\Status;
use AppBundle\Service\JunitParserService;
use DateInterval;
use DateTime;
use DOMDocument;
use PHPUnit\Framework\TestCase;

class JunitParserServiceTest extends TestCase
{
    protected $junitParserService;
    protected $testFilesDir = __DIR__.'/../../../files';

    public function setUp()
    {
        $this->junitParserService = new JunitParserService();
    }

    public function testValidateOkFile()
    {
        $doc = new DOMDocument();
        $doc->load($this->testFilesDir.'/junit-ok1.xml');
        $errors = $this->junitParserService->validate($doc);
        $this->assertEquals(0, count($errors));
    }

    public function testValidateKarmaFile()
    {
        $doc = new DOMDocument();
        $doc->load($this->testFilesDir.'/junit-karma.xml');
        $errors = $this->junitParserService->validate($doc);
        $this->assertEquals(0, count($errors));
    }

    public function testValidateProtractorFile()
    {
        $doc = new DOMDocument();
        $doc->load($this->testFilesDir.'/junit-protractor.xml');
        $errors = $this->junitParserService->validate($doc);
        $this->assertEquals(0, count($errors));
    }

    public function testValidateRobotFrameworkFile()
    {
        $doc = new DOMDocument();
        $doc->load($this->testFilesDir.'/junit-rf.xml');
        $errors = $this->junitParserService->validate($doc);
        $this->assertEquals(0, count($errors));
    }

    public function testValidateErrorSyntax()
    {
        $doc = new DOMDocument();
        $doc->load($this->testFilesDir.'/junit-err-syntax.xml');
        $errors = $this->junitParserService->validate($doc);
        $this->assertCount(1, $errors);
    }

    public function testValidateErrorSuiteWithoutName()
    {
        $doc = new DOMDocument();
        $doc->load($this->testFilesDir.'/junit-err-suite-without-name.xml');
        $errors = $this->junitParserService->validate($doc);
        $this->assertCount(1, $errors);
    }

    public function testValidateErrorTestWithoutName()
    {
        $doc = new DOMDocument();
        $doc->load($this->testFilesDir.'/junit-err-test-without-name.xml');
        $errors = $this->junitParserService->validate($doc);
        $this->assertCount(1, $errors);
    }

    public function testValidateErrorTestWithoutClassame()
    {
        $doc = new DOMDocument();
        $doc->load($this->testFilesDir.'/junit-err-test-without-classname.xml');
        $errors = $this->junitParserService->validate($doc);
        $this->assertCount(1, $errors);
    }

    public function testParseSuitesWithoutTests()
    {
        $doc = new DOMDocument();
        $doc->load($this->testFilesDir.'/junit-ok1.xml');
        $suitesArray = $this->junitParserService->parse($doc);

        $suiteWithoutTest = $suitesArray[0]->getSuite();
        $this->assertEquals('JUnitXmlReporter', $suiteWithoutTest->getName());
        $this->assertEquals(0, $suiteWithoutTest->getDisabled());
        $this->assertEquals(0, $suiteWithoutTest->getDuration());
        $this->assertEquals('2013-05-24T10:23:58', $suiteWithoutTest->getDatetime()->format('Y-m-d\TH:i:s'));
        $this->assertCount(0, $suitesArray[0]->getTests());
    }

    public function testParseOneSuite()
    {
        $doc = new DOMDocument();
        $doc->load($this->testFilesDir.'/junit-karma.xml');
        $suitesArray = $this->junitParserService->parse($doc);
        $this->assertCount(1, $suitesArray);

        $this->assertEquals('Chromium 58.0.3029 (Ubuntu 0.0.0)', $suitesArray[0]->getSuite()->getName());
        $this->assertCount(56, $suitesArray[0]->getTests());
    }

    public function testParseMultipleSuites()
    {
        $doc = new DOMDocument();
        $doc->load($this->testFilesDir.'/junit-ok1.xml');
        $suitesArray = $this->junitParserService->parse($doc);
        $this->assertCount(2, $suitesArray);

        $this->assertEquals('JUnitXmlReporter', $suitesArray[0]->getSuite()->getName());
        $this->assertEquals(0, $suitesArray[0]->getSuite()->getDuration());
        $this->assertEquals(0, $suitesArray[0]->getSuite()->getDisabled());
        $this->assertEquals('2013-05-24T10:23:58', $suitesArray[0]->getSuite()->getDatetime()->format('Y-m-d\TH:i:s'));
        $this->assertCount(0, $suitesArray[0]->getTests());

        $this->assertEquals('JUnitXmlReporter.constructor', $suitesArray[1]->getSuite()->getName());
        $this->assertEquals(0.005, $suitesArray[1]->getSuite()->getDuration());
        $this->assertEquals(1, $suitesArray[1]->getSuite()->getDisabled());
        $this->assertEquals('2013-05-24T10:23:59', $suitesArray[1]->getSuite()->getDatetime()->format('Y-m-d\TH:i:s'));

        $tests = $suitesArray[1]->getTests();
        $this->assertCount(4, $tests);

        $this->assertEquals('JUnitXmlReporter', $tests[0]->getPackage());
        $this->assertEquals('constructor', $tests[0]->getClassName());
        $this->assertEquals('should default consolidate to true', $tests[0]->getName());
        $this->assertEquals(0, $tests[0]->getDuration());
        $this->assertEquals(Status::ERROR, $tests[0]->getStatus());
        $this->assertEquals('Message: Error message'.PHP_EOL.' '.PHP_EOL.'Details: Error details', $tests[0]->getFailuremsg());
        $this->assertEquals('', $tests[0]->getSystemout());
        $this->assertEquals('', $tests[0]->getSystemerr());

        $this->assertEquals('JUnitXmlReporter', $tests[1]->getPackage());
        $this->assertEquals('constructor', $tests[1]->getClassName());
        $this->assertEquals('should default path to an empty string', $tests[1]->getName());
        $this->assertEquals(0.006, $tests[1]->getDuration());
        $this->assertEquals(Status::FAILED, $tests[1]->getStatus());
        $this->assertEquals('Message: test failure'.PHP_EOL.' '.PHP_EOL.'Details: Assertion failed', $tests[1]->getFailuremsg());
        $this->assertEquals('STDOUT dump', $tests[1]->getSystemout());
        $this->assertEquals('STDERR dump', $tests[1]->getSystemerr());

        $this->assertEquals('JUnitXmlReporter', $tests[2]->getPackage());
        $this->assertEquals('constructor', $tests[2]->getClassName());
        $this->assertEquals('should default consolidate to true', $tests[2]->getName());
        $this->assertEquals(0, $tests[2]->getDuration());
        $this->assertEquals(Status::SKIPPED, $tests[2]->getStatus());
        $this->assertEquals('', $tests[2]->getFailuremsg());

        $this->assertEquals('JUnitXmlReporter', $tests[3]->getPackage());
        $this->assertEquals('constructor', $tests[3]->getClassName());
        $this->assertEquals('should default useDotNotation to true', $tests[3]->getName());
        $this->assertEquals(0, $tests[3]->getDuration());
        $this->assertEquals(Status::SUCCESS, $tests[3]->getStatus());
        $this->assertEquals('', $tests[3]->getFailuremsg());
    }

    public function testParseMinimalData()
    {
        $doc = new DOMDocument();
        $doc->load($this->testFilesDir.'/junit-ok2.xml');
        $suitesArray = $this->junitParserService->parse($doc);
        $this->assertCount(1, $suitesArray);

        $this->assertEquals('JUnitXmlReporter.constructor', $suitesArray[0]->getSuite()->getName());
        $this->assertEquals(3, $suitesArray[0]->getSuite()->getDuration());
        $this->assertEquals(1, $suitesArray[0]->getSuite()->getDisabled());
        $suiteDatetime = $suitesArray[0]->getSuite()->getDatetime();
        $now = new DateTime();
        $prev = new DateTime();
        $prev->sub(new DateInterval('PT5S'));
        $this->assertGreaterThan($prev, $suiteDatetime);
        $this->assertLessThan($now, $suiteDatetime);

        $tests = $suitesArray[0]->getTests();
        $this->assertCount(3, $tests);

        $this->assertEquals('JUnitXmlReporter', $tests[0]->getPackage());
        $this->assertEquals('constructor', $tests[0]->getClassName());
        $this->assertEquals('name1', $tests[0]->getName());
        $this->assertEquals(0, $tests[0]->getDuration());
        $this->assertEquals(Status::SUCCESS, $tests[0]->getStatus());
        $this->assertEquals('', $tests[0]->getFailuremsg());
        $this->assertEquals('', $tests[0]->getSystemout());
        $this->assertEquals('', $tests[0]->getSystemerr());

        $this->assertEquals('JUnitXmlReporter', $tests[1]->getPackage());
        $this->assertEquals('constructor', $tests[1]->getClassName());
        $this->assertEquals('name2', $tests[1]->getName());
        $this->assertEquals(1, $tests[1]->getDuration());
        $this->assertEquals(Status::SUCCESS, $tests[1]->getStatus());
        $this->assertEquals('', $tests[1]->getFailuremsg());
        $this->assertEquals('', $tests[1]->getSystemout());
        $this->assertEquals('', $tests[1]->getSystemerr());
    }

    public function testParseJunitKarma()
    {
        $doc = new DOMDocument();
        $doc->load($this->testFilesDir.'/junit-karma.xml');
        $suitesArray = $this->junitParserService->parse($doc);
        $this->assertCount(1, $suitesArray);

        $this->assertEquals('Chromium 58.0.3029 (Ubuntu 0.0.0)', $suitesArray[0]->getSuite()->getName());
        $this->assertCount(56, $suitesArray[0]->getTests());
        foreach ($suitesArray[0]->getTests() as $test) {
            $this->assertEquals(Status::SUCCESS, $test->getStatus());
        }
    }

    public function testParseJunitProtractor()
    {
        $doc = new DOMDocument();
        $doc->load($this->testFilesDir.'/junit-protractor.xml');
        $suitesArray = $this->junitParserService->parse($doc);
        $this->assertCount(2, $suitesArray);

        $this->assertEquals('tam4 App', $suitesArray[0]->getSuite()->getName());
        $tests0 = $suitesArray[0]->getTests();
        $this->assertCount(1, $tests0);
        $this->assertEquals(Status::SUCCESS, $tests0[0]->getStatus());
    }

    public function testParseJunitRFPassed()
    {
        $doc = new DOMDocument();
        $doc->load($this->testFilesDir.'/junit-rf.xml');
        $suitesArray = $this->junitParserService->parse($doc);
        $this->assertCount(1, $suitesArray);

        $this->assertEquals('RF', $suitesArray[0]->getSuite()->getName());
        $tests = $suitesArray[0]->getTests();
        $this->assertCount(24, $tests);
        foreach ($tests as $test) {
            $this->assertEquals(Status::SUCCESS, $test->getStatus());
        }
    }

    public function testParseJunitRFFailed()
    {
        $doc = new DOMDocument();
        $doc->load($this->testFilesDir.'/junit-rf-testerror.xml');
        $suitesArray = $this->junitParserService->parse($doc);
        $this->assertCount(1, $suitesArray);

        $this->assertEquals('RF', $suitesArray[0]->getSuite()->getName());
        $tests = $suitesArray[0]->getTests();
        $this->assertCount(24, $tests);
        foreach ($tests as $test) {
            $this->assertEquals(Status::FAILED, $test->getStatus());
        }
    }

    public function testParseSuiteBlankName()
    {
        $doc = new DOMDocument();
        $doc->load($this->testFilesDir.'/junit-suite-blank-name.xml');
        $suitesArray = $this->junitParserService->parse($doc);
        $this->assertCount(2, $suitesArray);

        $this->assertEquals('DEFAULT_SUITE_NAME', $suitesArray[1]->getSuite()->getName());
    }

    public function testParseTestBlankName()
    {
        $doc = new DOMDocument();
        $doc->load($this->testFilesDir.'/junit-test-blank-name.xml');
        $suitesArray = $this->junitParserService->parse($doc);
        $this->assertCount(2, $suitesArray);

        $this->assertEquals('DEFAULT_NAME', $suitesArray[1]->getTests()[0]->getName());
    }

    public function testParseTestBlankClassname()
    {
        $doc = new DOMDocument();
        $doc->load($this->testFilesDir.'/junit-test-blank-classname.xml');
        $suitesArray = $this->junitParserService->parse($doc);
        $this->assertCount(2, $suitesArray);

        $this->assertEquals('_ROOT_', $suitesArray[1]->getTests()[0]->getPackage());
        $this->assertEquals('DEFAULT_CLASSNAME', $suitesArray[1]->getTests()[0]->getClassname());
    }

    public function testParseTestClassnameWithNoDot()
    {
        $doc = new DOMDocument();
        $doc->load($this->testFilesDir.'/junit-test-classname-no-dot.xml');
        $suitesArray = $this->junitParserService->parse($doc);
        $this->assertCount(2, $suitesArray);

        $this->assertEquals('_ROOT_', $suitesArray[1]->getTests()[0]->getPackage());
        $this->assertEquals('ClassnameWithNoDot', $suitesArray[1]->getTests()[0]->getClassname());
    }
}
