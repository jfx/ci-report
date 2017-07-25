<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Campaign;
use AppBundle\Entity\Project;
use Gedmo\Sortable\Entity\Repository\SortableRepository;

/**
 * CampaignRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CampaignRepository extends SortableRepository
{
    /**
     * Get all campaigns for a project.
     *
     * @param Project $project The project.
     *
     * @return Array List of campaigns.
     */
    public function findCampaignsByProject(Project $project)
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
     * Get a campaign for a project and its refId.
     *
     * @param Project $project The project.
     * @param int     $refId   RefId of the campaign in the project.
     *
     * @return Array List of campaigns.
     */
    public function findCampaignByProjectAndRefId(Project $project, $refId)
    {
        $position = $refId - 1;

        $qb = $this->createQueryBuilder('c')
            ->innerJoin('c.project', 'p')
            ->where('p.id = :projectId')
            ->andWhere('c.position = :position')
            ->setParameter('projectId', $project->getId())
            ->setParameter('position', $position);

        $result = $qb->getQuery()->getOneOrNullResult();

        return $result;
    }

    /**
     * Get previous campaign for a campaign.
     *
     * @param Project   $project  The project.
     * @param Campaign  $campaign The campaign.
     *
     * @return Campaign.
     */
    public function findPrevCampaignByProject(Project $project, Campaign $campaign)
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
     * @param Project   $project  The project.
     * @param Campaign  $campaign The campaign.
     *
     * @return Campaign.
     */
    public function findNextCampaignByProject(Project $project, Campaign $campaign)
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
