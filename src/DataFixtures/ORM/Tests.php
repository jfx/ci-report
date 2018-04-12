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

namespace App\DataFixtures\ORM;

use App\Entity\Test;
use Doctrine\Bundle\FixturesBundle\ORMFixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Test fixtures load class.
 *
 * @category  ci-report app
 *
 * @author    Francois-Xavier Soubirou <soubirou@yahoo.fr>
 * @copyright 2017 Francois-Xavier Soubirou
 * @license   http://www.gnu.org/licenses/   GPLv3
 *
 * @see      https://www.ci-report.io
 */
class Tests extends AbstractFixture implements OrderedFixtureInterface, ORMFixtureInterface
{
    /**
     * Load data fixtures with the passed EntityManager.
     *
     * @param ObjectManager $manager The entity manager
     */
    public function load(ObjectManager $manager)
    {
        $systemOut = <<<'EOT'
System out message :
  - Out 1
  - Out 2
EOT;
        $systemErr = <<<'EOT'
System error message :
  - Error 1,
  - Error 2,
  - Error 3.
EOT;
        $failureMsg = <<<'EOT'
Type: Type of message
 
Message: Message
    
Details: Details of message ...
EOT;
        $dataArray = array(
            array(
                'count' => '12',
                'fullClassName' => array(
                    'className2',
                    'className2',
                    'className1',
                    'className1',
                    'className1',
                    'package1.className1',
                    'io.ci-report.className3',
                    'io.ci-report.className3',
                    'io.ci-report.className3',
                    'io.ci-report.className4',
                    'io.ci-report.package.className4',
                    'io.ci-report.package.className5',
                ),
                'status' => array(Test::PASSED, Test::PASSED, Test::PASSED, Test::PASSED, Test::PASSED, Test::PASSED, Test::PASSED, Test::PASSED, Test::PASSED, Test::PASSED, Test::PASSED, Test::PASSED),
                'duration' => 1,
                'systemOut' => $systemOut,
                'systemErr' => $systemErr,
                'failureMsg' => $failureMsg,
                'suite' => 'p1c1s1-suite',
            ),
            array(
                'count' => '13',
                'fullClassName' => array(
                    'className2',
                    'className2',
                    'className1',
                    'className1',
                    'className1',
                    'package1.className1',
                    'io.ci-report.className3',
                    'io.ci-report.className3',
                    'io.ci-report.className3',
                    'io.ci-report.className4',
                    'io.ci-report.package.className4',
                    'io.ci-report.package.className5',
                    'io.ci-report.package.className6',
                ),
                'status' => array(Test::PASSED, Test::PASSED, Test::PASSED, Test::PASSED, Test::PASSED, Test::PASSED, Test::PASSED, Test::PASSED, Test::PASSED, Test::PASSED, Test::PASSED, Test::PASSED, Test::PASSED),
                'duration' => 1,
                'systemOut' => $systemOut,
                'systemErr' => $systemErr,
                'failureMsg' => $failureMsg,
                'suite' => 'p1c1s2-suite',
            ),
            array(
                'count' => '13',
                'fullClassName' => array(
                    'className2',
                    'className2',
                    'className1',
                    'className1',
                    'className1',
                    'package1.className1',
                    'io.ci-report.className3',
                    'io.ci-report.className3',
                    'io.ci-report.className3',
                    'io.ci-report.className4',
                    'io.ci-report.package.className4',
                    'io.ci-report.package.className5',
                    'io.ci-report.package.className6',
                ),
                'status' => array(Test::PASSED, Test::ERRORED, Test::PASSED, Test::PASSED, Test::PASSED, Test::PASSED, Test::PASSED, Test::PASSED, Test::PASSED, Test::PASSED, Test::PASSED, Test::PASSED, Test::PASSED),
                'duration' => 1,
                'systemOut' => $systemOut,
                'systemErr' => $systemErr,
                'failureMsg' => $failureMsg,
                'suite' => 'p1c2s1-suite',
            ),
            array(
                'count' => '13',
                'fullClassName' => array(
                    'className2',
                    'className2',
                    'className1',
                    'className1',
                    'className1',
                    'package1.className1',
                    'io.ci-report.className3',
                    'io.ci-report.className3',
                    'io.ci-report.className3',
                    'io.ci-report.className4',
                    'io.ci-report.package.className4',
                    'io.ci-report.package.className5',
                    'io.ci-report.package.className6',
                ),
                'status' => array(Test::PASSED, Test::PASSED, Test::PASSED, Test::PASSED, Test::PASSED, Test::PASSED, Test::PASSED, Test::PASSED, Test::PASSED, Test::PASSED, Test::PASSED, Test::PASSED, Test::PASSED),
                'duration' => 1,
                'systemOut' => $systemOut,
                'systemErr' => $systemErr,
                'failureMsg' => $failureMsg,
                'suite' => 'p1c2s2-suite',
            ),
            array(
                'count' => '13',
                'fullClassName' => array(
                    'className2',
                    'className2',
                    'className1',
                    'className1',
                    'className1',
                    'package1.className1',
                    'io.ci-report.className3',
                    'io.ci-report.className3',
                    'io.ci-report.className3',
                    'io.ci-report.className4',
                    'io.ci-report.package.className4',
                    'io.ci-report.package.className5',
                    'io.ci-report.package.className6',
                ),
                'status' => array(Test::PASSED, Test::FAILED, Test::PASSED, Test::PASSED, Test::PASSED, Test::PASSED, Test::PASSED, Test::PASSED, Test::PASSED, Test::PASSED, Test::PASSED, Test::PASSED, Test::PASSED),
                'duration' => 1,
                'systemOut' => $systemOut,
                'systemErr' => $systemErr,
                'failureMsg' => $failureMsg,
                'suite' => 'p1c3s1-suite',
            ),
            array(
                'count' => '13',
                'fullClassName' => array(
                    'className2',
                    'className2',
                    'className1',
                    'className1',
                    'className1',
                    'package1.className1',
                    'io.ci-report.className3',
                    'io.ci-report.className3',
                    'io.ci-report.className3',
                    'io.ci-report.className4',
                    'io.ci-report.package.className4',
                    'io.ci-report.package.className5',
                    'io.ci-report.package.className6',
                ),
                'status' => array(Test::PASSED, Test::PASSED, Test::PASSED, Test::PASSED, Test::PASSED, Test::PASSED, Test::PASSED, Test::PASSED, Test::PASSED, Test::PASSED, Test::PASSED, Test::PASSED, Test::PASSED),
                'duration' => 1,
                'systemOut' => $systemOut,
                'systemErr' => $systemErr,
                'failureMsg' => $failureMsg,
                'suite' => 'p1c3s2-suite',
            ),
            array(
                'count' => '13',
                'fullClassName' => array(
                    'className2',
                    'className2',
                    'className1',
                    'className1',
                    'className1',
                    'package1.className1',
                    'io.ci-report.className3',
                    'io.ci-report.className3',
                    'io.ci-report.className3',
                    'io.ci-report.className4',
                    'io.ci-report.package.className4',
                    'io.ci-report.package.className5',
                    'io.ci-report.package.className6',
                ),
                'status' => array(Test::PASSED, Test::PASSED, Test::PASSED, Test::PASSED, Test::PASSED, Test::PASSED, Test::PASSED, Test::PASSED, Test::PASSED, Test::PASSED, Test::PASSED, Test::PASSED, Test::PASSED),
                'duration' => 1,
                'systemOut' => '',
                'systemErr' => '',
                'failureMsg' => '',
                'suite' => 'p1c4s1-suite',
            ),
            array(
                'count' => '13',
                'fullClassName' => array(
                    'className2',
                    'className2',
                    'className1',
                    'className1',
                    'className1',
                    'package1.className1',
                    'io.ci-report.className3',
                    'io.ci-report.className3',
                    'io.ci-report.className3',
                    'io.ci-report.className4',
                    'io.ci-report.package.className4',
                    'io.ci-report.package.className5',
                    'io.ci-report.package.className6',
                ),
                'status' => array(Test::PASSED, Test::PASSED, Test::PASSED, Test::PASSED, Test::PASSED, Test::PASSED, Test::PASSED, Test::PASSED, Test::PASSED, Test::PASSED, Test::PASSED, Test::PASSED, Test::PASSED),
                'duration' => 1,
                'systemOut' => $systemOut,
                'systemErr' => $systemErr,
                'failureMsg' => $failureMsg,
                'suite' => 'p1c4s2-suite',
            ),
            $this->fillInTestArray(79, 21, 0, 0, 'p2c1s1-suite'),
            $this->fillInTestArray(80, 20, 0, 0, 'p2c2s1-suite'),
            $this->fillInTestArray(95, 4, 0, 1, 'p2c3s1-suite'),
            $this->fillInTestArray(95, 5, 0, 0, 'p3c1s1-suite'),
            $this->fillInTestArray(79, 21, 0, 0, 'p3c2s1-suite'),
            $this->fillInTestArray(80, 20, 0, 0, 'p3c3s1-suite'),
            $this->fillInTestArray(95, 0, 5, 0, 'p4c1s1-suite'),
            $this->fillInTestArray(80, 0, 20, 0, 'p4c2s1-suite'),
            $this->fillInTestArray(79, 0, 21, 0, 'p4c3s1-suite'),
            $this->fillInTestArray(79, 0, 0, 21, 'p5c1s1-suite'),
            $this->fillInTestArray(80, 0, 0, 20, 'p5c2s1-suite'),
            $this->fillInTestArray(95, 0, 0, 5, 'p5c3s1-suite'),
            $this->fillInTestArray(50, 0, 0, 0, 'p5c4s1-suite'),
        );

        $objectList = array();
        foreach ($dataArray as $i => $data) {
            for ($j = 0; $j < $data['count']; ++$j) {
                $k = $i.$j;
                $objectList[$k] = new Test($this->getReference($data['suite']));
                $objectList[$k]->setName('Test '.$j.' in '.$data['suite']);
                $objectList[$k]->setFullClassName($data['fullClassName'][$j]);
                $objectList[$k]->setStatus($data['status'][$j]);
                $objectList[$k]->setDuration($data['duration'] + 0.1 * $j);
                if ('' !== $data['systemOut']) {
                    $objectList[$k]->setSystemout($k.' : '.$data['systemOut']);
                }
                if ('' !== $data['systemErr']) {
                    $objectList[$k]->setSystemerr($k.' : '.$data['systemErr']);
                }
                if ('' !== $data['failureMsg']) {
                    $objectList[$k]->setFailuremsg($data['failureMsg']);
                }

                $manager->persist($objectList[$k]);
            }
        }
        $manager->flush();
    }

    /**
     * Fill in array with default test values.
     *
     * @param int    $passed  Amount of passed tests.
     * @param int    $failed  Amount of failed tests.
     * @param int    $errored Amount of errored tests.
     * @param int    $skipped Amount of skipped tests.
     * @param string $suite   Suite reference.
     *
     * @return array
     */
    public function fillInTestArray($passed, $failed, $errored, $skipped, $suite)
    {
        $count = $passed + $failed + $errored + $skipped;

        $fillInArray = array();

        $fillInArray['count'] = $count;
        $fillInArray['suite'] = $suite;

        $arrayFullClassName = array();
        for ($i = 0; $i < $count; ++$i) {
            $index = $i % 10;
            $arrayFullClassName[] = 'io.ci-report.className'.$index;
        }
        $fillInArray['fullClassName'] = $arrayFullClassName;

        $arrayStatus = array();
        for ($i = 0; $i < $passed; ++$i) {
            $arrayStatus[] = Test::PASSED;
        }
        for ($i = 0; $i < $failed; ++$i) {
            $arrayStatus[] = Test::FAILED;
        }
        for ($i = 0; $i < $errored; ++$i) {
            $arrayStatus[] = Test::ERRORED;
        }
        for ($i = 0; $i < $skipped; ++$i) {
            $arrayStatus[] = Test::SKIPPED;
        }
        $fillInArray['status'] = $arrayStatus;

        $fillInArray['duration'] = 1;
        $fillInArray['systemOut'] = 'systemOutMessage';
        $fillInArray['systemErr'] = 'systemErrMessage';
        $fillInArray['failureMsg'] = 'failureMessage';

        return $fillInArray;
    }

    /**
     * Get the order of this fixture.
     *
     * @return int
     */
    public function getOrder(): int
    {
        return 40;
    }
}
