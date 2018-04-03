<?php

/**
 * Copyright (c) 2017 Francois-Xavier Soubirou.
 *
 * This file is part of ci-report.
 *
 * ci-report is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * ci-report is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with ci-report. If not, see <http://www.gnu.org/licenses/>.
 */
declare(strict_types=1);

namespace App\Service;

use App\DTO\SuiteDTO;
use App\DTO\TestDTO;
use App\Entity\Suite;
use App\Entity\Test;
use App\Util\SuiteTests;
use DateTime;
use DOMDocument;
use ReflectionClass;
use SimpleXMLElement;

/**
 * Junit Parser service class.
 *
 * @category  ci-report app
 *
 * @author    Francois-Xavier Soubirou <soubirou@yahoo.fr>
 * @copyright 2017 Francois-Xavier Soubirou
 * @license   http://www.gnu.org/licenses/   GPLv3
 *
 * @see      https://www.ci-report.io
 */
class JunitParserService
{
    /**
     * @var string
     */
    protected $schemaRelativePath = '/../../../junit/xsd/junit-10.xsd';

    /**
     * @var string
     */
    const FAILURE_MSG_SEPARATOR = PHP_EOL.' '.PHP_EOL;

    /**
     * Validate the XML document.
     *
     * @param DOMDocument $domDoc XML document to validate
     *
     * @return array
     */
    public function validate(DOMDocument $domDoc): array
    {
        libxml_use_internal_errors(true);
        libxml_clear_errors();

        $reflClass = new ReflectionClass(get_class($this));
        $schemaAbsolutePath = dirname($reflClass->getFileName()).$this->schemaRelativePath;
        $domDoc->schemaValidate($schemaAbsolutePath);
        $errors = array();

        foreach (libxml_get_errors() as $libxmlError) {
            switch ($libxmlError->level) {
                case LIBXML_ERR_ERROR:
                    $level = 'Error';
                    break;
                case LIBXML_ERR_FATAL:
                    $level = 'Fatal error';
                    break;
                default:
                    $level = 'Warning';
                    break;
            }
            $error = array(
                'level' => $level,
                'line' => $libxmlError->line,
                'message' => $libxmlError->message,
            );
            $errors[] = $error;
        }

        libxml_clear_errors();

        return $errors;
    }

    /**
     * Parse the XML string.
     *
     * @param DOMDocument $domDoc XML document to parse
     *
     * @return array array(suiteTests, ...)
     */
    public function parse(DOMDocument $domDoc): array
    {
        $suitesArray = array();

        $xml = simplexml_import_dom($domDoc);

        if ('testsuites' === $xml->getName()) {
            foreach ($xml->testsuite as $xmlTestsuite) {
                $suiteTests = $this->parseSuiteTests($xmlTestsuite);
                $suitesArray[] = $suiteTests;
            }
        } elseif ('testsuite' === $xml->getName()) {
            $suiteTests = $this->parseSuiteTests($xml);
            $suitesArray[] = $suiteTests;
        }

        return $suitesArray;
    }

    /**
     * Parse the XML element.
     *
     * @param SimpleXMLElement $xmlTestsuite Element to parse
     *
     * @return suiteTests
     */
    private function parseSuiteTests(SimpleXMLElement $xmlTestsuite): suiteTests
    {
        $suite = new SuiteDTO();
        $suiteTests = new SuiteTests($suite);

        // Name is required
        $name = (string) $xmlTestsuite['name'];
        if (0 === strlen($name)) {
            $suite->setName(Suite::DEFAULT_NAME);
        } else {
            $suite->setName($name);
        }

        // If timestamp not set, initialize with now value
        if (isset($xmlTestsuite['timestamp'])) {
            $datetime = new DateTime((string) $xmlTestsuite['timestamp']);
        } else {
            $datetime = new DateTime();
        }
        $suite->setDatetime($datetime);

        $totalTestDuration = 0;
        $testsCount = 0;

        foreach ($xmlTestsuite->testcase as $xmlTestcase) {
            $test = $this->parseTest($xmlTestcase);

            $totalTestDuration += $test->getDuration();
            ++$testsCount;

            $suiteTests->addTest($test);
        }
        // If time not set, set it to sum of tests duration
        if (isset($xmlTestsuite['time'])) {
            $suite->setDuration((float) $xmlTestsuite['time']);
        } else {
            $suite->setDuration((float) $totalTestDuration);
        }
        // If disabled not set, compare tests attribute value and total of tests
        if (isset($xmlTestsuite['disabled'])) {
            $suite->setDisabled((int) $xmlTestsuite['disabled']);
        } else {
            // tests attribute is required
            $deltaTests = ((int) $xmlTestsuite['tests']) - $testsCount;
            if ($deltaTests > 0) {
                $suite->setDisabled($deltaTests);
            }
        }

        return $suiteTests;
    }

    /**
     * Parse the XML element.
     *
     * @param SimpleXMLElement $xmlTestcase Element to parse
     *
     * @return testDTO
     */
    private function parseTest(SimpleXMLElement $xmlTestcase): testDTO
    {
        $test = new TestDTO();

        // Name is required
        $name = (string) $xmlTestcase['name'];
        if (0 === strlen($name)) {
            $test->setName(Test::DEFAULT_NAME);
        } else {
            $test->setName($name);
        }

        // Classname is required.
        // set package.class, if no dot use default package
        $fullClassname = (string) $xmlTestcase['classname'];
        if (0 === strlen($fullClassname)) {
            $test->setFullclassname(Test::DEFAULT_CLASSNAME);
        } else {
            $test->setFullclassname($fullClassname);
        }

        // If time not set, initialize at 0
        if (isset($xmlTestcase['time'])) {
            $test->setDuration((float) $xmlTestcase['time']);
        } else {
            $test->setDuration(0);
        }

        // If system-out set
        if (isset($xmlTestcase->{'system-out'})) {
            $test->setSystemout((string) $xmlTestcase->{'system-out'});
        }

        // If system-err set
        if (isset($xmlTestcase->{'system-err'})) {
            $test->setSystemerr((string) $xmlTestcase->{'system-err'});
        }

        // If error
        if (isset($xmlTestcase->error)) {
            $test->setStatus(Test::ERRORED);
            $message = $this->formatErrorFailSkipMessage(
                $xmlTestcase->error
            );
            $test->setFailuremsg($message);
        } elseif (isset($xmlTestcase->failure)) {
            $test->setStatus(Test::FAILED);
            $message = $this->formatErrorFailSkipMessage(
                $xmlTestcase->failure
            );
            $test->setFailuremsg($message);
        } elseif (isset($xmlTestcase->skipped)) {
            $test->setStatus(Test::SKIPPED);
            $message = $this->formatErrorFailSkipMessage(
                $xmlTestcase->skipped
            );
            $test->setFailuremsg($message);
        } else {
            $test->setStatus(Test::PASSED);
        }

        return $test;
    }

    /**
     * Parse the XML element.
     *
     * @param SimpleXMLElement $elt Element to parse
     *
     * @return string
     */
    private function formatErrorFailSkipMessage(SimpleXMLElement $elt): string
    {
        if (isset($elt['type'])) {
            $type = 'Type: '.(string) $elt['type'];
        } else {
            $type = '';
        }
        if (isset($elt['message'])) {
            $message = 'Message: '.(string) $elt['message'];
        } else {
            $message = '';
        }
        if (isset($elt) && (strlen((string) $elt) > 0)) {
            $value = 'Details: '.(string) $elt;
        } else {
            $value = '';
        }
        if ((strlen($type) > 0) && (strlen($message) > 0)) {
            $fullmessage = $type.self::FAILURE_MSG_SEPARATOR.$message;
        } else {
            $fullmessage = $type.$message;
        }
        if ((strlen($fullmessage) > 0) && (strlen($value) > 0)) {
            $fullmessage = $fullmessage.self::FAILURE_MSG_SEPARATOR.$value;
        } else {
            $fullmessage = $fullmessage.$value;
        }

        return $fullmessage;
    }
}
