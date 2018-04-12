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

namespace App\Service;

use App\Entity\Campaign;
use App\Entity\Project;
use App\Entity\ZipFile;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * File uploader service class. Inspired from Symfony example:
 * https://symfony.com/doc/current/controller/upload_file.html.
 *
 * @category  ci-report app
 *
 * @author    Francois-Xavier Soubirou <soubirou@yahoo.fr>
 * @copyright 2018 Francois-Xavier Soubirou
 * @license   http://www.gnu.org/licenses/   GPLv3
 *
 * @see      https://www.ci-report.io
 */
class DocumentStorageService
{
    /**
     * @var string
     */
    private $storageDir;

    /**
     * Constructor.
     *
     * @param string $storageDir Directory to save temporary files
     */
    public function __construct(string $storageDir)
    {
        $this->storageDir = $storageDir;
    }

    /**
     * Attach zip to a suite.
     *
     * @param Project      $project  Project
     * @param Campaign     $campaign Campaign
     * @param UploadedFile $file     File to move
     *
     * @return ZipFile
     */
    public function storeZip(Project $project, Campaign $campaign, UploadedFile $file): ZipFile
    {
        $fileName = sha1(uniqid());

        $targetDir = $this->getTargetDir($project, $campaign, true);
        $file->move($targetDir, $fileName);

        return new ZipFile($targetDir, $fileName);
    }

    /**
     * Remove zip document.
     *
     * @param Project  $project  Project
     * @param Campaign $campaign Campaign
     * @param string   $fileName File to remove
     */
    public function remove(Project $project, Campaign $campaign, string $fileName)
    {
        $path = $this->getFullPath($project, $campaign, $fileName);

        if (file_exists($path)) {
            unlink($path);
        }
    }

    /**
     * Get full path of the file.
     *
     * @param Project  $project  Project
     * @param Campaign $campaign Campaign
     * @param string   $fileName File name
     *
     * @return string
     */
    public function getFullPath(Project $project, Campaign $campaign, string $fileName): string
    {
        $fileNameWithoutPath = basename($fileName);

        return $this->getTargetDir($project, $campaign).'/'.$fileNameWithoutPath;
    }

    /**
     * Get document directory.
     *
     * @param Project  $project  Project
     * @param Campaign $campaign Campaign
     * @param bool     $create   If true create directories if don't exist
     *
     * @return string
     */
    public function getTargetDir(Project $project, Campaign $campaign, bool $create = false): string
    {
        $targetDir = $this->storageDir.'/'.$project->getId().'/'.$campaign->getId();

        if ($create && !file_exists($targetDir)) {
            mkdir($targetDir, 0755, true);
        }

        return $targetDir;
    }
}
