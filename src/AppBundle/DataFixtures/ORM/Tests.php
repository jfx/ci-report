<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Test;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class Tests extends AbstractFixture implements OrderedFixtureInterface
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
                'status' => array(1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1),
                'duration' => '1',
                'systemOut' => $systemOut,
                'systemErr' => $systemErr,
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
                'status' => array(1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1),
                'duration' => '1',
                'systemOut' => $systemOut,
                'systemErr' => $systemErr,
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
                'status' => array(1, 4, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1),
                'duration' => '1',
                'systemOut' => $systemOut,
                'systemErr' => $systemErr,
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
                'status' => array(1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1),
                'duration' => '1',
                'systemOut' => $systemOut,
                'systemErr' => $systemErr,
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
                'status' => array(1, 2, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1),
                'duration' => '1',
                'systemOut' => $systemOut,
                'systemErr' => $systemErr,
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
                'status' => array(1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1),
                'duration' => '1',
                'systemOut' => $systemOut,
                'systemErr' => $systemErr,
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
                'status' => array(1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1),
                'duration' => '1',
                'systemOut' => $systemOut,
                'systemErr' => $systemErr,
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
                'status' => array(1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1),
                'duration' => '1',
                'systemOut' => $systemOut,
                'systemErr' => $systemErr,
                'suite' => 'p1c4s2-suite',
            ),
            $this->fillInTestArray(79, 21, 0, 0, 'p2c1s1-suite'),
            $this->fillInTestArray(80, 20, 0, 0, 'p2c2s1-suite'),
            $this->fillInTestArray(95, 5, 0, 0, 'p2c3s1-suite'),
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
                $objectList[$k]->setDuration($data['duration'].'.'.$j);
                $objectList[$k]->setSystemOut($k.' : '.$data['systemOut']);
                $objectList[$k]->setSystemErr($k.' : '.$data['systemErr']);

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
            $arrayStatus[] = 1;
        }
        for ($i = 0; $i < $failed; ++$i) {
            $arrayStatus[] = 2;
        }
        for ($i = 0; $i < $errored; ++$i) {
            $arrayStatus[] = 4;
        }
        for ($i = 0; $i < $skipped; ++$i) {
            $arrayStatus[] = 8;
        }
        $fillInArray['status'] = $arrayStatus;

        $fillInArray['duration'] = '1';
        $fillInArray['systemOut'] = 'systemOutMessage';
        $fillInArray['systemErr'] = 'systemErrMessage';

        return $fillInArray;
    }

    /**
     * Get the order of this fixture.
     *
     * @return int
     */
    public function getOrder()
    {
        return 40;
    }
}
