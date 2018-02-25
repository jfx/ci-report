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

use AppBundle\DTO\ProjectDTO;
use AppBundle\Entity\Project;
use AppBundle\Service\ProjectService;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\Operation;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolationList;

/**
 * Project API controller class.
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
class ProjectApiController extends AbstractApiController
{
    /**
     * Get list of projects.
     *
     * @return array
     *
     * @Rest\Get("/projects")
     * @Rest\View(serializerGroups={"public"})
     *
     * @Operation(
     *     tags={"Projects"},
     *     summary="Get the list of all projects.",
     *     description="Example: </br><pre><code>curl https://www.ci-report.io/api/projects -X GET</code></pre>",
     *     @SWG\Response(
     *         response="200",
     *         description="Returned when successful array of public data of projects",
     *         @SWG\Schema(
     *            type="array",
     *            @Model(type=Project::class, groups={"public"})
     *         )
     *     ),
     *     @SWG\Response(
     *         response="default",
     *         description="Unexpected error",
     *         @SWG\Schema(ref="#/definitions/ErrorModel")
     *     )
     * )
     */
    public function getProjectsAction(): array
    {
        $projects = $this->getDoctrine()
            ->getRepository(Project::class)
            ->findAll();

        return $projects;
    }

    /**
     * Get public project data.
     *
     * @param Project $project Project
     *
     * @return Project
     *
     * @Rest\Get("/projects/{prefid}")
     * @Rest\View(serializerGroups={"public"})
     *
     * @ParamConverter("project", options={"mapping": {"prefid": "refid"}})
     *
     * @Operation(
     *     tags={"Projects"},
     *     summary="Get public project data.",
     *     description="Example: </br><pre><code>curl https://www.ci-report.io/api/projects/project-one -X GET</code></pre>",
     *     @SWG\Parameter(
     *         name="prefid",
     *         in="path",
     *         description="Unique short name of project defined on project creation.",
     *         type="string"
     *     ),
     *     @SWG\Response(
     *         response="200",
     *         description="Returned when successful",
     *         @Model(type=Project::class, groups={"public"})
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="Returned when project not found",
     *         @SWG\Schema(ref="#/definitions/ErrorModel")
     *     )
     * )
     */
    public function getProjectAction(Project $project): Project
    {
        return $project;
    }

    /**
     * Get private project data.
     *
     * @param Project $project Project
     * @param Request $request The request
     *
     * @return Project|View
     *
     * @Rest\Get("/projects/{prefid}/private")
     * @Rest\View(serializerGroups={"private"})
     *
     * @ParamConverter("project", options={"mapping": {"prefid": "refid"}})
     *
     * @Operation(
     *     tags={"Projects"},
     *     summary="Get private project data.",
     *     description="Example: </br><pre><code>curl https://www.ci-report.io/api/projects/project-one/private -H &quot;X-CIR-TKN: 1f4ffb19e4b9-02278af07b7d-4e370a76f001&quot; -X GET</code></pre>",
     *     @SWG\Parameter(
     *         name="X-CIR-TKN",
     *         in="header",
     *         required=true,
     *         description="Private token",
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         name="prefid",
     *         in="path",
     *         description="Unique short name of project defined on project creation.",
     *         type="string"
     *     ),
     *     @SWG\Response(
     *         response="200",
     *         description="Returned when successful",
     *         @Model(type=Project::class, groups={"private"})
     *     ),
     *     @SWG\Response(
     *         response="401",
     *         description="Returned when X-CIR-TKN private token value is invalid",
     *         @SWG\Schema(ref="#/definitions/ErrorModel")
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="Returned when project not found",
     *         @SWG\Schema(ref="#/definitions/ErrorModel")
     *     )
     * )
     */
    public function getProjectPrivateAction(Project $project, Request $request)
    {
        if ($this->isInvalidToken($request, $project->getToken())) {
            return $this->getInvalidTokenView();
        }

        return $project;
    }

    /**
     * Create a project. Private token is sent by email.
     *
     * @param Project                 $project    Project to create
     * @param ConstraintViolationList $violations List of violations
     *
     * @return Project|View
     *
     * @Rest\Post("/projects")
     * @Rest\View(statusCode=Response::HTTP_CREATED, serializerGroups={"private"})
     *
     * @ParamConverter("project", converter="fos_rest.request_body", options={ "validator"={"groups"={"input", "unique"}} } )
     *
     * @Operation(
     *     tags={"Projects"},
     *     summary="Create a project. Private data are sent by mail.",
     *     description="Example: </br><pre><code>curl https://www.ci-report.io/api/projects -H &quot;Content-Type: application/json&quot; -X POST --data '{&quot;name&quot;:&quot;Project To Add&quot;, &quot;warning&quot;:80, &quot;success&quot;:95, &quot;email&quot;:&quot;test@example.com&quot;}'</code></pre>",
     *     @SWG\Parameter(
     *         name="data",
     *         in="body",
     *         required=true,
     *         @SWG\Schema(ref="#/definitions/ProjectDataModel")
     *     ),
     *     @SWG\Response(
     *         response="201",
     *         description="Returned when created",
     *         @Model(type=Project::class, groups={"private"})
     *     ),
     *     @SWG\Response(
     *         response="400",
     *         description="Returned when a violation is raised by validation",
     *         @SWG\Schema(
     *            type="array",
     *            @SWG\Items(ref="#/definitions/ErrorModel")
     *         )
     *     )
     * )
     */
    public function postProjectAction(Project $project, ConstraintViolationList $violations)
    {
        if (count($violations) > 0) {
            return $this->view($violations, Response::HTTP_BAD_REQUEST);
        }
        $projectService = $this->get(ProjectService::class);
        $projectService->setSlugAndToken($project);

        $em = $this->getDoctrine()->getManager();
        $em->persist($project);
        $em->flush();

        $projectService->sendRegistrationEmail($project);

        return $project;
    }

    /**
     * Update a project.
     *
     * @param Project $projectDB  Project to update
     * @param Project $projectDTO Project containing values to update
     * @param Request $request    The request
     *
     * @return Project|View
     *
     * @Rest\Put("/projects/{prefid}")
     * @Rest\View(serializerGroups={"private"})
     *
     * @ParamConverter("projectDB", options={"mapping": {"prefid": "refid"}})
     * @ParamConverter("projectDTO", converter="fos_rest.request_body")
     *
     * @Operation(
     *     tags={"Projects"},
     *     summary="Update a project.",
     *     description="Example: </br><pre><code>curl https://www.ci-report.io/api/projects/project-one -H &quot;Content-Type: application/json&quot; -H &quot;X-CIR-TKN: 1f4ffb19e4b9-02278af07b7d-4e370a76f001&quot; -X PUT --data '{&quot;name&quot;:&quot;Project To Update&quot;, &quot;warning&quot;:85, &quot;success&quot;:90, &quot;email&quot;:&quot;test-changed@example.com&quot;}'</code></pre>",
     *     @SWG\Parameter(
     *         name="X-CIR-TKN",
     *         in="header",
     *         required=true,
     *         description="Private token",
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         name="prefid",
     *         in="path",
     *         description="Unique short name of project defined on project creation.",
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         name="data",
     *         in="body",
     *         required=true,
     *         @SWG\Schema(ref="#/definitions/ProjectDataModel")
     *     ),
     *     @SWG\Response(
     *         response="200",
     *         description="Returned when successful",
     *         @Model(type=Project::class, groups={"private"})
     *     ),
     *     @SWG\Response(
     *         response="400",
     *         description="Returned when a violation is raised by validation",
     *         @SWG\Schema(
     *            type="array",
     *            @SWG\Items(ref="#/definitions/ErrorModel")
     *         )
     *     ),
     *     @SWG\Response(
     *         response="401",
     *         description="Returned when X-CIR-TKN private token value is invalid",
     *         @SWG\Schema(ref="#/definitions/ErrorModel")
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="Returned when project not found",
     *         @SWG\Schema(ref="#/definitions/ErrorModel")
     *     )
     * )
     */
    public function putProjectAction(Project $projectDB, ProjectDTO $projectDTO, Request $request)
    {
        if ($this->isInvalidToken($request, $projectDB->getToken())) {
            return $this->getInvalidTokenView();
        }

        $validator = $this->get('validator');
        $violationsDTO = $validator->validate($projectDTO);

        if (count($violationsDTO) > 0) {
            return $this->view($violationsDTO, Response::HTTP_BAD_REQUEST);
        }
        $projectDB->setFromDTO($projectDTO);

        // Check for unique name.
        $violationsDB = $validator->validate($projectDB, null, array('input', 'unique'));
        if (count($violationsDB) > 0) {
            return $this->view($violationsDB, Response::HTTP_BAD_REQUEST);
        }
        $this->getDoctrine()->getManager()->flush();

        return $projectDB;
    }

    /**
     * Delete a project.
     *
     * @param Project $project Project
     * @param Request $request The request
     *
     * @return void|View
     *
     * @Rest\Delete("/projects/{prefid}")
     * @Rest\View(statusCode=Response::HTTP_NO_CONTENT)
     *
     * @ParamConverter("project", options={"mapping": {"prefid": "refid"}})
     *
     * @Operation(
     *     tags={"Projects"},
     *     summary="Delete a project.",
     *     description="Example: </br><pre><code>curl https://www.ci-report.io/api/projects/project-one -H &quot;X-CIR-TKN: 1f4ffb19e4b9-02278af07b7d-4e370a76f001&quot; -X DELETE</code></pre>",
     *     @SWG\Parameter(
     *         name="X-CIR-TKN",
     *         in="header",
     *         required=true,
     *         description="Private token",
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         name="prefid",
     *         in="path",
     *         description="Unique short name of project defined on project creation.",
     *         type="string"
     *     ),
     *     @SWG\Response(
     *         response="204",
     *         description="Returned when successful"
     *     ),
     *     @SWG\Response(
     *         response="401",
     *         description="Returned when X-CIR-TKN private token value is invalid",
     *         @SWG\Schema(ref="#/definitions/ErrorModel")
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="Returned when project not found",
     *         @SWG\Schema(ref="#/definitions/ErrorModel")
     *     )
     * )
     */
    public function deleteProjectAction(Project $project, Request $request)
    {
        if ($this->isInvalidToken($request, $project->getToken())) {
            return $this->getInvalidTokenView();
        }
        $em = $this->getDoctrine()->getManager();
        $em->remove($project);
        $em->flush();
    }
}
