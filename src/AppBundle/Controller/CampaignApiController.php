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
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\Operation;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
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
     * @Operation(
     *     tags={"Campaigns"},
     *     summary="Get the list of all campaigns for a project.",
     *     @SWG\Response(
     *         response="200",
     *         description="Returned when successful array of public data of campaigns",
     *         @Model(type="AppBundle\Entity\Campaign")
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="Returned when project not found"
     *     )
     * )
     *
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
     * @Entity("campaign", expr="repository.findCampaignByProjectRefidAndRefid(prefid, crefid)")
     *
     * @Operation(
     *     tags={"Campaigns"},
     *     summary="Get campaign data.",
     *     @SWG\Response(
     *         response="200",
     *         description="Returned when successful",
     *         @Model(type="AppBundle\Entity\Campaign")
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="Returned when project or campaign not found"
     *     )
     * )
     *
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
     * @Entity("campaign", expr="repository.findLastCampaignByProjectRefid(prefid)")
     *
     * @Operation(
     *     tags={"Campaigns"},
     *     summary="Get last added campaign data.",
     *     @SWG\Response(
     *         response="200",
     *         description="Returned when successful",
     *         @Model(type="AppBundle\Entity\Campaign")
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="Returned when no campaign exists for project"
     *     )
     * )
     *
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
     * @param CampaignDTO $campaignDTO Campaign to create
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
     * @Operation(
     *     tags={"Campaigns"},
     *     summary="Create a campaign.",
     *     @SWG\Parameter(
     *         name="start",
     *         in="formData",
     *         description="Start Date time of the campaign in format (2017-07-01 12:30:01). Now by default.",
     *         required=false,
     *         type="DateTime"
     *     ),
     *     @SWG\Parameter(
     *         name="end",
     *         in="formData",
     *         description="End Date time of the campaign in format (2017-07-01 12:30:01). Null by default.",
     *         required=false,
     *         type="DateTime"
     *     ),
     *     @SWG\Response(
     *         response="201",
     *         description="Returned when created"
     *     ),
     *     @SWG\Response(
     *         response="400",
     *         description="Returned when a violation is raised by validation"
     *     ),
     *     @SWG\Response(
     *         response="401",
     *         description="Returned when X-CIR-TKN private token value is invalid"
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="Returned when project not found"
     *     )
     * )
     *
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

    /**
     * Update a campaign. Example: </br>
     * <pre style="background:black; color:white; font-size:10px;"><code style="background:black;">curl https://www.ci-report.io/api/projects/project-one/campaigns/1 -H "Content-Type: application/json" -H "X-CIR-TKN: 1f4ffb19e4b9-02278af07b7d-4e370a76f001" -X PUT --data '{"start":"2017-07-01 12:30:01", "end":"2017-07-03 12:30:01"}'
     * </code></pre>.
     *
     * @param Project     $project     Project
     * @param Campaign    $campaignDB  Campaign to update
     * @param CampaignDTO $campaignDTO Object containing input data
     * @param Request     $request     The request
     *
     * @return Campaign|View
     *
     * @Rest\Put(
     *    "/projects/{prefid}/campaigns/{crefid}",
     *    requirements={"crefid" = "\d+"}
     * )
     * @Rest\View(serializerGroups={"public"})
     *
     * @ParamConverter("project", options={"mapping": {"prefid": "refid"}})
     * @Entity("campaignDB", expr="repository.findCampaignByProjectRefidAndRefid(prefid, crefid)")
     * @ParamConverter("campaignDTO", converter="fos_rest.request_body")
     *
     * @Operation(
     *     tags={"Campaigns"},
     *     summary="Update a campaign.",
     *     @SWG\Parameter(
     *         name="start",
     *         in="body",
     *         description="Start Date time of the campaign in format (2017-07-01 12:30:01). Now by default.",
     *         required=false,
     *         type="DateTime",
     *         schema=""
     *     ),
     *     @SWG\Parameter(
     *         name="end",
     *         in="body",
     *         description="End Date time of the campaign in format (2017-07-01 12:30:01). Null by default.",
     *         required=false,
     *         type="DateTime",
     *         schema=""
     *     ),
     *     @SWG\Response(
     *         response="200",
     *         description="Returned when successful",
     *         @Model(type="AppBundle\Entity\Campaign")
     *     ),
     *     @SWG\Response(
     *         response="400",
     *         description="Returned when a violation is raised by validation"
     *     ),
     *     @SWG\Response(
     *         response="401",
     *         description="Returned when X-CIR-TKN private token value is invalid"
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="Returned when campaign not found"
     *     ),
     *     @SWG\Response(
     *         response="405",
     *         description="Returned when campaign refid is not set in URL"
     *     )
     * )
     *
     */
    public function putCampaignAction(Project $project, Campaign $campaignDB, CampaignDTO $campaignDTO, Request $request)
    {
        if ($this->isInvalidToken($request, $project->getToken())) {
            return $this->getInvalidTokenView();
        }

        $validator = $this->get('validator');
        $violationsDTO = $validator->validate($campaignDTO);

        if (count($violationsDTO) > 0) {
            return $this->view($violationsDTO, Response::HTTP_BAD_REQUEST);
        }
        $campaignDB->setFromDTO($campaignDTO);

        $violations = $validator->validate($campaignDB);
        if (count($violations) > 0) {
            return $this->view($violations, Response::HTTP_BAD_REQUEST);
        }
        $this->getDoctrine()->getManager()->flush();

        return $campaignDB;
    }

    /**
     * Delete a campaign. Example: </br>
     * <pre style="background:black; color:white; font-size:10px;"><code style="background:black;">curl https://www.ci-report.io/api/projects/project-one/campaigns/1 -H "X-CIR-TKN: 1f4ffb19e4b9-02278af07b7d-4e370a76f001" -X DELETE
     * </code></pre>.
     *
     * @param Project  $project  Project
     * @param Campaign $campaign Campaign to delete
     * @param Request  $request  The request
     *
     * @return void|View
     *
     * @Rest\Delete(
     *    "/projects/{prefid}/campaigns/{crefid}",
     *    requirements={"crefid" = "\d+"}
     * )
     * @Rest\View(statusCode=Response::HTTP_NO_CONTENT)
     *
     * @ParamConverter("project", options={"mapping": {"prefid": "refid"}})
     * @Entity("campaign", expr="repository.findCampaignByProjectRefidAndRefid(prefid, crefid)")
     *
     * @Operation(
     *     tags={"Campaigns"},
     *     summary="Delete a campaign.",
     *     @SWG\Response(
     *         response="204",
     *         description="Returned when successful"
     *     ),
     *     @SWG\Response(
     *         response="401",
     *         description="Returned when X-CIR-TKN private token value is invalid"
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="Returned when campaign not found"
     *     ),
     *     @SWG\Response(
     *         response="405",
     *         description="Returned when campaign refid is not set in URL"
     *     )
     * )
     *
     */
    public function deleteCampaignAction(Project $project, Campaign $campaign, Request $request)
    {
        if ($this->isInvalidToken($request, $project->getToken())) {
            return $this->getInvalidTokenView();
        }
        $em = $this->getDoctrine()->getManager();
        $em->remove($campaign);
        $em->flush();
    }
}
