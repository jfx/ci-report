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

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Test entity class.
 *
 * @category  ci-report app
 *
 * @author    Francois-Xavier Soubirou <soubirou@yahoo.fr>
 * @copyright 2017 Francois-Xavier Soubirou
 * @license   http://www.gnu.org/licenses/   GPLv3
 *
 * @see      https://www.ci-report.io
 *
 * @ORM\Table(name="cir_test")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TestRepository")
 */
class Test
{
    const DEFAULT_PACKAGE = '_root_';

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=256)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="classname", type="string", length=256)
     */
    private $className;

    /**
     * @var string
     *
     * @ORM\Column(name="package", type="string", length=256)
     */
    private $package;

    /**
     * @var int
     *
     * @ORM\Column(name="passed", type="smallint")
     */
    private $passed;

    /**
     * @var int
     *
     * @ORM\Column(name="failed", type="smallint")
     */
    private $failed;

    /**
     * @var int
     *
     * @ORM\Column(name="errored", type="smallint")
     */
    private $errored;

    /**
     * @var int
     *
     * @ORM\Column(name="skipped", type="smallint")
     */
    private $skipped;

    /**
     * @var float
     *
     * @ORM\Column(name="duration", type="float")
     */
    private $duration;

    /**
     * @var string
     *
     * @ORM\Column(name="system_out", type="text")
     */
    private $systemOut;

    /**
     * @var string
     *
     * @ORM\Column(name="system_err", type="text")
     */
    private $systemErr;

    /**
     * @Gedmo\SortablePosition
     * @ORM\Column(name="position", type="integer")
     */
    private $position;

    /**
     * @var Suite
     *
     * @Gedmo\SortableGroup
     * @ORM\ManyToOne(targetEntity="Suite")
     * @ORM\JoinColumn(name="suite_id", referencedColumnName="id", nullable=false, onDelete="cascade")
     */
    private $suite;

    /**
     * Constructor.
     *
     * @param Suite $suite The suite
     */
    public function __construct(Suite $suite)
    {
        $this->setSuite($suite);
    }

    /**
     * Get id.
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Set name.
     *
     * @param string $name Name
     *
     * @return Test
     */
    public function setName(string $name): Test
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Set class name.
     *
     * @param string $classname Class name
     *
     * @return Test
     */
    public function setClassName(string $classname): Test
    {
        $this->className = $classname;

        return $this;
    }

    /**
     * Get class name.
     *
     * @return string
     */
    public function getClassName(): string
    {
        return $this->className;
    }

    /**
     * Set package.
     *
     * @param string $package Package of the test
     *
     * @return Test
     */
    public function setPackage(string $package): Test
    {
        $this->package = $package;

        return $this;
    }

    /**
     * Get package.
     *
     * @return string
     */
    public function getPackage(): string
    {
        return $this->package;
    }

    /**
     * Set full class name including package.
     *
     * @param string $fullClassName The full class name
     *
     * @return Test
     */
    public function setFullClassName(string $fullClassName): Test
    {
        if (substr_count($fullClassName, '.') > 0) {
            $index = strrpos($fullClassName, '.');
            $this->setPackage(substr($fullClassName, 0, $index));
            $this->setClassName(substr($fullClassName, $index + 1));
        } else {
            $this->setPackage(self::DEFAULT_PACKAGE);
            $this->setClassName($fullClassName);
        }

        return $this;
    }

    /**
     * Set test to passed.
     *
     * @return Test
     */
    public function setpassed(): Test
    {
        $this->passed = 1;
        $this->failed = 0;
        $this->errored = 0;
        $this->skipped = 0;

        return $this;
    }

    /**
     * Return 1 if test is passed.
     *
     * @return int
     */
    public function getPassed(): int
    {
        return $this->passed;
    }

    /**
     * Set test to failed.
     *
     * @return Test
     */
    public function setFailed(): Test
    {
        $this->passed = 0;
        $this->failed = 1;
        $this->errored = 0;
        $this->skipped = 0;

        return $this;
    }

    /**
     * Return 1 if test is failed.
     *
     * @return int
     */
    public function getFailed(): int
    {
        return $this->failed;
    }

    /**
     * Set test to errored.
     *
     * @return Test
     */
    public function setErrored(): Test
    {
        $this->passed = 0;
        $this->failed = 0;
        $this->errored = 1;
        $this->skipped = 0;

        return $this;
    }

    /**
     * Return 1 if test is errored.
     *
     * @return int
     */
    public function getErrored(): int
    {
        return $this->errored;
    }

    /**
     * Set test to skipped.
     *
     * @return Test
     */
    public function setSkipped(): Test
    {
        $this->passed = 0;
        $this->failed = 0;
        $this->errored = 0;
        $this->skipped = 1;

        return $this;
    }

    /**
     * Return 1 if test is skipped.
     *
     * @return int
     */
    public function getSkipped(): int
    {
        return $this->skipped;
    }

    /**
     * Set status of test.
     *
     * @param int $status Status (const of Status class)
     *
     * @return Test
     */
    public function setStatus(int $status): Test
    {
        switch ($status) {
            case Status::SUCCESS:
                $this->setPassed();
                break;
            case Status::FAILED:
                $this->setFailed();
                break;
            case Status::ERROR:
                $this->setErrored();
                break;
            case Status::SKIPPED:
                $this->setSkipped();
                break;
        }

        return $this;
    }

    /**
     * Return  status (const of Status class).
     *
     * @return int
     */
    public function getStatus(): int
    {
        if ($this->passed > 0) {
            return Status::SUCCESS;
        }
        if ($this->failed > 0) {
            return Status::FAILED;
        }
        if ($this->skipped > 0) {
            return Status::SKIPPED;
        }

        return Status::ERROR;
    }

    /**
     * Get label of status.
     *
     * @return string
     */
    public function getLabelStatus(): string
    {
        return Status::getLabel($this->getStatus());
    }

    /**
     * Set duration in seconds.
     *
     * @param float $duration Duration
     *
     * @return Test
     */
    public function setDuration(float $duration): Test
    {
        $this->duration = $duration;

        return $this;
    }

    /**
     * Get duration in seconds.
     *
     * @return float
     */
    public function getDuration(): float
    {
        return $this->duration;
    }

    /**
     * Set system out message.
     *
     * @param string $systemOut The message
     *
     * @return Test
     */
    public function setSystemOut(string $systemOut): Test
    {
        $this->systemOut = $systemOut;

        return $this;
    }

    /**
     * Get system out message.
     *
     * @return string
     */
    public function getSystemOut(): string
    {
        return $this->systemOut;
    }

    /**
     * Set system error message.
     *
     * @param string $systemErr The message
     *
     * @return Test
     */
    public function setSystemErr(string $systemErr): Test
    {
        $this->systemErr = $systemErr;

        return $this;
    }

    /**
     * Get system error message.
     *
     * @return string
     */
    public function getSystemErr(): string
    {
        return $this->systemErr;
    }

    /**
     * Set order.
     *
     * @param int $position The order.
     *
     * @return Test
     */
    public function setPosition(int $position): Test
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Get position.
     *
     * @return int
     */
    public function getPosition(): int
    {
        return $this->position;
    }

    /**
     * Get reference id.
     *
     * @return int
     */
    public function getRefid(): int
    {
        return $this->position + 1;
    }

    /**
     * Set suite.
     *
     * @param Suite $suite
     *
     * @return Test
     */
    public function setSuite(Suite $suite): Test
    {
        $this->suite = $suite;

        return $this;
    }

    /**
     * Get suite.
     *
     * @return Suite
     */
    public function getSuite(): Suite
    {
        return $this->suite;
    }
}
