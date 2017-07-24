<?php

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
