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
use App\Entity\Test;
use App\Service\DocumentStorageService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Suite controller class.
 *
 * @category  ci-report app
 *
 * @author    Francois-Xavier Soubirou <soubirou@yahoo.fr>
 * @copyright 2017 Francois-Xavier Soubirou
 * @license   http://www.gnu.org/licenses/   GPLv3
 *
 * @see      https://www.ci-report.io
 */
class SuiteController extends Controller
{
    /**
     * Index action.
     *
     * @param Project  $project  Project
     * @param Campaign $campaign Campaign
     * @param Suite    $suite    The suite to display
     *
     * @return Response A Response instance
     *
     * @Route(
     *    "/project/{prefid}/campaign/{crefid}/suite/{srefid}",
     *    requirements={"crefid" = "\d+", "srefid" = "\d+"},
     *    name="suite-view"
     * )
     *
     * @ParamConverter("project", options={"mapping": {"prefid": "refid"}})
     * @Entity("campaign", expr="repository.findCampaignByProjectRefidAndRefid(prefid, crefid)")
     * @Entity("suite", expr="repository.findSuiteByProjectRefidCampaignRefidAndRefid(prefid, crefid, srefid)")
     */
    public function indexAction(Project $project, Campaign $campaign, Suite $suite): Response
    {
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

    /**
     * Download document action.
     *
     * @param Project  $project  Project
     * @param Campaign $campaign Campaign
     * @param Suite    $suite    The suite to display
     *
     * @return BinaryFileResponse The file to download
     *
     * @Route(
     *    "/project/{prefid}/campaign/{crefid}/suite/{srefid}/doc",
     *    requirements={"crefid" = "\d+", "srefid" = "\d+"},
     *    name="suite-doc"
     * )
     *
     * @ParamConverter("project", options={"mapping": {"prefid": "refid"}})
     * @Entity("campaign", expr="repository.findCampaignByProjectRefidAndRefid(prefid, crefid)")
     * @Entity("suite", expr="repository.findSuiteByProjectRefidCampaignRefidAndRefid(prefid, crefid, srefid)")
     */
    public function docAction(Project $project, Campaign $campaign, Suite $suite): BinaryFileResponse
    {
        if (null === $suite->getDocumentUid()) {
            throw $this->createNotFoundException('The zip document does not exist');
        }
        $docStoreService = $this->get(DocumentStorageService::class);
        $filePath = $docStoreService->getFullPath(
            $project,
            $campaign,
            $suite->getDocumentUid()
        );
        $response = new BinaryFileResponse($filePath);
        $response->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            'documents.zip'
        );
        $response->headers->set('Content-Type', 'application/zip');

        return $response;
    }
}
