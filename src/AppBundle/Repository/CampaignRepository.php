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
            ->orderBy('c.datetimeCampaign', 'DESC', 'c.position', 'DESC');

        $result = $qb->getQuery()->getResult();

        return $result;
    }
}
