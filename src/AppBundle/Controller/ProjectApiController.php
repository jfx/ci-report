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

use AppBundle\Entity\Project;
use AppBundle\Service\ProjectService;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation as Doc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
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
     *     output= { "class"=Project::class, "collection"=true, "groups"={"public"} },
     *     statusCodes={
     *         200="Returned when successful"
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
     * Create a project. Private data are sent by mail.
     *
     * @param Project                 $project    Project to create
     * @param ConstraintViolationList $violations List of violations
     *
     * @return Project|View
     *
     * @Rest\Post("/projects")
     * @ParamConverter("project", converter="fos_rest.request_body")
     * @Rest\View(statusCode=Response::HTTP_CREATED, serializerGroups={"public"})
     *
     * @Doc\ApiDoc(
     *     section="Projects",
     *     description="Create a project. Private data are sent by mail.",
     *     input= { "class"=Project::class, "groups"={"create"} },
     *     output= { "class"=Project::class, "groups"={"public"} },
     *     statusCodes={
     *         201="Returned when created",
     *         400="Returned when a violation is raised by validation"
     *     }
     * )
     */
    public function postProjectsAction(Project $project, ConstraintViolationList $violations)
    {
        if (count($violations)) {
            return $this->view($violations, Response::HTTP_BAD_REQUEST);
        }
        $this->get(ProjectService::class)->setSlugAndToken($project);

        if (null === $project->getWarningLimit()) {
            $project->setWarningLimit(Project::DEFAULT_WARNING_LIMIT);
        }
        if (null === $project->getSuccessLimit()) {
            $project->setSuccessLimit(Project::DEFAULT_SUCCESS_LIMIT);
        }
        $em = $this->getDoctrine()->getManager();
        $em->persist($project);
        $em->flush();

        return $project;
    }
}
