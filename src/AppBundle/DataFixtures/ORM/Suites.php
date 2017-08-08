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
namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Suite;
use DateTime;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class Suites extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * Load data fixtures with the passed EntityManager.
     *
     * @param ObjectManager $manager The entity manager
     */
    public function load(ObjectManager $manager)
    {
        $ref1Date = new DateTime();
        $ref1Date->setDate(2017, 7, 1)->setTime(12, 30, 1);
        $ref2Date = new DateTime();
        $ref2Date->setDate(2017, 7, 2)->setTime(12, 30, 1);
        $ref3Date = new DateTime();
        $ref3Date->setDate(2017, 7, 3)->setTime(12, 30, 1);
        $ref4Date = new DateTime();
        $ref4Date->setDate(2017, 7, 4)->setTime(12, 30, 1);

        $dataArray = array(
            array(
                'name' => 'Name of suite 1 for campaign 1',
                'passed' => '12',
                'failed' => '0',
                'errored' => '0',
                'skipped' => '0',
                'disabled' => '0',
                'duration' => '1.1',
                'datetime' => $ref1Date,
                'campaign' => 'p1c1-campaign',
                'suiteRef' => 'p1c1s1',
            ),
            array(
                'name' => 'Name of suite 2 for campaign 1',
                'passed' => '13',
                'failed' => '0',
                'errored' => '0',
                'skipped' => '0',
                'disabled' => '0',
                'duration' => '1.1',
                'datetime' => $ref1Date,
                'campaign' => 'p1c1-campaign',
                'suiteRef' => 'p1c1s2',
            ),
            array(
                'name' => 'Name of suite 1 for campaign 2',
                'passed' => '12',
                'failed' => '0',
                'errored' => '1',
                'skipped' => '0',
                'disabled' => '0',
                'duration' => '1.1',
                'datetime' => $ref2Date,
                'campaign' => 'p1c2-campaign',
                'suiteRef' => 'p1c2s1',
            ),
            array(
                'name' => 'Name of suite 2 for campaign 2',
                'passed' => '13',
                'failed' => '0',
                'errored' => '0',
                'skipped' => '0',
                'disabled' => '0',
                'duration' => '1.1',
                'datetime' => $ref2Date,
                'campaign' => 'p1c2-campaign',
                'suiteRef' => 'p1c2s2',
            ),
            array(
                'name' => 'Name of suite 1 for campaign 3',
                'passed' => '12',
                'failed' => '1',
                'errored' => '0',
                'skipped' => '0',
                'disabled' => '0',
                'duration' => '1.1',
                'datetime' => $ref3Date,
                'campaign' => 'p1c3-campaign',
                'suiteRef' => 'p1c3s1',
            ),
            array(
                'name' => 'Name of suite 2 for campaign 3',
                'passed' => '13',
                'failed' => '0',
                'errored' => '0',
                'skipped' => '0',
                'disabled' => '0',
                'duration' => '1.1',
                'datetime' => $ref3Date,
                'campaign' => 'p1c3-campaign',
                'suiteRef' => 'p1c3s2',
            ),
            array(
                'name' => 'Name of suite 1 for campaign 4',
                'passed' => '13',
                'failed' => '0',
                'errored' => '0',
                'skipped' => '0',
                'disabled' => '0',
                'duration' => '1.1',
                'datetime' => $ref4Date,
                'campaign' => 'p1c4-campaign',
                'suiteRef' => 'p1c4s1',
            ),
            array(
                'name' => 'Name of suite 2 for campaign 4',
                'passed' => '13',
                'failed' => '0',
                'errored' => '0',
                'skipped' => '0',
                'disabled' => '0',
                'duration' => '1.1',
                'datetime' => $ref4Date,
                'campaign' => 'p1c4-campaign',
                'suiteRef' => 'p1c4s2',
            ),
            array(
                'name' => 'Name of suite 1 for campaign 1 Project 2',
                'passed' => '79',
                'failed' => '21',
                'errored' => '0',
                'skipped' => '0',
                'disabled' => '0',
                'duration' => '1.1',
                'datetime' => $ref1Date,
                'campaign' => 'p2c1-campaign',
                'suiteRef' => 'p2c1s1',
            ),
            array(
                'name' => 'Name of suite 1 for campaign 2 Project 2',
                'passed' => '80',
                'failed' => '20',
                'errored' => '0',
                'skipped' => '0',
                'disabled' => '0',
                'duration' => '1.1',
                'datetime' => $ref2Date,
                'campaign' => 'p2c2-campaign',
                'suiteRef' => 'p2c2s1',
            ),
            array(
                'name' => 'Name of suite 1 for campaign 3 Project 2',
                'passed' => '95',
                'failed' => '4',
                'errored' => '0',
                'skipped' => '1',
                'disabled' => '0',
                'duration' => '1.1',
                'datetime' => $ref3Date,
                'campaign' => 'p2c3-campaign',
                'suiteRef' => 'p2c3s1',
            ),
            array(
                'name' => 'Name of suite 1 for campaign 1 Project 3',
                'passed' => '95',
                'failed' => '5',
                'errored' => '0',
                'skipped' => '0',
                'disabled' => '0',
                'duration' => '1.1',
                'datetime' => $ref1Date,
                'campaign' => 'p3c1-campaign',
                'suiteRef' => 'p3c1s1',
            ),
            array(
                'name' => 'Name of suite 1 for campaign 2 Project 3',
                'passed' => '79',
                'failed' => '21',
                'errored' => '0',
                'skipped' => '0',
                'disabled' => '0',
                'duration' => '1.1',
                'datetime' => $ref2Date,
                'campaign' => 'p3c2-campaign',
                'suiteRef' => 'p3c2s1',
            ),
            array(
                'name' => 'Name of suite 1 for campaign 3 Project 3',
                'passed' => '80',
                'failed' => '20',
                'errored' => '0',
                'skipped' => '0',
                'disabled' => '0',
                'duration' => '1.1',
                'datetime' => $ref3Date,
                'campaign' => 'p3c3-campaign',
                'suiteRef' => 'p3c3s1',
            ),
            array(
                'name' => 'Name of suite 1 for campaign 1 Project 4',
                'passed' => '95',
                'failed' => '0',
                'errored' => '5',
                'skipped' => '0',
                'disabled' => '0',
                'duration' => '1.1',
                'datetime' => $ref1Date,
                'campaign' => 'p4c1-campaign',
                'suiteRef' => 'p4c1s1',
            ),
            array(
                'name' => 'Name of suite 1 for campaign 2 Project 4',
                'passed' => '80',
                'failed' => '0',
                'errored' => '20',
                'skipped' => '0',
                'disabled' => '0',
                'duration' => '1.1',
                'datetime' => $ref2Date,
                'campaign' => 'p4c2-campaign',
                'suiteRef' => 'p4c2s1',
            ),
            array(
                'name' => 'Name of suite 1 for campaign 3 Project 4',
                'passed' => '79',
                'failed' => '0',
                'errored' => '21',
                'skipped' => '0',
                'disabled' => '0',
                'duration' => '1.1',
                'datetime' => $ref3Date,
                'campaign' => 'p4c3-campaign',
                'suiteRef' => 'p4c3s1',
            ),
            array(
                'name' => 'Name of suite 1 for campaign 1 Project 5',
                'passed' => '79',
                'failed' => '0',
                'errored' => '0',
                'skipped' => '21',
                'disabled' => '0',
                'duration' => '1.1',
                'datetime' => $ref1Date,
                'campaign' => 'p5c1-campaign',
                'suiteRef' => 'p5c1s1',
            ),
            array(
                'name' => 'Name of suite 1 for campaign 2 Project 5',
                'passed' => '80',
                'failed' => '0',
                'errored' => '0',
                'skipped' => '20',
                'disabled' => '0',
                'duration' => '1.1',
                'datetime' => $ref2Date,
                'campaign' => 'p5c2-campaign',
                'suiteRef' => 'p5c2s1',
            ),
            array(
                'name' => 'Name of suite 1 for campaign 3 Project 5',
                'passed' => '95',
                'failed' => '0',
                'errored' => '0',
                'skipped' => '5',
                'disabled' => '0',
                'duration' => '1.1',
                'datetime' => $ref3Date,
                'campaign' => 'p5c3-campaign',
                'suiteRef' => 'p5c3s1',
            ),
            array(
                'name' => 'Name of suite 1 for campaign 4 Project 5',
                'passed' => '50',
                'failed' => '0',
                'errored' => '0',
                'skipped' => '0',
                'disabled' => '50',
                'duration' => '1.1',
                'datetime' => $ref4Date,
                'campaign' => 'p5c4-campaign',
                'suiteRef' => 'p5c4s1',
            ),
        );
        $objectList = array();
        foreach ($dataArray as $i => $data) {
            $objectList[$i] = new Suite($this->getReference($data['campaign']));
            $objectList[$i]->setName($data['name']);
            $objectList[$i]->setPassed($data['passed']);
            $objectList[$i]->setFailed($data['failed']);
            $objectList[$i]->setErrored($data['errored']);
            $objectList[$i]->setSkipped($data['skipped']);
            $objectList[$i]->setDisabled($data['disabled']);
            $objectList[$i]->setDuration($data['duration']);
            $objectList[$i]->setDatetimeSuite($data['datetime']);

            $manager->persist($objectList[$i]);
            $ref = $data['suiteRef'].'-suite';
            $this->addReference($ref, $objectList[$i]);
        }
        $manager->flush();
    }

    /**
     * Get the order of this fixture.
     *
     * @return int
     */
    public function getOrder()
    {
        return 30;
    }
}
