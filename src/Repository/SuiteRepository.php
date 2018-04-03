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

namespace App\Repository;

use App\Entity\Campaign;
use App\Entity\Suite;
use Gedmo\Sortable\Entity\Repository\SortableRepository;

/**
 * Suite repository class.
 *
 * @category  ci-report app
 *
 * @author    Francois-Xavier Soubirou <soubirou@yahoo.fr>
 * @copyright 2017 Francois-Xavier Soubirou
 * @license   http://www.gnu.org/licenses/   GPLv3
 *
 * @see      https://www.ci-report.io
 */
class SuiteRepository extends SortableRepository
{
    /**
     * Get all suites for a campaign.
     *
     * @param Campaign $campaign The campaign
     *
     * @return array
     */
    public function findSuitesByCampaign(Campaign $campaign): array
    {
        $qb = $this->createQueryBuilder('s')
            ->innerJoin('s.campaign', 'c')
            ->where('c.id = :campaignId')
            ->setParameter('campaignId', $campaign->getId())
            ->orderBy('s.datetime', 'DESC', 's.position', 'DESC');

        $result = $qb->getQuery()->getResult();

        return $result;
    }

    /**
     * Get a suite for a campaign refid and its refid.
     *
     * @param string $prefid The project refid
     * @param int    $crefid The refid of the campaign
     * @param int    $srefid Refid of the suite in the campaign
     *
     * @return Suite|null
     */
    public function findSuiteByProjectRefidCampaignRefidAndRefid(string $prefid, int $crefid, int $srefid): ?Suite
    {
        $cposition = $crefid - 1;
        $position = $srefid - 1;

        $qb = $this->createQueryBuilder('s')
            ->innerJoin('s.campaign', 'c')
            ->innerJoin('c.project', 'p')
            ->where('p.refid = :prefid')
            ->andWhere('c.position = :cposition')
            ->andWhere('s.position = :position')
            ->setParameter('prefid', $prefid)
            ->setParameter('cposition', $cposition)
            ->setParameter('position', $position);

        $result = $qb->getQuery()->getOneOrNullResult();

        return $result;
    }

    /**
     * Get previous suite for a suite.
     *
     * @param Campaign $campaign The campaign
     * @param Suite    $suite    The suite
     *
     * @return Suite|null
     */
    public function findPrevSuiteByCampaign(Campaign $campaign, Suite $suite): ?Suite
    {
        $qb = $this->createQueryBuilder('s')
            ->innerJoin('s.campaign', 'c')
            ->where('c.id = :campaignId')
            ->andWhere('s.position < :position')
            ->setParameter('campaignId', $campaign->getId())
            ->setParameter('position', $suite->getPosition())
            ->orderBy('s.position', 'DESC')
            ->setMaxResults(1);

        $result = $qb->getQuery()->getOneOrNullResult();

        return $result;
    }

    /**
     * Get next campaign for a campaign.
     *
     * @param Campaign $campaign The campaign
     * @param Suite    $suite    The suite
     *
     * @return Suite|null
     */
    public function findNextSuiteByCampaign(Campaign $campaign, Suite $suite): ?Suite
    {
        $qb = $this->createQueryBuilder('s')
            ->innerJoin('s.campaign', 'c')
            ->where('c.id = :campaignId')
            ->andWhere('s.position > :position')
            ->setParameter('campaignId', $campaign->getId())
            ->setParameter('position', $suite->getPosition())
            ->orderBy('s.position', 'ASC')
            ->setMaxResults(1);

        $result = $qb->getQuery()->getOneOrNullResult();

        return $result;
    }
}
