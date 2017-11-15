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

use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * File uploader service class. Inspired from Symfony example:
 * https://symfony.com/doc/current/controller/upload_file.html.
 *
 * @category  ci-report app
 *
 * @author    Francois-Xavier Soubirou <soubirou@yahoo.fr>
 * @copyright 2017 Francois-Xavier Soubirou
 * @license   http://www.gnu.org/licenses/   GPLv3
 *
 * @see      https://www.ci-report.io
 */
class FileUploaderService
{
    /**
     * @var string
     */
    private $targetDir;

    /**
     * Constructor.
     *
     * @param string $targetDir Directory to save temporary files
     */
    public function __construct(string $targetDir)
    {
        $this->targetDir = $targetDir;
    }

    /**
     * Move file temporary directory.
     *
     * @param UploadedFile $file File to move
     *
     * @return string
     */
    public function upload(UploadedFile $file): string
    {
        $fileName = md5(uniqid()).'.'.$file->guessExtension();

        $file->move($this->getTargetDir(), $fileName);

        return $fileName;
    }

    /**
     * Remove file from temporary directory.
     *
     * @param string $fileName File to remove
     */
    public function remove(string $fileName)
    {
        $fileNameWithoutPath = basename($fileName);
        unlink($this->getTargetDir().'/'.$fileNameWithoutPath);
    }

    /**
     * Get full path of the file.
     *
     * @param string $fileName File name
     *
     * @return string
     */
    public function getFullPath(string $fileName): string
    {
        $fileNameWithoutPath = basename($fileName);

        return $this->targetDir.'/'.$fileNameWithoutPath;
    }

    /**
     * Get temporary directory name.
     *
     * @return string
     */
    public function getTargetDir(): string
    {
        return $this->targetDir;
    }
}
