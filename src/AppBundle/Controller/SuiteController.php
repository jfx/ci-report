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
                'No suite found for campaign #'.$crefId.' and suite #'.$srefId
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
