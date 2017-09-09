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

namespace AppBundle\Repository;

use AppBundle\Entity\Suite;
use Gedmo\Sortable\Entity\Repository\SortableRepository;

/**
 * Test repository class.
 *
 * @category  ci-report app
 *
 * @author    Francois-Xavier Soubirou <soubirou@yahoo.fr>
 * @copyright 2017 Francois-Xavier Soubirou
 * @license   http://www.gnu.org/licenses/   GPLv3
 *
 * @see      https://www.ci-report.io
 */
class TestRepository extends SortableRepository
{
    /**
     * Get all tests for a suite.
     *
     * @param Suite $suite The suite
     *
     * @return array
     */
    public function findTestsBySuite(Suite $suite): array
    {
        $qb = $this->createQueryBuilder('t')
            ->innerJoin('t.suite', 's')
            ->where('s.id = :suiteId')
            ->setParameter('suiteId', $suite->getId())
            ->orderBy('t.package', 'ASC', 't.className', 'ASC', 't.name', 'ASC', 't.position', 'ASC');

        $result = $qb->getQuery()->getResult();

        return $result;
    }
}
