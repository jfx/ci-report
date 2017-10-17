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

use DateTime;
use JMS\Serializer\Annotation\Type;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Suite data transfert object class.
 *
 * @category  ci-report app
 *
 * @author    Francois-Xavier Soubirou <soubirou@yahoo.fr>
 * @copyright 2017 Francois-Xavier Soubirou
 * @license   http://www.gnu.org/licenses/   GPLv3
 *
 * @see      https://www.ci-report.io
 */
class SuiteDTO
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
     * Number of disabled tests.
     *
     * @var int
     *
     * @Type("integer")
     *
     * @Assert\Type("integer")
     * @Assert\GreaterThanOrEqual(0)
     */
    private $disabled = 0;

    /**
     * Duration of the suite in seconds.
     *
     * @var float
     *
     * @Type("float")
     *
     * @Assert\Type("float")
     * @Assert\GreaterThanOrEqual(0)
     */
    private $duration = 0;

    /**
     * Date time of the suite in format (2017-07-01T12:30:01). Now by default.
     *
     * @var DateTime
     *
     * @Type("DateTime<'Y-m-d\TH:i:s'>")
     *
     * @Assert\DateTime()
     */
    private $datetime;

    /**
     * Set name.
     *
     * @param string $name
     *
     * @return SuiteDTO
     */
    public function setName(string $name): SuiteDTO
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
     * Set disabled tests count.
     *
     * @param int $disabled Disable tests
     *
     * @return SuiteDTO
     */
    public function setDisabled(int $disabled): SuiteDTO
    {
        $this->disabled = $disabled;

        return $this;
    }

    /**
     * Get disabled tests count.
     *
     * @return int
     */
    public function getDisabled(): int
    {
        return $this->disabled;
    }

    /**
     * Set duration of the suite in second.
     *
     * @param float $duration Duration
     *
     * @return SuiteDTO
     */
    public function setDuration(float $duration): SuiteDTO
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
     * Set datetime of suite.
     *
     * @param DateTime $datetime Datetime of suite.
     *
     * @return SuiteDTO
     */
    public function setDatetime(DateTime $datetime): SuiteDTO
    {
        $this->datetime = $datetime;

        return $this;
    }

    /**
     * Get datetime of suite.
     *
     * @return DateTime
     */
    public function getDatetime(): ?DateTime
    {
        return $this->datetime;
    }
}
