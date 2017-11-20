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

namespace AppBundle\DTO;

use AppBundle\Entity\Project;
use JMS\Serializer\Annotation\Type;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Suite limits and junit files data transfert object class.
 *
 * @category  ci-report app
 *
 * @author    Francois-Xavier Soubirou <soubirou@yahoo.fr>
 * @copyright 2017 Francois-Xavier Soubirou
 * @license   http://www.gnu.org/licenses/   GPLv3
 *
 * @see      https://www.ci-report.io
 */
class SuiteLimitsFilesDTO extends SuiteLimitsDTO
{
    /**
     * XML junit file.
     *
     * @var File
     *
     * @Type("File")
     *
     * @Assert\NotBlank(
     *     message = "A junit file must be specified."
     * )
     * @Assert\File(
     *     maxSize = "1024k",
     *     mimeTypes = {"application/xml"},
     *     mimeTypesMessage = "Please upload a valid XML file"
     * )
     */
    protected $junitfile;

    /**
     * Constructor.
     *
     * @param Project $project
     * @param Request $request
     */
    public function __construct(Project $project, Request $request)
    {
        $warning = $request->request->get('warning');
        if (null !== $warning) {
            $this->setWarning((int) $warning);
        } else {
            $this->setWarning($project->getWarning());
        }
        $success = $request->request->get('success');
        if (null !== $success) {
            $this->setSuccess((int) $success);
        } else {
            $this->setSuccess($project->getSuccess());
        }

        $file = $request->files->get('junitfile');
        if (null !== $file) {
            $this->setJunitfile($file);
        }
    }

    /**
     * Set junit xml file.
     *
     * @param File $file
     *
     * @return SuiteLimitsFilesDTO
     */
    public function setJunitfile(File $file): self
    {
        $this->junitfile = $file;

        return $this;
    }

    /**
     * Get junit xml file.
     *
     * @return File
     */
    public function getJunitfile(): ?File
    {
        return $this->junitfile;
    }
}
