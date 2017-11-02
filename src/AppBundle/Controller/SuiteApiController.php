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

use AppBundle\DTO\SuiteDTO;
use AppBundle\DTO\SuiteLimitsDTO;
use AppBundle\Entity\Campaign;
use AppBundle\Entity\Project;
use AppBundle\Entity\Suite;
use AppBundle\Service\RefreshService;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation as Doc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Suite API controller class.
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
class SuiteApiController extends AbstractApiController
{
    /**
     * Get list of suites for a project and a campaign. Example: </br>
     * <pre style="background:black; color:white; font-size:10px;"><code style="background:black;">curl https://www.ci-report.io/api/projects/project-one/campaigns/1/suites -X GET
     * </code></pre>.
     *
     * @param Campaign $campaign Campaign
     *
     * @return array
     *
     * @Rest\Get("/projects/{prefid}/campaigns/{crefid}/suites")
     * @Rest\View(serializerGroups={"public"})
     *
     * @ParamConverter("campaign", class="AppBundle:Campaign", options={
     *    "repository_method" = "findCampaignByProjectRefidAndRefid",
     *    "mapping": {"prefid": "prefid", "crefid": "crefid"},
     *    "map_method_signature" = true
     * })
     *
     * @Doc\ApiDoc(
     *     section="Suites",
     *     description="Get the list of all suites for a campaign.",
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
     *     output={
     *         "class"=Suite::class,
     *         "groups"={"public"},
     *         "parsers"={"Nelmio\ApiDocBundle\Parser\JmsMetadataParser"}
     *     },
     *     statusCodes={
     *         200="Returned when successful array of public data of campaigns",
     *         404="Returned when project not found"
     *     }
     * )
     */
    public function getSuitesAction(Campaign $campaign): array
    {
        $suites = $this->getDoctrine()
            ->getRepository(Suite::class)
            ->findSuitesByCampaign($campaign);

        return $suites;
    }

    /**
     * Get suite data. Example: </br>
     * <pre style="background:black; color:white; font-size:10px;"><code style="background:black;">curl https://www.ci-report.io/api/projects/project-one/campaigns/1/suites/1 -X GET
     * </code></pre>.
     *
     * @param Suite $suite Suite
     *
     * @return Suite
     *
     * @Rest\Get(
     *    "/projects/{prefid}/campaigns/{crefid}/suites/{srefid}",
     *    requirements={"crefid" = "\d+", "srefid" = "\d+"}
     * )
     * @Rest\View(serializerGroups={"public"})
     *
     * @ParamConverter("suite", class="AppBundle:Suite", options={
     *    "repository_method" = "findSuiteByProjectRefidCampaignRefidAndRefid",
     *    "mapping": {"prefid": "prefid", "crefid": "crefid", "srefid": "srefid"},
     *    "map_method_signature" = true
     * })
     *
     * @Doc\ApiDoc(
     *     section="Suites",
     *     description="Get suite data.",
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
     *         },
     *         {
     *             "name"="srefid",
     *             "dataType"="int",
     *             "requirement"="int",
     *             "description"="Reference id of the suite."
     *         }
     *     },
     *     output= {
     *         "class"=Suite::class,
     *         "groups"={"public"},
     *         "parsers"={"Nelmio\ApiDocBundle\Parser\JmsMetadataParser"}
     *     },
     *     statusCodes={
     *         200="Returned when successful",
     *         404="Returned when project or campaign not found"
     *     }
     * )
     */
    public function getSuiteAction(Suite $suite): Suite
    {
        return $suite;
    }

    /**
     * Create a suite. Example: </br>
     * <pre style="background:black; color:white; font-size:10px;"><code style="background:black;">curl https://www.ci-report.io/api/projects/project-one/campaigns -H "Content-Type: application/json" -H "X-CIR-TKN: 1f4ffb19e4b9-02278af07b7d-4e370a76f001" -X POST --data '{"warning":80, "success":95, "start":"2017-07-01 12:30:01", "end":"2017-07-03 12:30:01"}'
     * </code></pre>.
     *
     * @param Project  $project  Project
     * @param Campaign $campaign Campaign
     * @param SuiteDTO $suiteDTO Suite to create
     * @param Request  $request  The request
     *
     * @return Suite|View
     *
     * @Rest\Post("/projects/{prefid}/campaigns/{crefid}/suites")
     * @Rest\View(statusCode=Response::HTTP_CREATED, serializerGroups={"public"})
     *
     * @ParamConverter("project", options={"mapping": {"prefid": "refid"}})
     * @ParamConverter("campaign", class="AppBundle:Campaign", options={
     *    "repository_method" = "findCampaignByProjectRefidAndRefid",
     *    "mapping": {"prefid": "prefid", "crefid": "crefid"},
     *    "map_method_signature" = true
     * })
     * @ParamConverter("suiteDTO", converter="fos_rest.request_body")
     *
     * @Doc\ApiDoc(
     *     section="Suites",
     *     description="Create a suite.",
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
     *         },
     *         {
     *             "name"="crefid",
     *             "dataType"="int",
     *             "requirement"="int",
     *             "description"="Reference id of the campaign."
     *         }
     *     },
     *     input= { "class"=SuiteDTO::class },
     *     output= {
     *         "class"=Suite::class,
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
    public function postSuiteAction(Project $project, Campaign $campaign, SuiteDTO $suiteDTO, Request $request)
    {
        if ($this->isInvalidToken($request, $project->getToken())) {
            return $this->getInvalidTokenView();
        }

        $validator = $this->get('validator');
        $violationsDTO = $validator->validate($suiteDTO);

        if (count($violationsDTO) > 0) {
            return $this->view($violationsDTO, Response::HTTP_BAD_REQUEST);
        }
        $suite = new Suite($campaign);
        $suite->setFromDTO($suiteDTO);

        $violations = $validator->validate($suite);
        if (count($violations) > 0) {
            return $this->view($violations, Response::HTTP_BAD_REQUEST);
        }
        $em = $this->getDoctrine()->getManager();
        $em->persist($suite);
        $em->flush();

        return $suite;
    }

    /**
     * Update suite warning and success limits. Example: </br>
     * <pre style="background:black; color:white; font-size:10px;"><code style="background:black;">curl https://www.ci-report.io/api/projects/project-one/campaigns/1/suites/1/limits -H "Content-Type: application/json" -H "X-CIR-TKN: 1f4ffb19e4b9-02278af07b7d-4e370a76f001" -X PUT --data '{"warning":80, "success":95}'
     * </code></pre>.
     *
     * @param Suite          $suiteDB        Suite to update
     * @param SuiteLimitsDTO $suiteLimitsDTO Object containing input data
     * @param Request        $request        The request
     *
     * @return Suite|View
     *
     * @Rest\Put(
     *    "/projects/{prefid}/campaigns/{crefid}/suites/{srefid}/limits",
     *    requirements={"crefid" = "\d+", "srefid" = "\d+"}
     * )
     * @Rest\View(serializerGroups={"public"})
     *
     * @ParamConverter("suiteDB", class="AppBundle:Suite", options={
     *    "repository_method" = "findSuiteByProjectRefidCampaignRefidAndRefid",
     *    "mapping": {"prefid": "prefid", "crefid": "crefid", "srefid": "srefid"},
     *    "map_method_signature" = true
     * })
     * @ParamConverter("suiteLimitsDTO", converter="fos_rest.request_body")
     *
     * @Doc\ApiDoc(
     *     section="Suites",
     *     description="Update suite warning and success limits.",
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
     *         },
     *         {
     *             "name"="crefid",
     *             "dataType"="int",
     *             "requirement"="int",
     *             "description"="Reference id of the campaign."
     *         },
     *         {
     *             "name"="srefid",
     *             "dataType"="int",
     *             "requirement"="int",
     *             "description"="Reference id of the suite."
     *         }
     *     },
     *     input= { "class"=SuiteLimitsDTO::class },
     *     output= {
     *         "class"=Suite::class,
     *         "groups"={"public"},
     *         "parsers"={"Nelmio\ApiDocBundle\Parser\JmsMetadataParser"}
     *     },
     *     statusCodes={
     *         200="Returned when successful",
     *         400="Returned when a violation is raised by validation",
     *         401="Returned when X-CIR-TKN private token value is invalid",
     *         404="Returned when suite not found"
     *     },
     *     tags={
     *         "token" = "#2c3e50"
     *     }
     * )
     */
    public function putSuiteLimitsAction(Suite $suiteDB, SuiteLimitsDTO $suiteLimitsDTO, Request $request)
    {
        $project = $suiteDB->getCampaign()->getProject();
        if ($this->isInvalidToken($request, $project->getToken())) {
            return $this->getInvalidTokenView();
        }

        $validator = $this->get('validator');
        $violationsDTO = $validator->validate($suiteLimitsDTO);

        if (count($violationsDTO) > 0) {
            return $this->view($violationsDTO, Response::HTTP_BAD_REQUEST);
        }
        $suiteDB->setWarning($suiteLimitsDTO->getWarning());
        $suiteDB->setSuccess($suiteLimitsDTO->getSuccess());

        $violations = $validator->validate($suiteDB);
        if (count($violations) > 0) {
            return $this->view($violations, Response::HTTP_BAD_REQUEST);
        }
        $this->getDoctrine()->getManager()->flush();
        $this->get(RefreshService::class)->refreshCampaign(
            $suiteDB->getCampaign()
        );

        return $suiteDB;
    }

    /**
     * Delete a suite. Example: </br>
     * <pre style="background:black; color:white; font-size:10px;"><code style="background:black;">curl https://www.ci-report.io/api/projects/project-one/campaigns/1/suites/1 -H "X-CIR-TKN: 1f4ffb19e4b9-02278af07b7d-4e370a76f001" -X DELETE
     * </code></pre>.
     *
     * @param Suite   $suite   Suite to delete
     * @param Request $request The request
     *
     * @return void|View
     *
     * @Rest\Delete(
     *    "/projects/{prefid}/campaigns/{crefid}/suites/{srefid}",
     *    requirements={"crefid" = "\d+", "srefid" = "\d+"}
     * )
     * @Rest\View(statusCode=Response::HTTP_NO_CONTENT)
     *
     * @ParamConverter("suite", class="AppBundle:Suite", options={
     *    "repository_method" = "findSuiteByProjectRefidCampaignRefidAndRefid",
     *    "mapping": {"prefid": "prefid", "crefid": "crefid", "srefid": "srefid"},
     *    "map_method_signature" = true
     * })
     *
     * @Doc\ApiDoc(
     *     section="Suites",
     *     description="Delete a campaign.",
     *     headers={
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
     *         },
     *         {
     *             "name"="crefid",
     *             "dataType"="int",
     *             "requirement"="int",
     *             "description"="Reference id of the campaign."
     *         },
     *         {
     *             "name"="srefid",
     *             "dataType"="int",
     *             "requirement"="int",
     *             "description"="Reference id of the suite."
     *         }
     *     },
     *     statusCodes={
     *         204="Returned when successful",
     *         401="Returned when X-CIR-TKN private token value is invalid",
     *         404="Returned when suite not found",
     *         405="Returned when suite refid is not set in URL"
     *     },
     *     tags={
     *         "token" = "#2c3e50"
     *     }
     * )
     */
    public function deleteSuiteAction(Suite $suite, Request $request)
    {
        $project = $suite->getCampaign()->getProject();
        if ($this->isInvalidToken($request, $project->getToken())) {
            return $this->getInvalidTokenView();
        }
        $em = $this->getDoctrine()->getManager();
        $em->remove($suite);
        $em->flush();
        $this->get(RefreshService::class)->refreshCampaign(
            $suite->getCampaign()
        );
    }
}
