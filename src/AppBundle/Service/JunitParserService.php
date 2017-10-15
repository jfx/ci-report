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

namespace AppBundle\Service;

use AppBundle\DTO\SuiteDTO;
use AppBundle\DTO\TestDTO;
use AppBundle\Entity\Status;
use AppBundle\Util\SuiteTests;
use DateTime;
use DOMDocument;
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
    protected $schemaPath = __DIR__.'/../../../junit/xsd/junit-10.xsd';

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

        $domDoc->schemaValidate($this->schemaPath);
        $errors = libxml_get_errors();

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
        $suite->setName((string) $xmlTestsuite['name']);

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
        $test->setName((string) $xmlTestcase['name']);

        // Classname is required.
        // set package.class, if no dot use default package
        $test->setFullClassName((string) $xmlTestcase['classname']);

        // If time not set, initialize at 0
        if (isset($xmlTestcase['time'])) {
            $test->setDuration((float) $xmlTestcase['time']);
        } else {
            $test->setDuration(0);
        }

        // If system-out set
        if (isset($xmlTestcase->{'system-out'})) {
            $test->setSystemOut((string) $xmlTestcase->{'system-out'});
        }

        // If system-err set
        if (isset($xmlTestcase->{'system-err'})) {
            $test->setSystemErr((string) $xmlTestcase->{'system-err'});
        }

        // If error
        if (isset($xmlTestcase->error)) {
            $test->setStatus(Status::ERROR);
            $message = $this->formatErrorFailSkipMessage(
                $xmlTestcase->error
            );
            $test->setErrorFailSkipMessage($message);
        } elseif (isset($xmlTestcase->failure)) {
            $test->setStatus(Status::FAILED);
            $message = $this->formatErrorFailSkipMessage(
                $xmlTestcase->failure
            );
            $test->setErrorFailSkipMessage($message);
        } elseif (isset($xmlTestcase->skipped)) {
            $test->setStatus(Status::SKIPPED);
            $message = $this->formatErrorFailSkipMessage(
                $xmlTestcase->skipped
            );
            $test->setErrorFailSkipMessage($message);
        } else {
            $test->setStatus(Status::SUCCESS);
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
        $type = '';
        if (isset($elt['type'])) {
            $type = (string) $elt['type'];
        }
        $message = '';
        if (isset($elt['message'])) {
            $message = (string) $elt['message'];
        }
        $value = '';
        if (isset($elt)) {
            $value = (string) $elt;
        }
        if ((strlen($type) > 0) && (strlen($message) > 0)) {
            $fullmessage = $type.'---'.$message;
        } else {
            $fullmessage = $type.$message;
        }
        if ((strlen($fullmessage) > 0) && (strlen($value) > 0)) {
            $fullmessage = $fullmessage.'---'.$value;
        } else {
            $fullmessage = $fullmessage.$value;
        }

        return $fullmessage;
    }
}
