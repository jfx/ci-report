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

use AppBundle\DTO\CampaignDTO;
use AppBundle\Entity\Campaign;
use AppBundle\Entity\Project;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation as Doc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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
     * @Rest\Get(
     *    "/projects/{prefid}/campaigns/{crefid}",
     *    requirements={"crefid" = "\d+"}
     * )
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
     *         404="Returned when project or campaign not found"
     *     }
     * )
     */
    public function getCampaignAction(Campaign $campaign): Campaign
    {
        return $campaign;
    }

    /**
     * Get last added campaign data. Example: </br>
     * <pre style="background:black; color:white; font-size:10px;"><code style="background:black;">curl https://www.ci-report.io/api/projects/project-one/campaigns/last -X GET
     * </code></pre>.
     *
     * @param Campaign $campaign Campaign
     *
     * @return Campaign
     *
     * @Rest\Get("/projects/{prefid}/campaigns/last")
     * @Rest\View(serializerGroups={"public"})
     *
     * @ParamConverter("campaign", class="AppBundle:Campaign", options={
     *    "repository_method" = "findLastCampaignByProjectRefid",
     *    "mapping": {"prefid": "prefid"},
     *    "map_method_signature" = true
     * })
     *
     * @Doc\ApiDoc(
     *     section="Campaigns",
     *     description="Get last added campaign data.",
     *     requirements={
     *         {
     *             "name"="prefid",
     *             "dataType"="string",
     *             "requirement"="string",
     *             "description"="Unique short name of project defined on project creation."
     *         }
     *     },
     *     output= {
     *         "class"=Campaign::class,
     *         "groups"={"public"},
     *         "parsers"={"Nelmio\ApiDocBundle\Parser\JmsMetadataParser"}
     *     },
     *     statusCodes={
     *         200="Returned when successful",
     *         404="Returned when no campaign exists for project"
     *     }
     * )
     */
    public function getLastCampaignAction(Campaign $campaign): Campaign
    {
        return $campaign;
    }

    /**
     * Create a campaign. Example: </br>
     * <pre style="background:black; color:white; font-size:10px;"><code style="background:black;">curl https://www.ci-report.io/api/projects/project-one/campaigns -H "Content-Type: application/json" -H "X-CIR-TKN: 1f4ffb19e4b9-02278af07b7d-4e370a76f001" -X POST --data '{"warning":80, "success":95, "start":"2017-07-01 12:30:01", "end":"2017-07-03 12:30:01"}'
     * </code></pre>.
     *
     * @param Project     $project     Project
     * @param CampaignDTO $campaignDTO Project to create
     * @param Request     $request     The request
     *
     * @return Campaign|View
     *
     * @Rest\Post("/projects/{prefid}/campaigns")
     * @Rest\View(statusCode=Response::HTTP_CREATED, serializerGroups={"public"})
     *
     * @ParamConverter("project", options={"mapping": {"prefid": "refid"}})
     * @ParamConverter("campaignDTO", converter="fos_rest.request_body")
     *
     * @Doc\ApiDoc(
     *     section="Campaigns",
     *     description="Create a campaign.",
     *     headers={
     *         {
     *             "name"="Content-Type",
     *             "required"=true,
     *             "description"="Type of content: application/json"
     *         },
     *         {
     *             "name"="X-CIR-TKN",
     *             "required"=true,
     *             "description"="Private token"
     *         }
     *     },
     *     requirements={
     *         {
     *             "name"="prefid",
     *             "dataType"="string",
     *             "requirement"="string",
     *             "description"="Unique short name of project defined on project creation."
     *         }
     *     },
     *     input= { "class"=CampaignDTO::class },
     *     output= {
     *         "class"=Campaign::class,
     *         "groups"={"public"},
     *         "parsers"={"Nelmio\ApiDocBundle\Parser\JmsMetadataParser"}
     *     },
     *     statusCodes={
     *         201="Returned when created",
     *         400="Returned when a violation is raised by validation",
     *         401="Returned when X-CIR-TKN private token value is invalid",
     *         404="Returned when project not found"
     *     },
     *     tags={
     *         "token" = "#2c3e50"
     *     }
     * )
     */
    public function postCampaignAction(Project $project, CampaignDTO $campaignDTO, Request $request)
    {
        if ($this->isInvalidToken($request, $project->getToken())) {
            return $this->getInvalidTokenView();
        }

        $validator = $this->get('validator');
        $violationsDTO = $validator->validate($campaignDTO);

        if (count($violationsDTO) > 0) {
            return $this->view($violationsDTO, Response::HTTP_BAD_REQUEST);
        }
        $campaign = new Campaign($project);
        $campaign->setFromDTO($campaignDTO);

        $violations = $validator->validate($campaign);
        if (count($violations) > 0) {
            return $this->view($violations, Response::HTTP_BAD_REQUEST);
        }
        $em = $this->getDoctrine()->getManager();
        $em->persist($campaign);
        $em->flush();

        return $campaign;
    }
}
