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

namespace App\Controller;

use App\Entity\Campaign;
use App\Entity\Project;
use App\Entity\Suite;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Campaign controller class.
 *
 * @category  ci-report app
 *
 * @author    Francois-Xavier Soubirou <soubirou@yahoo.fr>
 * @copyright 2017 Francois-Xavier Soubirou
 * @license   http://www.gnu.org/licenses/   GPLv3
 *
 * @see      https://www.ci-report.io
 */
class CampaignController extends Controller
{
    /**
     * Index action.
     *
     * @param Project  $project  Project
     * @param Campaign $campaign Campaign to display
     *
     * @return Response A Response instance
     *
     * @Route(
     *    "/project/{prefid}/campaign/{crefid}",
     *    requirements={"crefid" = "\d+"},
     *    name="campaign-view"
     * )
     *
     * @ParamConverter("project", options={"mapping": {"prefid": "refid"}})
     * @Entity("campaign", expr="repository.findCampaignByProjectRefidAndRefid(prefid, crefid)")
     */
    public function indexAction(Project $project, Campaign $campaign): Response
    {
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
