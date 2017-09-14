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

use AppBundle\Entity\Campaign;
use AppBundle\Entity\Project;
use Gedmo\Sortable\Entity\Repository\SortableRepository;

/**
 * Campaign repository class.
 *
 * @category  ci-report app
 *
 * @author    Francois-Xavier Soubirou <soubirou@yahoo.fr>
 * @copyright 2017 Francois-Xavier Soubirou
 * @license   http://www.gnu.org/licenses/   GPLv3
 *
 * @see      https://www.ci-report.io
 */
class CampaignRepository extends SortableRepository
{
    /**
     * Get all campaigns for a project.
     *
     * @param Project $project The project.
     *
     * @return array List of campaigns
     */
    public function findCampaignsByProject(Project $project): array
    {
        $qb = $this->createQueryBuilder('c')
            ->innerJoin('c.project', 'p')
            ->where('p.id = :projectId')
            ->setParameter('projectId', $project->getId())
            ->orderBy('c.position', 'DESC');

        $result = $qb->getQuery()->getResult();

        return $result;
    }

    /**
     * Get a campaign for a project refid and its refid.
     *
     * @param string $prefid The project refid
     * @param int    $refid  Refid of the campaign of the project
     *
     * @return Campaign|null
     */
    public function findCampaignByProjectRefidAndRefid(string $prefid, int $refid): ?Campaign
    {
        $position = $refid - 1;

        $qb = $this->createQueryBuilder('c')
            ->innerJoin('c.project', 'p')
            ->where('p.refid = :prefid')
            ->andWhere('c.position = :position')
            ->setParameter('prefid', $prefid)
            ->setParameter('position', $position);

        $result = $qb->getQuery()->getOneOrNullResult();

        return $result;
    }

    /**
     * Get previous campaign for a campaign.
     *
     * @param Project  $project  The project
     * @param Campaign $campaign The campaign
     *
     * @return Campaign|null
     */
    public function findPrevCampaignByProject(Project $project, Campaign $campaign): ?Campaign
    {
        $qb = $this->createQueryBuilder('c')
            ->innerJoin('c.project', 'p')
            ->where('p.id = :projectId')
            ->andWhere('c.position < :position')
            ->setParameter('projectId', $project->getId())
            ->setParameter('position', $campaign->getPosition())
            ->orderBy('c.position', 'DESC')
            ->setMaxResults(1);

        $result = $qb->getQuery()->getOneOrNullResult();

        return $result;
    }

    /**
     * Get next campaign for a campaign.
     *
     * @param Project  $project  The project
     * @param Campaign $campaign The campaign
     *
     * @return Campaign|null
     */
    public function findNextCampaignByProject(Project $project, Campaign $campaign): ?Campaign
    {
        $qb = $this->createQueryBuilder('c')
            ->innerJoin('c.project', 'p')
            ->where('p.id = :projectId')
            ->andWhere('c.position > :position')
            ->setParameter('projectId', $project->getId())
            ->setParameter('position', $campaign->getPosition())
            ->orderBy('c.position', 'ASC')
            ->setMaxResults(1);

        $result = $qb->getQuery()->getOneOrNullResult();

        return $result;
    }
}
