<?php

/**
 * Copyright (c) 2018 Francois-Xavier Soubirou.
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

use AppBundle\DTO\ZipFileDTO;
use AppBundle\Entity\Campaign;
use AppBundle\Entity\Project;
use AppBundle\Entity\Suite;
use AppBundle\Entity\ZipFile;
use AppBundle\Service\DocumentStorageService;
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
 * Document API controller class.
 *
 * @category  ci-report app
 *
 * @author    Francois-Xavier Soubirou <soubirou@yahoo.fr>
 * @copyright 2018 Francois-Xavier Soubirou
 * @license   http://www.gnu.org/licenses/   GPLv3
 *
 * @see      https://www.ci-report.io
 *
 * @Rest\Route("/api")
 */
class DocumentApiController extends AbstractApiController
{
    /**
     * Attach a zip archive to a suite. Example: </br>
     * <pre style="background:black; color:white; font-size:10px;"><code style="background:black;">curl https://www.ci-report.io/api/projects/project-one/campaigns/1/suites/1/doc/zip -H "X-CIR-TKN: 1f4ffb19e4b9-02278af07b7d-4e370a76f001" -X POST -F 'zipfile=@/path/to/myfile.zip'
     * </code></pre>
     * <p style="font-size:10px;">(@ symbol is mandatory at the beginning of the file path)</p>.
     *
     * @param Project  $project  Project
     * @param Campaign $campaign Campaign
     * @param Suite    $suite    Suite
     * @param Request  $request  The request
     *
     * @return array|View
     *
     * @Rest\Post("/projects/{prefid}/campaigns/{crefid}/suites/{srefid}/doc/zip",
     *    requirements={"crefid" = "\d+", "srefid" = "\d+"}
     * )
     * @Rest\View(statusCode=Response::HTTP_CREATED, serializerGroups={"public"})
     *
     * @ParamConverter("project", options={"mapping": {"prefid": "refid"}})
     * @Entity("campaign", expr="repository.findCampaignByProjectRefidAndRefid(prefid, crefid)")
     * @Entity("suite", expr="repository.findSuiteByProjectRefidCampaignRefidAndRefid(prefid, crefid, srefid)")
     *
     * @Operation(
     *     tags={"Documents"},
     *     summary="Attach a zip archive to a suite.",
     *     @SWG\Parameter(
     *         name="zipfile",
     *         in="formData",
     *         description="Zip file.",
     *         required=false,
     *         type="custom handler result for (UploadedFile)"
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
     *         description="Returned when project, campaign or suite not found"
     *     )
     * )
     *
     */
    public function postZipDocumentAction(Project $project, Campaign $campaign, Suite $suite, Request $request)
    {
        if ($this->isInvalidToken($request, $project->getToken())) {
            return $this->getInvalidTokenView();
        }
        if (null !== $suite->getDocumentUid()) {
            return $this->view(
                array(
                    'code' => Response::HTTP_BAD_REQUEST,
                    'message' => 'A zip file already exists',
                ),
                Response::HTTP_BAD_REQUEST
            );
        }
        $validator = $this->get('validator');
        $zipFileDTO = new ZipFileDTO($request);
        $violations = $validator->validate($zipFileDTO);
        if (count($violations) > 0) {
            return $this->view($violations, Response::HTTP_BAD_REQUEST);
        }
        $docStoreService = $this->get(DocumentStorageService::class);
        $zipFile = $docStoreService->storeZip(
            $project,
            $campaign,
            $zipFileDTO->getZipfile()
        );
        $suite->setDocumentUid($zipFile->getFileName());
        $this->getDoctrine()->getManager()->flush();

        return $zipFile;
    }

    /**
     * Delete a zip archive from a suite. Example: </br>
     * <pre style="background:black; color:white; font-size:10px;"><code style="background:black;">curl https://www.ci-report.io/api/projects/project-one/campaigns/1/suites/1/doc/zip -H "X-CIR-TKN: 1f4ffb19e4b9-02278af07b7d-4e370a76f001" -X DELETE
     * </code></pre>.
     *
     * @param Project  $project  Project
     * @param Campaign $campaign Campaign
     * @param Suite    $suite    Suite to delete
     * @param Request  $request  The request
     *
     * @return void|View
     *
     * @Rest\Delete(
     *    "/projects/{prefid}/campaigns/{crefid}/suites/{srefid}/doc/zip",
     *    requirements={"crefid" = "\d+", "srefid" = "\d+"}
     * )
     * @Rest\View(statusCode=Response::HTTP_NO_CONTENT)
     *
     * @ParamConverter("project", options={"mapping": {"prefid": "refid"}})
     * @Entity("campaign", expr="repository.findCampaignByProjectRefidAndRefid(prefid, crefid)")
     * @Entity("suite", expr="repository.findSuiteByProjectRefidCampaignRefidAndRefid(prefid, crefid, srefid)")
     *
     * @Operation(
     *     tags={"Documents"},
     *     summary="Delete a zip archive from a suite.",
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
     *     )
     * )
     *
     */
    public function deleteZipDocumentAction(Project $project, Campaign $campaign, Suite $suite, Request $request)
    {
        if ($this->isInvalidToken($request, $project->getToken())) {
            return $this->getInvalidTokenView();
        }
        $documentUid = $suite->getDocumentUid();

        if (null !== $documentUid) {
            $suite->setDocumentUid(null);
            $this->getDoctrine()->getManager()->flush();

            $docStoreService = $this->get(DocumentStorageService::class);
            $docStoreService->remove(
                $project,
                $campaign,
                $documentUid
            );
        }
    }
}
