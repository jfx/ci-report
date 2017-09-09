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

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Campaign;
use DateTime;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Campaign fixtures load class.
 *
 * @category  ci-report app
 *
 * @author    Francois-Xavier Soubirou <soubirou@yahoo.fr>
 * @copyright 2017 Francois-Xavier Soubirou
 * @license   http://www.gnu.org/licenses/   GPLv3
 *
 * @see      https://www.ci-report.io
 */
class Campaigns extends AbstractFixture implements OrderedFixtureInterface
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
                'passed' => 25,
                'failed' => 0,
                'errored' => 0,
                'skipped' => 0,
                'disabled' => 0,
                'duration' => 1.1,
                'datetime' => $ref1Date,
                'project' => 'projectone-project',
                'campaignRef' => 'p1c1',
            ),
            array(
                'passed' => 25,
                'failed' => 0,
                'errored' => 1,
                'skipped' => 0,
                'disabled' => 0,
                'duration' => 1.1,
                'datetime' => $ref2Date,
                'project' => 'projectone-project',
                'campaignRef' => 'p1c2',
            ),
            array(
                'passed' => 25,
                'failed' => 1,
                'errored' => 0,
                'skipped' => 0,
                'disabled' => 0,
                'duration' => 1.3,
                'datetime' => $ref3Date,
                'project' => 'projectone-project',
                'campaignRef' => 'p1c3',
            ),
            array(
                'passed' => 26,
                'failed' => 0,
                'errored' => 0,
                'skipped' => 0,
                'disabled' => 0,
                'duration' => 1.2,
                'datetime' => $ref4Date,
                'project' => 'projectone-project',
                'campaignRef' => 'p1c4',
            ),
            array(
                'passed' => 79,
                'failed' => 21,
                'errored' => 0,
                'skipped' => 0,
                'disabled' => 0,
                'duration' => 1.1,
                'datetime' => $ref1Date,
                'project' => 'projecttwo-project',
                'campaignRef' => 'p2c1',
            ),
            array(
                'passed' => 80,
                'failed' => 20,
                'errored' => 0,
                'skipped' => 0,
                'disabled' => 0,
                'duration' => 1.1,
                'datetime' => $ref2Date,
                'project' => 'projecttwo-project',
                'campaignRef' => 'p2c2',
            ),
            array(
                'passed' => 95,
                'failed' => 4,
                'errored' => 0,
                'skipped' => 1,
                'disabled' => 0,
                'duration' => 1.3,
                'datetime' => $ref3Date,
                'project' => 'projecttwo-project',
                'campaignRef' => 'p2c3',
            ),
            array(
                'passed' => 95,
                'failed' => 5,
                'errored' => 0,
                'skipped' => 0,
                'disabled' => 0,
                'duration' => 1.1,
                'datetime' => $ref1Date,
                'project' => 'projectthree-project',
                'campaignRef' => 'p3c1',
            ),
            array(
                'passed' => 79,
                'failed' => 21,
                'errored' => 0,
                'skipped' => 0,
                'disabled' => 0,
                'duration' => 1.1,
                'datetime' => $ref2Date,
                'project' => 'projectthree-project',
                'campaignRef' => 'p3c2',
            ),
            array(
                'passed' => 80,
                'failed' => 20,
                'errored' => 0,
                'skipped' => 0,
                'disabled' => 0,
                'duration' => 1.3,
                'datetime' => $ref3Date,
                'project' => 'projectthree-project',
                'campaignRef' => 'p3c3',
            ),
            array(
                'passed' => 95,
                'failed' => 0,
                'errored' => 5,
                'skipped' => 0,
                'disabled' => 0,
                'duration' => 1.1,
                'datetime' => $ref1Date,
                'project' => 'projectfour-project',
                'campaignRef' => 'p4c1',
            ),
            array(
                'passed' => 80,
                'failed' => 0,
                'errored' => 20,
                'skipped' => 0,
                'disabled' => 0,
                'duration' => 1.1,
                'datetime' => $ref2Date,
                'project' => 'projectfour-project',
                'campaignRef' => 'p4c2',
            ),
            array(
                'passed' => 79,
                'failed' => 0,
                'errored' => 21,
                'skipped' => 0,
                'disabled' => 0,
                'duration' => 1.3,
                'datetime' => $ref3Date,
                'project' => 'projectfour-project',
                'campaignRef' => 'p4c3',
            ),
            array(
                'passed' => 79,
                'failed' => 0,
                'errored' => 0,
                'skipped' => 21,
                'disabled' => 0,
                'duration' => 1.1,
                'datetime' => $ref1Date,
                'project' => 'projectfive-project',
                'campaignRef' => 'p5c1',
            ),
            array(
                'passed' => 80,
                'failed' => 0,
                'errored' => 0,
                'skipped' => 20,
                'disabled' => 0,
                'duration' => 1.1,
                'datetime' => $ref2Date,
                'project' => 'projectfive-project',
                'campaignRef' => 'p5c2',
            ),
            array(
                'passed' => 95,
                'failed' => 0,
                'errored' => 0,
                'skipped' => 5,
                'disabled' => 0,
                'duration' => 1.3,
                'datetime' => $ref3Date,
                'project' => 'projectfive-project',
                'campaignRef' => 'p5c3',
            ),
            array(
                'passed' => 50,
                'failed' => 0,
                'errored' => 0,
                'skipped' => 0,
                'disabled' => 50,
                'duration' => 1.3,
                'datetime' => $ref4Date,
                'project' => 'projectfive-project',
                'campaignRef' => 'p5c4',
            ),
        );
        $objectList = array();
        foreach ($dataArray as $i => $data) {
            $objectList[$i] = new Campaign($this->getReference($data['project']));
            $objectList[$i]->setPassed($data['passed']);
            $objectList[$i]->setFailed($data['failed']);
            $objectList[$i]->setErrored($data['errored']);
            $objectList[$i]->setSkipped($data['skipped']);
            $objectList[$i]->setDisabled($data['disabled']);
            $objectList[$i]->setDuration($data['duration']);
            $objectList[$i]->setDatetimeCampaign($data['datetime']);

            $manager->persist($objectList[$i]);
            $ref = $data['campaignRef'].'-campaign';
            $this->addReference($ref, $objectList[$i]);
        }
        $manager->flush();
    }

    /**
     * Get the order of this fixture.
     *
     * @return int
     */
    public function getOrder(): int
    {
        return 20;
    }
}
