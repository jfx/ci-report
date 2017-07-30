<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Campaign;
use AppBundle\Entity\Project;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ProjectController extends Controller
{
    /**
     * @Route("/project/{pid}", name="project-view")
     */
    public function indexAction($pid)
    {
        $project = $this->getDoctrine()
            ->getRepository(Project::class)
            ->find($pid);

        if (!$project) {
            throw $this->createNotFoundException(
                'No project found for id '.$pid
            );
        }

        $campaignsList = $this->getDoctrine()
            ->getRepository(Campaign::class)
            ->findCampaignsByProject($project);

        if (count($campaignsList) > 0) {
            $lastCampaign = $campaignsList[0];
        } else {
            $lastCampaign = null;
        }

        return $this->render(
            'project/view.html.twig',
            array(
                'project' => $project,
                'lastCampaign' => $lastCampaign,
                'campaigns' => $campaignsList,
            )
        );
    }
}
