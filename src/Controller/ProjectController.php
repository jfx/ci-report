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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Project controller class.
 *
 * @category  ci-report app
 *
 * @author    Francois-Xavier Soubirou <soubirou@yahoo.fr>
 * @copyright 2017 Francois-Xavier Soubirou
 * @license   http://www.gnu.org/licenses/   GPLv3
 *
 * @see      https://www.ci-report.io
 */
class ProjectController extends Controller
{
    /**
     * Index action.
     *
     * @param Project $project The project
     *
     * @return Response A Response instance
     *
     * @Route("/project/{prefid}", name="project-view")
     *
     * @ParamConverter("project", options={"mapping": {"prefid": "refid"}})
     */
    public function indexAction(Project $project): Response
    {
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
