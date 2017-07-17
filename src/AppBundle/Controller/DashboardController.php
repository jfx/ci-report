<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Campaign;
use AppBundle\Entity\Project;
// use AppBundle\Repository\CampaignRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DashboardController extends Controller
{
    /**
     * @Route("/project/{pid}", name="dashboard")
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

        $campaignsList =  $this->getDoctrine()
            ->getRepository(Campaign::class)
            ->findCampaignsByProject($project);

        if (count($campaignsList) > 0) {
            $lastCampaign = $campaignsList[0];
        } else {
            $lastCampaign = null;
        }

        return $this->render(
            'dashboard/index.html.twig',
            [
                'project' => $project,
                'lastCampaign' => $lastCampaign,
                'campaigns' => $campaignsList
            ]
        );
    }
}
