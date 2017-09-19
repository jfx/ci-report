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
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation as Doc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * Campaign API controller class.
 *
 * @category  ci-report app
 *
 * @author    Francois-Xavier Soubirou <soubirou@yahoo.fr>
 * @copyright 2017 Francois-Xavier Soubirou
 * @license   http://www.gnu.org/licenses/   GPLv3
 *
 * @see      https://www.ci-report.io
 *
 * @Rest\Route("/api")
 */
class CampaignApiController extends AbstractApiController
{
    /**
     * Get list of campaigns for a project. Example: </br>
     * <pre style="background:black; color:white; font-size:10px;"><code style="background:black;">curl https://www.ci-report.io/api/projects/project-one/campaigns -X GET
     * </code></pre>.
     *
     * @param Project $project Project
     *
     * @return array
     *
     * @Rest\Get("/projects/{prefid}/campaigns")
     * @Rest\View(serializerGroups={"public"})
     *
     * @ParamConverter("project", options={"mapping": {"prefid": "refid"}})
     *
     * @Doc\ApiDoc(
     *     section="Campaigns",
     *     description="Get the list of all campaigns for a project.",
     *     requirements={
     *         {
     *             "name"="prefid",
     *             "dataType"="string",
     *             "requirement"="string",
     *             "description"="Unique short name of project defined on project creation."
     *         }
     *     },
     *     output={
     *         "class"=Campaign::class,
     *         "groups"={"public"},
     *         "parsers"={"Nelmio\ApiDocBundle\Parser\JmsMetadataParser"}
     *     },
     *     statusCodes={
     *         200="Returned when successful array of public data of campaigns",
     *         404="Returned when project not found"
     *     }
     * )
     */
    public function getCampaignsAction(Project $project): array
    {
        $campaigns = $this->getDoctrine()
            ->getRepository(Campaign::class)
            ->findCampaignsByProject($project);

        return $campaigns;
    }

    /**
     * Get campaign data. Example: </br>
     * <pre style="background:black; color:white; font-size:10px;"><code style="background:black;">curl https://www.ci-report.io/api/projects/project-one/campaigns/1 -X GET
     * </code></pre>.
     *
     * @param Campaign $campaign Campaign
     *
     * @return Campaign
     *
     * @Rest\Get("/projects/{prefid}/campaigns/{crefid}")
     * @Rest\View(serializerGroups={"public"})
     *
     * @ParamConverter("campaign", class="AppBundle:Campaign", options={
     *    "repository_method" = "findCampaignByProjectRefidAndRefid",
     *    "mapping": {"prefid": "prefid", "crefid": "crefid"},
     *    "map_method_signature" = true
     * })
     *
     * @Doc\ApiDoc(
     *     section="Campaigns",
     *     description="Get campaign data.",
     *     requirements={
     *         {
     *             "name"="prefid",
     *             "dataType"="string",
     *             "requirement"="string",
     *             "description"="Unique short name of project defined on project creation."
     *         },
     *         {
     *             "name"="crefid",
     *             "dataType"="int",
     *             "requirement"="int",
     *             "description"="Reference id of the campaign."
     *         }
     *     },
     *     output= {
     *         "class"=Campaign::class,
     *         "groups"={"public"},
     *         "parsers"={"Nelmio\ApiDocBundle\Parser\JmsMetadataParser"}
     *     },
     *     statusCodes={
     *         200="Returned when successful",
     *         404="Returned when project not found"
     *     }
     * )
     */
    public function getProjectAction(Campaign $campaign): Campaign
    {
        return $campaign;
    }
}
