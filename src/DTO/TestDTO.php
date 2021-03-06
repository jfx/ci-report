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

namespace App\DTO;

use App\Entity\Test;
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
    private $classname = '';

    /**
     * @var string
     *
     * @Type("string")
     */
    private $package = '';

    /**
     * @var string
     *
     * @Assert\Type("string")
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
    private $systemout = '';

    /**
     * @var string
     *
     * @Type("string")
     */
    private $systemerr = '';

    /**
     * @var string
     *
     * @Type("string")
     */
    private $failuremsg = '';

    /**
     * Set name.
     *
     * @param string $name
     *
     * @return TestDTO
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
     * @return TestDTO
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
     * @return TestDTO
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
     * @param string $fullclassname The full class name
     *
     * @return TestDTO
     */
    public function setFullclassname(string $fullclassname): self
    {
        if (substr_count($fullclassname, '.') > 0) {
            $index = strrpos($fullclassname, '.');
            $this->setPackage(substr($fullclassname, 0, $index));
            $this->setClassName(substr($fullclassname, $index + 1));
        } else {
            $this->setPackage(Test::DEFAULT_PACKAGE);
            $this->setClassName($fullclassname);
        }

        return $this;
    }

    /**
     * Set test status.
     *
     * @param string $status Test::PASSED|Test::FAILED|Test::ERRORED|Test::SKIPPED
     *
     * @return TestDTO
     */
    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Return test status.
     *
     * @return string
     */
    public function getStatus(): string
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
    public function setDuration(float $duration): self
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
     * Set system out message.
     *
     * @param string $systemout The message
     *
     * @return TestDTO
     */
    public function setSystemout(string $systemout): self
    {
        $this->systemout = $systemout;

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
     * @param string $systemerr The message
     *
     * @return TestDTO
     */
    public function setSystemerr(string $systemerr): self
    {
        $this->systemerr = $systemerr;

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
     * Set message when error, fail, skip test.
     *
     * @param string $message The message
     *
     * @return TestDTO
     */
    public function setFailuremsg(string $message): self
    {
        $this->failuremsg = $message;

        return $this;
    }

    /**
     * Get message set for errored, failed, skipped test.
     *
     * @return string
     */
    public function getFailuremsg(): string
    {
        return $this->failuremsg;
    }
}
