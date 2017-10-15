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

use AppBundle\Entity\Test;
use JMS\Serializer\Annotation\Type;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Test data transfert object class.
 *
 * @category  ci-report app
 *
 * @author    Francois-Xavier Soubirou <soubirou@yahoo.fr>
 * @copyright 2017 Francois-Xavier Soubirou
 * @license   http://www.gnu.org/licenses/   GPLv3
 *
 * @see      https://www.ci-report.io
 */
class TestDTO
{
    /**
     * Name of the suite.
     *
     * @var string
     *
     * @Type("string")
     *
     * @Assert\NotBlank
     */
    private $name;

    /**
     * @var string
     *
     * @Type("string")
     */
    private $className = '';

    /**
     * @var string
     *
     * @Type("string")
     */
    private $package = '';

    /**
     * @var int
     *
     * @Assert\Type("integer")
     * @Assert\GreaterThanOrEqual(0)
     */
    private $status;

    /**
     * @var float
     *
     * @Type("float")
     *
     * @Assert\Type("float")
     * @Assert\GreaterThanOrEqual(0)
     */
    private $duration = 0;

    /**
     * @var string
     *
     * @Type("string")
     */
    private $errorFailSkipMessage = '';

    /**
     * @var string
     *
     * @Type("string")
     */
    private $systemOut = '';

    /**
     * @var string
     *
     * @Type("string")
     */
    private $systemErr = '';

    /**
     * Set name.
     *
     * @param string $name
     *
     * @return TestDTO
     */
    public function setName(string $name): TestDTO
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
     * @return TestDTO
     */
    public function setClassName(string $classname): TestDTO
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
     * @return TestDTO
     */
    public function setPackage(string $package): TestDTO
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
     * @return TestDTO
     */
    public function setFullClassName(string $fullClassName): TestDTO
    {
        if (substr_count($fullClassName, '.') > 0) {
            $index = strrpos($fullClassName, '.');
            $this->setPackage(substr($fullClassName, 0, $index));
            $this->setClassName(substr($fullClassName, $index + 1));
        } else {
            $this->setPackage(Test::DEFAULT_PACKAGE);
            $this->setClassName($fullClassName);
        }

        return $this;
    }

    /**
     * Set test status.
     *
     * @param int $status Status::SUCCESS|Status::FAILED|Status::ERROR|Status::SKIPPED
     *
     * @return TestDTO
     */
    public function setStatus(int $status): TestDTO
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Return test status.
     *
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * Set duration of the suite in second.
     *
     * @param float $duration Duration
     *
     * @return TestDTO
     */
    public function setDuration(float $duration): TestDTO
    {
        $this->duration = $duration;

        return $this;
    }

    /**
     * Get duration of the suite in seconds.
     *
     * @return float
     */
    public function getDuration(): float
    {
        return $this->duration;
    }

    /**
     * Set message when error, fail, skip test.
     *
     * @param string $message The message
     *
     * @return TestDTO
     */
    public function setErrorFailSkipMessage(string $message): TestDTO
    {
        $this->errorFailSkipMessage = $message;

        return $this;
    }

    /**
     * Get message set for errored, failed, skipped test.
     *
     * @return string
     */
    public function getErrorFailSkipMessage(): string
    {
        return $this->errorFailSkipMessage;
    }

    /**
     * Set system out message.
     *
     * @param string $systemOut The message
     *
     * @return TestDTO
     */
    public function setSystemOut(string $systemOut): TestDTO
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
     * @return TestDTO
     */
    public function setSystemErr(string $systemErr): TestDTO
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
}
