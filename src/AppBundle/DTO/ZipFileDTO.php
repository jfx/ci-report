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

namespace AppBundle\DTO;

use JMS\Serializer\Annotation\Type;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Zip files data transfert object class.
 *
 * @category  ci-report app
 *
 * @author    Francois-Xavier Soubirou <soubirou@yahoo.fr>
 * @copyright 2018 Francois-Xavier Soubirou
 * @license   http://www.gnu.org/licenses/   GPLv3
 *
 * @see      https://www.ci-report.io
 */
class ZipFileDTO
{
    /**
     * Zip file.
     *
     * @var File
     *
     * @Type("UploadedFile")
     *
     * @Assert\NotBlank(
     *     message = "A zip file must be specified."
     * )
     * @Assert\File(
     *     maxSize = "4096k",
     *     mimeTypes = {"application/zip"},
     *     mimeTypesMessage = "Please upload a valid zip file"
     * )
     */
    protected $zipfile;

    /**
     * Constructor.
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $file = $request->files->get('zipfile');
        if (null !== $file) {
            $this->setZipfile($file);
        }
    }

    /**
     * Set zip file.
     *
     * @param UploadedFile $file
     *
     * @return ZipFileDTO
     */
    public function setZipfile(UploadedFile $file): self
    {
        $this->zipfile = $file;

        return $this;
    }

    /**
     * Get zip file.
     *
     * @return UploadedFile
     */
    public function getZipfile(): ?UploadedFile
    {
        return $this->zipfile;
    }
}
