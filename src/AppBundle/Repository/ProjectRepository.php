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

use Doctrine\ORM\EntityRepository;

/**
 * Project repository class.
 *
 * @category  ci-report app
 *
 * @author    Francois-Xavier Soubirou <soubirou@yahoo.fr>
 * @copyright 2017 Francois-Xavier Soubirou
 * @license   http://www.gnu.org/licenses/   GPLv3
 *
 * @see      https://ci-report.io
 */
class ProjectRepository extends EntityRepository
{
    /**
     * Check if refid already exists.
     *
     * @param string $refid RefId to test
     *
     * @return bool
     */
    public function refidExists(string $refid): bool
    {
        $qb = $this->createQueryBuilder('p')
            ->select('count(p.id)')
            ->where('p.refid = :refid')
            ->setParameter('refid', $refid);

        $result = $qb->getQuery()->getSingleScalarResult();

        if ($result > 0) {
            return true;
        }

        return false;
    }
}
