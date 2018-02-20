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

use AppBundle\DTO\SuiteLimitsDTO;
use AppBundle\DTO\SuiteLimitsFilesDTO;
use AppBundle\Entity\Campaign;
use AppBundle\Entity\Project;
use AppBundle\Entity\Suite;
use AppBundle\Entity\Test;
use AppBundle\Service\FileUploaderService;
use AppBundle\Service\JunitParserService;
use AppBundle\Service\RefreshService;
use DOMDocument;
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
     * @Rest\Get("/projects/{prefid}/campaigns/{crefid}/suites",
     *    requirements={"crefid" = "\d+"}
     * )
     * @Rest\View(serializerGroups={"public"})
     *
     * @Entity("campaign", expr="repository.findCampaignByProjectRefidAndRefid(prefid, crefid)")
     *
     * @Operation(
     *     tags={"Suites"},
     *     summary="Get the list of all suites for a campaign.",
     *     @SWG\Response(
     *         response="200",
     *         description="Returned when successful array of public data of campaigns",
     *         @Model(type="AppBundle\Entity\Suite")
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="Returned when project not found"
     *     )
     * )
     *
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
     * @Entity("suite", expr="repository.findSuiteByProjectRefidCampaignRefidAndRefid(prefid, crefid, srefid)")
     *
     * @Operation(
     *     tags={"Suites"},
     *     summary="Get suite data.",
     *     @SWG\Response(
     *         response="200",
     *         description="Returned when successful",
     *         @Model(type="AppBundle\Entity\Suite")
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="Returned when project or campaign not found"
     *     )
     * )
     *
     */
    public function getSuiteAction(Suite $suite): Suite
    {
        return $suite;
    }

    /**
     * Create suites for a campaign by uploading a junit file. Example: </br>
     * <pre style="background:black; color:white; font-size:10px;"><code style="background:black;">curl https://www.ci-report.io/api/projects/project-one/campaigns/1/suites/junit -H "X-CIR-TKN: 1f4ffb19e4b9-02278af07b7d-4e370a76f001" -X POST -F warning=80 -F success=95 -F 'junitfile=@/path/to/junit.xml'
     * </code></pre>
     * <p style="font-size:10px;">(@ symbol is mandatory at the beginning of the file path)</p>.
     *
     * @param Project  $project  Project
     * @param Campaign $campaign Campaign
     * @param Request  $request  The request
     *
     * @return array|View
     *
     * @Rest\Post("/projects/{prefid}/campaigns/{crefid}/suites/junit",
     *    requirements={"crefid" = "\d+"}
     * )
     * @Rest\View(statusCode=Response::HTTP_CREATED, serializerGroups={"public"})
     *
     * @ParamConverter("project", options={"mapping": {"prefid": "refid"}})
     * @Entity("campaign", expr="repository.findCampaignByProjectRefidAndRefid(prefid, crefid)")
     *
     * @Operation(
     *     tags={"Suites"},
     *     summary="Create suites by uploading a junit file.",
     *     @SWG\Parameter(
     *         name="junitfile",
     *         in="formData",
     *         description="XML junit file.",
     *         required=false,
     *         type="custom handler result for (File)"
     *     ),
     *     @SWG\Parameter(
     *         name="warning",
     *         in="formData",
     *         description="Tests warning limit. Integer between 0 and 100 %. Limit defined on project by default.",
     *         required=false,
     *         type="integer"
     *     ),
     *     @SWG\Parameter(
     *         name="success",
     *         in="formData",
     *         description="Tests success limit. Integer between 0 and 100 %. Limit defined on project by default.",
     *         required=false,
     *         type="integer"
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
     *         description="Returned when project or campaign not found"
     *     )
     * )
     *
     */
    public function postSuiteAction(Project $project, Campaign $campaign, Request $request)
    {
        if ($this->isInvalidToken($request, $project->getToken())) {
            return $this->getInvalidTokenView();
        }

        $fileUploaderService = $this->get(FileUploaderService::class);
        $junitParserService = $this->get(JunitParserService::class);
        $validator = $this->get('validator');

        $suiteLimitsFilesDTO = new SuiteLimitsFilesDTO($project, $request);

        $violations = $validator->validate($suiteLimitsFilesDTO);
        if (count($violations) > 0) {
            return $this->view($violations, Response::HTTP_BAD_REQUEST);
        }

        $fileNameUId = $fileUploaderService->upload(
            $suiteLimitsFilesDTO->getJunitfile()
        );

        $doc = new DOMDocument('1.0', 'UTF-8');
        $doc->load($fileUploaderService->getFullPath($fileNameUId));
        $errors = $junitParserService->validate($doc);
        if (count($errors) > 0) {
            $fileUploaderService->remove($fileNameUId);

            return $this->view($errors, Response::HTTP_BAD_REQUEST);
        }
        $suitesArray = $junitParserService->parse($doc);
        $fileUploaderService->remove($fileNameUId);

        $em = $this->getDoctrine()->getManager();

        $suitesEntity = array();

        foreach ($suitesArray as $suiteTests) {
            $suiteDTO = $suiteTests->getSuite();
            $suiteDTO->setWarning($suiteLimitsFilesDTO->getWarning());
            $suiteDTO->setSuccess($suiteLimitsFilesDTO->getSuccess());

            $suite = new Suite($project, $campaign);
            $suite->setFromDTO($suiteDTO);

            $violations = $validator->validate($suite);
            if (count($violations) > 0) {
                return $this->view($violations, Response::HTTP_BAD_REQUEST);
            }
            $em->persist($suite);

            foreach ($suiteTests->getTests() as $testDTO) {
                $test = new Test($suite);
                $test->setFromDTO($testDTO);

                $violations = $validator->validate($test);
                if (count($violations) > 0) {
                    return $this->view($violations, Response::HTTP_BAD_REQUEST);
                }
                $em->persist($test);
            }
            $em->flush();
            $this->get(RefreshService::class)->refreshCampaign(
                $suite->getCampaign(),
                true
            );
            $suitesEntity[] = $suite;
        }

        return $suitesEntity;
    }

    /**
     * Update suite warning and success limits. Example: </br>
     * <pre style="background:black; color:white; font-size:10px;"><code style="background:black;">curl https://www.ci-report.io/api/projects/project-one/campaigns/1/suites/1/limits -H "Content-Type: application/json" -H "X-CIR-TKN: 1f4ffb19e4b9-02278af07b7d-4e370a76f001" -X PUT --data '{"warning":80, "success":95}'
     * </code></pre>.
     *
     * @param Project        $project        Project
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
     * @ParamConverter("project", options={"mapping": {"prefid": "refid"}})
     * @Entity("suiteDB", expr="repository.findSuiteByProjectRefidCampaignRefidAndRefid(prefid, crefid, srefid)")
     * @ParamConverter("suiteLimitsDTO", converter="fos_rest.request_body")
     *
     * @Operation(
     *     tags={"Suites"},
     *     summary="Update suite warning and success limits.",
     *     @SWG\Parameter(
     *         name="warning",
     *         in="body",
     *         description="Tests warning limit. Integer between 0 and 100 %. Limit defined on project by default.",
     *         required=false,
     *         type="integer",
     *         schema=""
     *     ),
     *     @SWG\Parameter(
     *         name="success",
     *         in="body",
     *         description="Tests success limit. Integer between 0 and 100 %. Limit defined on project by default.",
     *         required=false,
     *         type="integer",
     *         schema=""
     *     ),
     *     @SWG\Response(
     *         response="200",
     *         description="Returned when successful",
     *         @Model(type="AppBundle\Entity\Suite")
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
     *         description="Returned when suite not found"
     *     )
     * )
     *
     */
    public function putSuiteLimitsAction(Project $project, Suite $suiteDB, SuiteLimitsDTO $suiteLimitsDTO, Request $request)
    {
        if ($this->isInvalidToken($request, $project->getToken())) {
            return $this->getInvalidTokenView();
        }
        // If limit not defined get limit from suite
        if (null === $suiteLimitsDTO->getWarning()) {
            $suiteLimitsDTO->setWarning($suiteDB->getWarning());
        }
        if (null === $suiteLimitsDTO->getSuccess()) {
            $suiteLimitsDTO->setSuccess($suiteDB->getSuccess());
        }

        $validator = $this->get('validator');
        $violationsDTO = $validator->validate($suiteLimitsDTO);

        if (count($violationsDTO) > 0) {
            return $this->view($violationsDTO, Response::HTTP_BAD_REQUEST);
        }
        $suiteDB->setFromLimitsDTO($suiteLimitsDTO);

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
     * @param Project $project Project
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
     * @ParamConverter("project", options={"mapping": {"prefid": "refid"}})
     * @Entity("suite", expr="repository.findSuiteByProjectRefidCampaignRefidAndRefid(prefid, crefid, srefid)")
     *
     * @Operation(
     *     tags={"Suites"},
     *     summary="Delete a suite.",
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
     *         description="Returned when suite not found"
     *     ),
     *     @SWG\Response(
     *         response="405",
     *         description="Returned when suite refid is not set in URL"
     *     )
     * )
     *
     */
    public function deleteSuiteAction(Project $project, Suite $suite, Request $request)
    {
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
