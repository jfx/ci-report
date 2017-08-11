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

namespace AppBundle\Controller;

use AppBundle\Entity\Campaign;
use AppBundle\Entity\Project;
use AppBundle\Entity\Suite;
use AppBundle\Entity\Test;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * Suite controller class.
 *
 * @category  ci-report app
 *
 * @author    Francois-Xavier Soubirou <soubirou@yahoo.fr>
 * @copyright 2017 Francois-Xavier Soubirou
 * @license   http://www.gnu.org/licenses/   GPLv3
 *
 * @see      https://ci-report.io
 */
class SuiteController extends Controller
{
    /**
     * Index action.
     *
     * @param int $pid    Id of project
     * @param int $crefId Reference Id of campaign
     * @param int $srefId Reference Id of suite
     *
     * @return Response A Response instance
     *
     * @Route("/project/{pid}/campaign/{crefId}/suite/{srefId}", name="suite-view")
     */
    public function indexAction(int $pid, int $crefId, int $srefId): Response
    {
        $project = $this->getDoctrine()
            ->getRepository(Project::class)
            ->find($pid);
        if (!$project) {
            throw $this->createNotFoundException(
                sprintf('No project found for id #%d', $pid)
            );
        }

        $campaign = $this->getDoctrine()
            ->getRepository(Campaign::class)
            ->findCampaignByProjectAndRefId($project, $crefId);
        if (!$campaign) {
            throw $this->createNotFoundException(
                sprintf(
                    'No campaign found for project id #%d and campaign #%d',
                    $pid,
                    $crefId
                )
            );
        }

        $suite = $this->getDoctrine()
            ->getRepository(Suite::class)
            ->findSuiteByCampaignAndRefId($campaign, $srefId);
        if (!$suite) {
            throw $this->createNotFoundException(
                sprintf(
                    'No suite found for campaign #%d and suite #%d',
                    $crefId,
                    $srefId
                )
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
