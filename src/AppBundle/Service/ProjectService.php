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

namespace AppBundle\Service;

use AppBundle\Entity\Project;
use Doctrine\Common\Persistence\ManagerRegistry;
use Swift_Mailer;
use Swift_Message;
use Twig\Environment;

/**
 * Project service class.
 *
 * @category  ci-report app
 *
 * @author    Francois-Xavier Soubirou <soubirou@yahoo.fr>
 * @copyright 2017 Francois-Xavier Soubirou
 * @license   http://www.gnu.org/licenses/   GPLv3
 *
 * @see      https://ci-report.io
 */
class ProjectService
{
    /**
     * @var ManagerRegistry
     */
    private $doctrine;

    /**
     * @var Swift_Mailer
     */
    private $mailer;

    /**
     * @var Environment
     */
    private $twig;

    /**
     * @var UtilService
     */
    private $utilService;

    /**
     * Constructor.
     *
     * @param ManagerRegistry $doctrine Doctrine registry manager
     * @param Swift_Mailer    $mailer   Mailer service
     * @param Environment     $twig     Twig service
     */
    public function __construct(ManagerRegistry $doctrine, Swift_Mailer $mailer, Environment $twig)
    {
        $this->doctrine = $doctrine;
        $this->mailer = $mailer;
        $this->twig = $twig;
        $this->utilService = new UtilService();
    }

    /**
     * Generate a unique slug and token for a project.
     *
     * @param Project $project Project
     */
    public function setSlugAndToken(Project $project): void
    {
        $repository = $this->doctrine->getRepository(Project::class);
        $slug = $this->utilService->toAscii($project->getName());

        $root = $slug;
        $separator = '-';
        $ext = 0;

        while ($repository->refidExists($slug)) {
            ++$ext;
            $slug = $root.$separator.$ext;
        }
        $project->setRefid($slug);

        $project->setToken($this->utilService->generateToken());
    }

    /**
     * Send a registration email.
     *
     * @param Project $project Project
     */
    public function sendRegistrationEmail(Project $project): void
    {
        $title = 'ci-report: "'.$project->getName().'" registered';

        $message = (new Swift_Message($title))
            ->setFrom('noreply@ci-report.io')
            ->setTo($project->getEmail())
            ->setBody(
                $this->twig->render(
                    'Emails/registration.html.twig',
                    array(
                        'project' => $project,
                    )
                ),
                'text/html'
            );
        $this->mailer->send($message);
    }
}
