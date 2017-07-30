<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Campaign;
use AppBundle\Entity\Project;
use AppBundle\Entity\Suite;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CampaignController extends Controller
{
    /**
     * @Route("/project/{pid}/campaign/{refId}", name="campaign-view")
     */
    public function indexAction($pid, $refId)
    {
        $project = $this->getDoctrine()
            ->getRepository(Project::class)
            ->find($pid);
        if (!$project) {
            throw $this->createNotFoundException(
                'No project found for id '.$pid
            );
        }

        $campaign = $this->getDoctrine()
            ->getRepository(Campaign::class)
            ->findCampaignByProjectAndRefId($project, $refId);
        if (!$campaign) {
            throw $this->createNotFoundException(
                'No campaign found for project id '.$pid.' and campaign #'.$refId
            );
        }

        $prevCampaign = $this->getDoctrine()
            ->getRepository(Campaign::class)
            ->findPrevCampaignByProject($project, $campaign);
        $nextCampaign = $this->getDoctrine()
            ->getRepository(Campaign::class)
            ->findNextCampaignByProject($project, $campaign);

        $suitesList = $this->getDoctrine()
            ->getRepository(Suite::class)
            ->findSuitesByCampaign($campaign);

        return $this->render(
            'campaign/view.html.twig',
            array(
                'project' => $project,
                'campaign' => $campaign,
                'prevCampaign' => $prevCampaign,
                'nextCampaign' => $nextCampaign,
                'suites' => $suitesList,
            )
        );
    }
}
