<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Campaign;
use AppBundle\Entity\Project;
use AppBundle\Entity\Suite;
use AppBundle\Entity\Test;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SuiteController extends Controller
{
    /**
     * @Route("/project/{pid}/campaign/{crefId}/suite/{srefId}", name="suite-view")
     */
    public function indexAction($pid, $crefId, $srefId)
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
            ->findCampaignByProjectAndRefId($project, $crefId);
        if (!$campaign) {
            throw $this->createNotFoundException(
                'No campaign found for project id '.$pid.' and campaign #'.$crefId
            );
        }

        $suite = $this->getDoctrine()
            ->getRepository(Suite::class)
            ->findSuiteByCampaignAndRefId($campaign, $srefId);
        if (!$suite) {
            throw $this->createNotFoundException(
                'No suite found for campaign #'.$crefid.' and suite #'.$srefId
            );
        }

        $prevSuite = $this->getDoctrine()
            ->getRepository(Suite::class)
            ->findPrevSuiteByCampaign($campaign, $suite);
        $nextSuite = $this->getDoctrine()
            ->getRepository(Suite::class)
            ->findNextSuiteByCampaign($campaign, $suite);

        $testsList = $this->getDoctrine()
            ->getRepository(Test::class)
            ->findTestsBySuite($suite);

        return $this->render(
            'suite/view.html.twig',
            array(
                'project' => $project,
                'campaign' => $campaign,
                'suite' => $suite,
                'prevSuite' => $prevSuite,
                'nextSuite' => $nextSuite,
                'tests' => $testsList,
            )
        );
    }
}
