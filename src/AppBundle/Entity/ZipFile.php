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

namespace AppBundle\Entity;

use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;
use ZipArchive;

/**
 * Zip file class.
 *
 * @category  ci-report app
 *
 * @author    Francois-Xavier Soubirou <soubirou@yahoo.fr>
 * @copyright 201 Francois-Xavier Soubirou
 * @license   http://www.gnu.org/licenses/   GPLv3
 *
 * @see      https://www.ci-report.io
 */
class ZipFile
{
    /**
     * @var string
     *
     * @Serializer\Exclude
     */
    private $targetDir;

    /**
     * @var string
     *
     * @Serializer\Exclude
     */
    private $fileName;

    const MAX_FILES = 2000;

    /**
     * Constructor.
     *
     * @param string $targetDir Directory of file
     * @param string $fileName  Name of zip file
     */
    public function __construct(string $targetDir, string $fileName)
    {
        $this->targetDir = $targetDir;
        $this->fileName = $fileName;
    }

    /**
     * Set file name.
     *
     * @param string $name Name
     *
     * @return Suite
     */
    public function setFileName(string $name): self
    {
        $this->fileName = $name;

        return $this;
    }

    /**
     * Get file name.
     *
     * @return string
     */
    public function getFileName(): string
    {
        return $this->fileName;
    }

    /**
     * Sha1 hash of zip file.
     *
     * @return string
     *
     * @throws FileNotFoundException
     *
     * @Serializer\VirtualProperty
     * @Serializer\SerializedName("sha1")
     * @Serializer\Type("string")
     * @Serializer\Groups({"public", "private"})
     */
    public function getSha1(): string
    {
        $fileNameWithPath = $this->targetDir.'/'.$this->fileName;

        if (!file_exists($fileNameWithPath)) {
            throw new FileNotFoundException();
        }

        return sha1_file($fileNameWithPath);
    }

    /**
     * File size in bytes.
     *
     * @return int
     *
     * @throws FileNotFoundException
     *
     * @Serializer\VirtualProperty
     * @Serializer\SerializedName("size")
     * @Serializer\Type("int")
     * @Serializer\Groups({"public", "private"})
     */
    public function getSize(): int
    {
        $fileNameWithPath = $this->targetDir.'/'.$this->fileName;

        if (!file_exists($fileNameWithPath)) {
            throw new FileNotFoundException();
        }

        return filesize($fileNameWithPath);
    }

    /**
     * Files and directories count in zip file.
     *
     * @return int
     *
     * @throws FileNotFoundException
     *
     * @Serializer\VirtualProperty
     * @Serializer\SerializedName("count")
     * @Serializer\Type("int")
     * @Serializer\Groups({"public", "private"})
     */
    public function getFilesCount(): int
    {
        $fileNameWithPath = $this->targetDir.'/'.$this->fileName;

        if (!file_exists($fileNameWithPath)) {
            throw new FileNotFoundException();
        }
        $za = new ZipArchive();
        $za->open($fileNameWithPath);
        $count = $za->numFiles;
        $za->close();

        return $count;
    }

    /**
     * Get content in array of files and directories.
     *
     * @return array
     *
     * @throws FileNotFoundException
     */
    public function getContent(): array
    {
        $fileNameWithPath = $this->targetDir.'/'.$this->fileName;

        if (!file_exists($fileNameWithPath)) {
            throw new FileNotFoundException();
        }
        $za = new ZipArchive();
        $za->open($fileNameWithPath);
        $limit = min($za->numFiles, self::MAX_FILES);

        $directories = array();
        $files = array();

        for ($i = 0; $i < $limit; ++$i) {
            $stat = $za->statIndex($i);
            $first = strtok($stat['name'], '/');
            if (($first === $stat['name']) && ($stat['crc'] > 0)) {
                $files[$stat['name']] = $stat['size'];
            } else {
                if (!in_array($first, $directories)) {
                    $directories[] = $first;
                }
            }
        }
        $za->close();

        return array('directories' => $directories, 'files' => $files);
    }
}
