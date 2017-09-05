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
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation as Doc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolationList;

/**
 * Project controller class.
 *
 * @category  ci-report app
 *
 * @author    Francois-Xavier Soubirou <soubirou@yahoo.fr>
 * @copyright 2017 Francois-Xavier Soubirou
 * @license   http://www.gnu.org/licenses/   GPLv3
 *
 * @see      https://ci-report.io
 *
 * @Rest\Route("/api")
 */
class ProjectApiController extends FOSRestController
{
    /**
     * Get list of projects.
     *
     * @return array
     *
     * @Rest\Get("/projects")
     * @Rest\View(serializerGroups={"public"})
     *
     * @Doc\ApiDoc(
     *     section="Projects",
     *     description="Get the list of all projects.",
     *     output={
     *         "class"=Project::class,
     *         "groups"={"public"},
     *         "parsers"={"Nelmio\ApiDocBundle\Parser\JmsMetadataParser"}
     *     },
     *     statusCodes={
     *         200="Returned when successful array of public data of projects"
     *     }
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
     * @param Project $project Project to create
     *
     * @return Project
     *
     * @Rest\Get("/projects/{refid}")
     * @Rest\View(serializerGroups={"public"})
     *
     * @ParamConverter("project", options={"mapping": {"refid": "refid"}})
     *
     * @Doc\ApiDoc(
     *     section="Projects",
     *     description="Get public project data.",
     *     requirements={
     *         {
     *             "name"="refid",
     *             "dataType"="string",
     *             "requirement"="string",
     *             "description"="Unique short name of project defined on project creation."
     *         }
     *     },
     *     output= {
     *         "class"=Project::class,
     *         "groups"={"public"},
     *         "parsers"={"Nelmio\ApiDocBundle\Parser\JmsMetadataParser"}
     *     },
     *     statusCodes={
     *         200="Returned when successful",
     *         404="Returned when project not found"
     *     }
     * )
     */
    public function getProjectAction(Project $project): Project
    {
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
     * @Doc\ApiDoc(
     *     section="Projects",
     *     description="Create a project. Private data are sent by mail.",
     *     input= { "class"=ProjectDTO::class },
     *     output= {
     *         "class"=Project::class,
     *         "groups"={"private"},
     *         "parsers"={"Nelmio\ApiDocBundle\Parser\JmsMetadataParser"}
     *     },
     *     statusCodes={
     *         201="Returned when created",
     *         400="Returned when a violation is raised by validation"
     *     }
     * )
     */
    public function postProjectsAction(Project $project, ConstraintViolationList $violations)
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
     * @param Project $projectDTO Project containing values to update
     * @param Project $projectDB  Project to update
     * @param Request $request    The request
     *
     * @return Project|View
     *
     * @Rest\Put("/projects/{refid}")
     * @Rest\View(serializerGroups={"private"})
     *
     * @ParamConverter("projectDB", options={"mapping": {"refid": "refid"}})
     * @ParamConverter("projectDTO", converter="fos_rest.request_body")
     *
     * @Doc\ApiDoc(
     *     section="Projects",
     *     description="Update a project.",
     *     headers={
     *         {
     *             "name"="X-CIR-TKN",
     *             "required"=true,
     *             "description"="Private token"
     *         }
     *     },
     *     requirements={
     *         {
     *             "name"="ref_id",
     *             "dataType"="string",
     *             "requirement"="string",
     *             "description"="Unique short name of project defined on project creation."
     *         }
     *     },
     *     input= { "class"=ProjectDTO::class },
     *     output= {
     *         "class"=Project::class,
     *         "groups"={"private"},
     *         "parsers"={"Nelmio\ApiDocBundle\Parser\JmsMetadataParser"}
     *     },
     *     statusCodes={
     *         200="Returned when successful",
     *         400="Returned when a violation is raised by validation",
     *         401="Returned when X-CIR-TKN private token value is invalid"
     *     },
     *     tags={
     *         "token" = "#87ceeb"
     *     }
     * )
     */
    public function putProjectsAction(ProjectDTO $projectDTO, Project $projectDB, Request $request)
    {
        $token = $request->headers->get('X-CIR-TKN');

        if ((null === $token) || ($projectDB->getToken() !== $token)) {
            return $this->view(
                array(
                    'code' => Response::HTTP_UNAUTHORIZED,
                    'message' => 'Invalid token',
                ),
                Response::HTTP_UNAUTHORIZED
            );
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
}
