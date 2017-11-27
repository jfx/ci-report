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

use AppBundle\DTO\TestDTO;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

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
    const DEFAULT_NAME = 'DEFAULT_NAME';
    const DEFAULT_CLASSNAME = 'DEFAULT_CLASSNAME';
    const DEFAULT_PACKAGE = '_ROOT_';

    const PASSED = 'Passed';
    const FAILED = 'Failed';
    const ERRORED = 'Errored';
    const SKIPPED = 'Skipped';

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @Serializer\Exclude
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=256)
     *
     * @Assert\NotBlank
     *
     * @Serializer\Groups({"public", "private"})
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="classname", type="string", length=256)
     *
     * @Serializer\Groups({"public", "private"})
     */
    private $classname;

    /**
     * @var string
     *
     * @ORM\Column(name="package", type="string", length=256)
     *
     * @Serializer\Groups({"public", "private"})
     */
    private $package;

    /**
     * @var int
     *
     * @ORM\Column(name="passed", type="smallint")
     *
     * @Assert\Type("integer")
     * @Assert\GreaterThanOrEqual(0)
     *
     * @Serializer\Exclude
     */
    private $passed;

    /**
     * @var int
     *
     * @ORM\Column(name="failed", type="smallint")
     *
     * @Assert\Type("integer")
     * @Assert\GreaterThanOrEqual(0)
     *
     * @Serializer\Exclude
     */
    private $failed;

    /**
     * @var int
     *
     * @ORM\Column(name="errored", type="smallint")
     *
     * @Assert\Type("integer")
     * @Assert\GreaterThanOrEqual(0)
     *
     * @Serializer\Exclude
     */
    private $errored;

    /**
     * @var int
     *
     * @ORM\Column(name="skipped", type="smallint")
     *
     * @Assert\Type("integer")
     * @Assert\GreaterThanOrEqual(0)
     *
     * @Serializer\Exclude
     */
    private $skipped;

    /**
     * @var float
     *
     * @ORM\Column(name="duration", type="float")
     *
     * @Assert\Type("float")
     * @Assert\GreaterThanOrEqual(0)
     *
     * @Serializer\Groups({"public", "private"})
     */
    private $duration;

    /**
     * @var string
     *
     * @ORM\Column(name="system_out", type="text")
     *
     * @Serializer\Groups({"public", "private"})
     */
    private $systemout = '';

    /**
     * @var string
     *
     * @ORM\Column(name="system_err", type="text")
     *
     * @Serializer\Groups({"public", "private"})
     */
    private $systemerr = '';

    /**
     * @var string
     *
     * @ORM\Column(name="failure_msg", type="text")
     *
     * @Serializer\Groups({"public", "private"})
     */
    private $failuremsg = '';

    /**
     * @Gedmo\SortablePosition
     *
     * @ORM\Column(name="position", type="integer"))
     */
    private $position;

    /**
     * @var Suite
     *
     * @Gedmo\SortableGroup
     *
     * @ORM\ManyToOne(targetEntity="Suite")
     * @ORM\JoinColumn(name="suite_id", referencedColumnName="id", nullable=false, onDelete="cascade")
     *
     * @Serializer\Exclude
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
    public function setName(string $name): self
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
    public function setClassname(string $classname): self
    {
        $this->classname = $classname;

        return $this;
    }

    /**
     * Get class name.
     *
     * @return string
     */
    public function getClassname(): string
    {
        return $this->classname;
    }

    /**
     * Set package.
     *
     * @param string $package Package of the test
     *
     * @return Test
     */
    public function setPackage(string $package): self
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
     * @param string $fullClassname The full class name
     *
     * @return Test
     */
    public function setFullclassname(string $fullClassname): self
    {
        if (substr_count($fullClassname, '.') > 0) {
            $index = strrpos($fullClassname, '.');
            $this->setPackage(substr($fullClassname, 0, $index));
            $this->setClassname(substr($fullClassname, $index + 1));
        } else {
            $this->setPackage(self::DEFAULT_PACKAGE);
            $this->setClassname($fullClassname);
        }

        return $this;
    }

    /**
     * Set test to passed.
     *
     * @return Test
     */
    public function setPassed(): self
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
    public function setFailed(): self
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
    public function setErrored(): self
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
    public function setSkipped(): self
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
     * @param string $status Status (const of Test class)
     *
     * @return Test
     */
    public function setStatus(string $status): self
    {
        switch ($status) {
            case self::PASSED:
                $this->setPassed();
                break;
            case self::FAILED:
                $this->setFailed();
                break;
            case self::ERRORED:
                $this->setErrored();
                break;
            case self::SKIPPED:
                $this->setSkipped();
                break;
        }

        return $this;
    }

    /**
     * Return  status (const of Test class).
     *
     * @return string
     */
    public function getStatus(): string
    {
        if ($this->passed > 0) {
            return self::PASSED;
        }
        if ($this->failed > 0) {
            return self::FAILED;
        }
        if ($this->skipped > 0) {
            return self::SKIPPED;
        }

        return self::ERRORED;
    }

    /**
     * Set duration in seconds.
     *
     * @param float $duration Duration
     *
     * @return Test
     */
    public function setDuration(float $duration): self
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
    public function setSystemout(string $systemOut): self
    {
        $this->systemout = $systemOut;

        return $this;
    }

    /**
     * Get system out message.
     *
     * @return string
     */
    public function getSystemout(): string
    {
        return $this->systemout;
    }

    /**
     * Set system error message.
     *
     * @param string $systemErr The message
     *
     * @return Test
     */
    public function setSystemerr(string $systemErr): self
    {
        $this->systemerr = $systemErr;

        return $this;
    }

    /**
     * Get system error message.
     *
     * @return string
     */
    public function getSystemerr(): string
    {
        return $this->systemerr;
    }

    /**
     * Set failure message.
     *
     * @param string $failureMsg The message
     *
     * @return Test
     */
    public function setFailuremsg(string $failureMsg): self
    {
        $this->failuremsg = $failureMsg;

        return $this;
    }

    /**
     * Get failure message.
     *
     * @return string
     */
    public function getFailuremsg(): string
    {
        return $this->failuremsg;
    }

    /**
     * Set order.
     *
     * @param int $position The order.
     *
     * @return Test
     */
    public function setPosition(int $position): self
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
     *
     * @Serializer\VirtualProperty
     * @Serializer\SerializedName("refid")
     * @Serializer\Type("int")
     * @Serializer\Groups({"public", "private"})
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
    public function setSuite(Suite $suite): self
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

    /**
     * Set from DTO test.
     *
     * @param TestDTO $dto DTO object
     *
     * @return Test
     */
    public function setFromDTO(TestDTO $dto): self
    {
        $this->setName($dto->getName());
        $this->setClassname($dto->getClassname());
        $this->setPackage($dto->getPackage());
        $this->setStatus($dto->getStatus());
        $this->setDuration($dto->getDuration());
        $this->setSystemout($dto->getSystemout());
        $this->setSystemerr($dto->getSystemerr());
        $this->setFailuremsg($dto->getFailuremsg());

        return $this;
    }
}
