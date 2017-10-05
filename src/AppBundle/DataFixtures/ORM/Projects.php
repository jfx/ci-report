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

use AppBundle\Entity\Project;
use AppBundle\Service\UtilService;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Project fixtures load class.
 *
 * @category  ci-report app
 *
 * @author    Francois-Xavier Soubirou <soubirou@yahoo.fr>
 * @copyright 2017 Francois-Xavier Soubirou
 * @license   http://www.gnu.org/licenses/   GPLv3
 *
 * @see      https://www.ci-report.io
 */
class Projects extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * Load data fixtures with the passed EntityManager.
     *
     * @param ObjectManager $manager The entity manager
     */
    public function load(ObjectManager $manager)
    {
        $utilService = new UtilService();

        $dataArray = array(
            array(
                'name' => 'Project One',
                'token' => '1f4ffb19e4b9-02278af07b7d-4e370a76f001',
                'email' => 'email1@example.com',
            ),
            array(
                'name' => 'Project Two',
                'token' => '1f4ffb19e4b9-02278af07b7d-4e370a76f002',
                'email' => 'email2@example.com',
            ),
            array(
                'name' => 'Project Three',
                'token' => '1f4ffb19e4b9-02278af07b7d-4e370a76f003',
                'email' => 'email3@example.com',
            ),
            array(
                'name' => 'Project Four',
                'token' => '1f4ffb19e4b9-02278af07b7d-4e370a76f004',
                'email' => 'email4@example.com',
            ),
            array(
                'name' => 'Project Five',
                'token' => '1f4ffb19e4b9-02278af07b7d-4e370a76f005',
                'email' => 'email5@example.com',
            ),
            array(
                'name' => 'Project Six',
                'token' => '1f4ffb19e4b9-02278af07b7d-4e370a76f006',
                'email' => 'email6@example.com',
            ),
            array(
                'name' => 'Project Seven',
                'token' => '1f4ffb19e4b9-02278af07b7d-4e370a76f007',
                'email' => 'email7@example.com',
            ),
            array(
                'name' => 'Project Eight',
                'token' => '1f4ffb19e4b9-02278af07b7d-4e370a76f008',
                'email' => 'email8@example.com',
            ),
        );
        $objectList = array();
        foreach ($dataArray as $i => $data) {
            $objectList[$i] = new Project();
            $objectList[$i]->setName($data['name']);
            $objectList[$i]->setRefid($utilService->toAscii($data['name']));
            $objectList[$i]->setToken($data['token']);
            $objectList[$i]->setEmail($data['email']);

            $manager->persist($objectList[$i]);
            $ref = strtolower(str_replace(' ', '', $data['name'])).'-project';
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
        return 10;
    }
}
