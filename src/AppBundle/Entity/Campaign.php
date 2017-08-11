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
 * Campaign entity class.
 *
 * @category  ci-report app
 *
 * @author    Francois-Xavier Soubirou <soubirou@yahoo.fr>
 * @copyright 2017 Francois-Xavier Soubirou
 * @license   http://www.gnu.org/licenses/   GPLv3
 *
 * @see      https://ci-report.io
 *
 * @ORM\Table(name="cir_campaign")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CampaignRepository")
 */
class Campaign
{
    const DEFAULT_WARNING_LIMIT = 80;
    const DEFAULT_SUCCESS_LIMIT = 95;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="warning_limit", type="smallint")
     */
    private $warningLimit;

    /**
     * @var int
     *
     * @ORM\Column(name="success_limit", type="smallint")
     */
    private $successLimit;

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
     * @ORM\Column(name="datetime_campaign", type="datetime")
     */
    protected $datetimeCampaign;

    /**
     * @Gedmo\SortablePosition
     *
     * @ORM\Column(name="position", type="integer")
     */
    private $position;

    /**
     * @var Project
     *
     * @Gedmo\SortableGroup
     *
     * @ORM\ManyToOne(targetEntity="Project")
     * @ORM\JoinColumn(name="project_id", referencedColumnName="id", nullable=false)
     */
    private $project;

    /**
     * Constructor.
     *
     * @param Project $project
     */
    public function __construct(Project $project)
    {
        $this->setWarningLimit($project->getWarningLimit());
        $this->setSuccessLimit($project->getSuccessLimit());
        $this->setProject($project);
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
     * Set warning limit.
     *
     * @param int $warningLimit Warning limit
     *
     * @return Campaign
     */
    public function setWarningLimit(int $warningLimit): Campaign
    {
        $this->warningLimit = $warningLimit;

        return $this;
    }

    /**
     * Get warning limit.
     *
     * @return int
     */
    public function getWarningLimit(): int
    {
        return $this->warningLimit;
    }

    /**
     * Set success limit.
     *
     * @param int $successLimit Success limit
     *
     * @return Campaign
     */
    public function setSuccessLimit(int $successLimit): Campaign
    {
        $this->successLimit = $successLimit;

        return $this;
    }

    /**
     * Get success limit.
     *
     * @return int
     */
    public function getSuccessLimit(): int
    {
        return $this->successLimit;
    }

    /**
     * Set passed tests count.
     *
     * @param int $passed Passed tests
     *
     * @return Campaign
     */
    public function setpassed(int $passed): Campaign
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
     * @return Campaign
     */
    public function setFailed(int $failed): Campaign
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
     * @return Campaign
     */
    public function setErrored(int $errored): Campaign
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
     * @return Campaign
     */
    public function setSkipped(int $skipped): Campaign
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
     * @param int $disabled Disabled tests
     *
     * @return Campaign
     */
    public function setDisabled(int $disabled): Campaign
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
     * Set duration of campaign in seconds.
     *
     * @param float $duration Duration
     *
     * @return Campaign
     */
    public function setDuration(float $duration): Campaign
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
     * Set datetime of campaign.
     *
     * @param DateTime $datetime datetime of campaign.
     *
     * @return Campaign
     */
    public function setDatetimeCampaign(DateTime $datetime): Campaign
    {
        $this->datetimeCampaign = $datetime;

        return $this;
    }

    /**
     * Get datetime of campaign.
     *
     * @return DateTime
     */
    public function getDatetimeCampaign(): Datetime
    {
        return $this->datetimeCampaign;
    }

    /**
     * Set order.
     *
     * @param int $position The order.
     *
     * @return Campaign
     */
    public function setPosition(int $position): Campaign
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
    public function getRefId(): int
    {
        return $this->position + 1;
    }

    /**
     * Set project.
     *
     * @param Project $project
     *
     * @return Campaign
     */
    public function setProject(Project $project): Campaign
    {
        $this->project = $project;

        return $this;
    }

    /**
     * Get project.
     *
     * @return Project
     */
    public function getProject(): Project
    {
        return $this->project;
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
        if ($this->getEnabled() !== 0) {
            return round($this->passed / $this->getEnabled() * 100);
        }

        return 0;
    }

    /**
     * Get campaign status.
     *
     * @return int Status::FAILED|Status::WARNING|Status::SUCCESS
     */
    public function getStatus(): int
    {
        if ($this->getPercentage() < $this->getWarningLimit()) {
            return Status::FAILED;
        }
        if ($this->getPercentage() < $this->getSuccessLimit()) {
            return Status::WARNING;
        }

        return Status::SUCCESS;
    }
}
