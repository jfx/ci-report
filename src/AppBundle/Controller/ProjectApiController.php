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
     * Get list of projects. Example: </br>
     * <pre style="background:black; color:white; font-size:10px;"><code style="background:black;">curl https://www.ci-report.io/api/projects -X GET
     * </code></pre>.
     *
     * @return array
     *
     * @Rest\Get("/projects")
     * @Rest\View(serializerGroups={"public"})
     *
     * @Operation(
     *     tags={"Projects"},
     *     summary="Get the list of all projects.",
     *     @SWG\Response(
     *         response="200",
     *         description="Returned when successful array of public data of projects",
     *         @Model(type="AppBundle\Entity\Project")
     *     )
     * )
     *
     */
    public function getProjectsAction(): array
    {
        $projects = $this->getDoctrine()
            ->getRepository(Project::class)
            ->findAll();

        return $projects;
    }

    /**
     * Get public project data. Example: </br>
     * <pre style="background:black; color:white; font-size:10px;"><code style="background:black;">curl https://www.ci-report.io/api/projects/project-one -X GET
     * </code></pre>.
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
     *     @SWG\Response(
     *         response="200",
     *         description="Returned when successful",
     *         @Model(type="AppBundle\Entity\Project")
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="Returned when project not found"
     *     )
     * )
     *
     */
    public function getProjectAction(Project $project): Project
    {
        return $project;
    }

    /**
     * Get private project data. Example: </br>
     * <pre style="background:black; color:white; font-size:10px;"><code style="background:black;">curl https://www.ci-report.io/api/projects/project-one/private -H "X-CIR-TKN: 1f4ffb19e4b9-02278af07b7d-4e370a76f001" -X GET
     * </code></pre>.
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
     *     @SWG\Response(
     *         response="200",
     *         description="Returned when successful",
     *         @Model(type="AppBundle\Entity\Project")
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
    public function getProjectPrivateAction(Project $project, Request $request)
    {
        if ($this->isInvalidToken($request, $project->getToken())) {
            return $this->getInvalidTokenView();
        }

        return $project;
    }

    /**
     * Create a project. Private token is sent by email. Example: </br>
     * <pre style="background:black; color:white; font-size:10px;"><code style="background:black;">curl https://www.ci-report.io/api/projects -H "Content-Type: application/json" -X POST --data '{"name":"Project To Add", "warning":80, "success":95, "email":"test@example.com"}'
     * </code></pre>.
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
     *     @SWG\Parameter(
     *         name="name",
     *         in="formData",
     *         description="Name of the project.",
     *         required=false,
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         name="warning",
     *         in="formData",
     *         description="Tests warning limit. Integer between 0 and 100 %.",
     *         required=false,
     *         type="integer"
     *     ),
     *     @SWG\Parameter(
     *         name="success",
     *         in="formData",
     *         description="Tests success limit. Integer between 0 and 100 %.",
     *         required=false,
     *         type="integer"
     *     ),
     *     @SWG\Parameter(
     *         name="email",
     *         in="formData",
     *         description="Email.",
     *         required=false,
     *         type="string"
     *     ),
     *     @SWG\Response(
     *         response="201",
     *         description="Returned when created"
     *     ),
     *     @SWG\Response(
     *         response="400",
     *         description="Returned when a violation is raised by validation"
     *     )
     * )
     *
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
     * Update a project. Example: </br>
     * <pre style="background:black; color:white; font-size:10px;"><code style="background:black;">curl https://www.ci-report.io/api/projects/project-one -H "Content-Type: application/json" -H "X-CIR-TKN: 1f4ffb19e4b9-02278af07b7d-4e370a76f001" -X PUT --data '{"name":"Project To Update", "warning":85, "success":90, "email":"test-changed@example.com"}'
     * </code></pre>.
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
     *     @SWG\Parameter(
     *         name="name",
     *         in="body",
     *         description="Name of the project.",
     *         required=false,
     *         type="string",
     *         schema=""
     *     ),
     *     @SWG\Parameter(
     *         name="warning",
     *         in="body",
     *         description="Tests warning limit. Integer between 0 and 100 %.",
     *         required=false,
     *         type="integer",
     *         schema=""
     *     ),
     *     @SWG\Parameter(
     *         name="success",
     *         in="body",
     *         description="Tests success limit. Integer between 0 and 100 %.",
     *         required=false,
     *         type="integer",
     *         schema=""
     *     ),
     *     @SWG\Parameter(
     *         name="email",
     *         in="body",
     *         description="Email.",
     *         required=false,
     *         type="string",
     *         schema=""
     *     ),
     *     @SWG\Response(
     *         response="200",
     *         description="Returned when successful",
     *         @Model(type="AppBundle\Entity\Project")
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
     * Delete a project. Example: </br>
     * <pre style="background:black; color:white; font-size:10px;"><code style="background:black;">curl https://www.ci-report.io/api/projects/project-one -H "X-CIR-TKN: 1f4ffb19e4b9-02278af07b7d-4e370a76f001" -X DELETE
     * </code></pre>.
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
     *         description="Returned when project not found"
     *     )
     * )
     *
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
