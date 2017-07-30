<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Project;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class Projects extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * Load data fixtures with the passed EntityManager.
     *
     * @param ObjectManager $manager The entity manager
     */
    public function load(ObjectManager $manager)
    {
        $dataArray = array(
            array(
                'name' => 'ProjectOne',
            ),
            array(
                'name' => 'ProjectTwo',
            ),
            array(
                'name' => 'ProjectThree',
            ),
            array(
                'name' => 'ProjectFour',
            ),
            array(
                'name' => 'ProjectFive',
            ),
            array(
                'name' => 'ProjectSix',
            ),
        );
        $objectList = array();
        foreach ($dataArray as $i => $data) {
            $objectList[$i] = new Project();
            $objectList[$i]->setName($data['name']);

            $manager->persist($objectList[$i]);
            $ref = strtolower($data['name']).'-project';
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
        return 10;
    }
}
