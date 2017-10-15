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

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Suite entity class.
 *
 * @category  ci-report app
 *
 * @author    Francois-Xavier Soubirou <soubirou@yahoo.fr>
 * @copyright 2017 Francois-Xavier Soubirou
 * @license   http://www.gnu.org/licenses/   GPLv3
 *
 * @see      https://www.ci-report.io
 *
 * @ORM\Table(name="cir_suite")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\SuiteRepository")
 */
class Suite
{
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
     * @ORM\Column(name="name", type="string", length=50)
     */
    private $name;

    /**
     * @var int
     *
     * @ORM\Column(name="passed", type="integer")
     */
    private $passed;

    /**
     * @var int
     *
     * @ORM\Column(name="failed", type="integer")
     */
    private $failed;

    /**
     * @var int
     *
     * @ORM\Column(name="errored", type="integer")
     */
    private $errored;

    /**
     * @var int
     *
     * @ORM\Column(name="skipped", type="integer")
     */
    private $skipped;

    /**
     * @var int
     *
     * @ORM\Column(name="disabled", type="integer")
     */
    private $disabled;

    /**
     * @var float
     *
     * @ORM\Column(name="duration", type="float")
     */
    private $duration;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="datetime_suite", type="datetime")
     */
    protected $datetime;

    /**
     * @Gedmo\SortablePosition
     * @ORM\Column(name="position", type="integer")
     */
    private $position;

    /**
     * @var Campaign
     *
     * @Gedmo\SortableGroup
     * @ORM\ManyToOne(targetEntity="Campaign")
     * @ORM\JoinColumn(name="campaign_id", referencedColumnName="id", nullable=false, onDelete="cascade")
     */
    private $campaign;

    /**
     * Constructor.
     *
     * @param Campaign $campaign
     */
    public function __construct(Campaign $campaign)
    {
        $this->setCampaign($campaign);
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
     * @return Suite
     */
    public function setName(string $name): Suite
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
     * Set passed tests count.
     *
     * @param int $passed Passed tests
     *
     * @return Suite
     */
    public function setpassed(int $passed): Suite
    {
        $this->passed = $passed;

        return $this;
    }

    /**
     * Get passed tests count.
     *
     * @return int
     */
    public function getPassed(): int
    {
        return $this->passed;
    }

    /**
     * Set failed tests count.
     *
     * @param int $failed Failed tests
     *
     * @return Suite
     */
    public function setFailed(int $failed): Suite
    {
        $this->failed = $failed;

        return $this;
    }

    /**
     * Get failed tests count.
     *
     * @return int
     */
    public function getFailed(): int
    {
        return $this->failed;
    }

    /**
     * Set errored tests count.
     *
     * @param int $errored Errored tests
     *
     * @return Suite
     */
    public function setErrored(int $errored): Suite
    {
        $this->errored = $errored;

        return $this;
    }

    /**
     * Get errored tests count.
     *
     * @return int
     */
    public function getErrored(): int
    {
        return $this->errored;
    }

    /**
     * Set skipped tests count.
     *
     * @param int $skipped Skipped tests
     *
     * @return Suite
     */
    public function setSkipped(int $skipped): Suite
    {
        $this->skipped = $skipped;

        return $this;
    }

    /**
     * Get skipped tests count.
     *
     * @return int
     */
    public function getSkipped(): int
    {
        return $this->skipped;
    }

    /**
     * Set disabled tests count.
     *
     * @param int $disabled Disable tests
     *
     * @return Suite
     */
    public function setDisabled(int $disabled): Suite
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
     * @return Suite
     */
    public function setDuration(float $duration): Suite
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
     * @param DateTime $datetime DateTime of suite.
     *
     * @return Suite
     */
    public function setDateTime(DateTime $datetime): Suite
    {
        $this->datetime = $datetime;

        return $this;
    }

    /**
     * Get datetime of suite.
     *
     * @return DateTime
     */
    public function getDateTime(): DateTime
    {
        return $this->datetime;
    }

    /**
     * Set order.
     *
     * @param int $position The order.
     *
     * @return Suite
     */
    public function setPosition(int $position): Suite
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
     * Set campaign.
     *
     * @param Campaign $campaign Campaign
     *
     * @return Suite
     */
    public function setCampaign(Campaign $campaign): Suite
    {
        $this->campaign = $campaign;

        return $this;
    }

    /**
     * Get campaign.
     *
     * @return Campaign
     */
    public function getCampaign(): Campaign
    {
        return $this->campaign;
    }

    /**
     * Get enabled tests.
     *
     * @return int
     */
    public function getEnabled(): int
    {
        return $this->passed
            + $this->failed
            + $this->errored
            + $this->skipped;
    }

    /**
     * Get percentage of successful tests.
     *
     * @return float
     */
    public function getPercentage(): float
    {
        if ($this->getEnabled() > 0) {
            return round($this->passed / $this->getEnabled() * 100);
        }

        return 0;
    }

    /**
     * Get suite status.
     *
     * @return int Status::FAILED|Status::WARNING|Status::SUCCESS
     */
    public function getStatus(): int
    {
        if ($this->getPercentage() < $this->campaign->getWarning()) {
            return Status::FAILED;
        }
        if ($this->getPercentage() < $this->campaign->getSuccess()) {
            return Status::WARNING;
        }

        return Status::SUCCESS;
    }
}
