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
use JMS\Serializer\Annotation as Serializer;

/**
 * Campaign entity class.
 *
 * @category  ci-report app
 *
 * @author    Francois-Xavier Soubirou <soubirou@yahoo.fr>
 * @copyright 2017 Francois-Xavier Soubirou
 * @license   http://www.gnu.org/licenses/   GPLv3
 *
 * @see      https://www.ci-report.io
 *
 * @ORM\Table(name="cir_campaign")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CampaignRepository")
 */
class Campaign
{
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
     * Tests warning limit.
     *
     * @var int
     *
     * @ORM\Column(name="warning", type="smallint")
     *
     * @Serializer\Groups({"public", "private"})
     */
    private $warning;

    /**
     * Tests success limit.
     *
     * @var int
     *
     * @ORM\Column(name="success", type="smallint")
     *
     * @Serializer\Groups({"public", "private"})
     */
    private $success;

    /**
     * Total number of passed tests from all testsuites.
     *
     * @var int
     *
     * @ORM\Column(name="passed", type="integer")
     *
     * @Serializer\Groups({"public", "private"})
     */
    private $passed;

    /**
     * Total number of failed tests from all testsuites.
     *
     * @var int
     *
     * @ORM\Column(name="failed", type="integer")
     *
     * @Serializer\Groups({"public", "private"})
     */
    private $failed;

    /**
     * Total number of errored tests from all testsuites.
     *
     * @var int
     *
     * @ORM\Column(name="errored", type="integer")
     *
     * @Serializer\Groups({"public", "private"})
     */
    private $errored;

    /**
     * Total number of skipped tests from all testsuites.
     *
     * @var int
     *
     * @ORM\Column(name="skipped", type="integer")
     *
     * @Serializer\Groups({"public", "private"})
     */
    private $skipped;

    /**
     * Total number of disabled tests from all testsuites.
     *
     * @var int
     *
     * @ORM\Column(name="disabled", type="integer")
     *
     * @Serializer\Groups({"public", "private"})
     */
    private $disabled;

    /**
     * Start Date time of the campaign in ISO 8601 format (2017-07-01T12:30:01+02:00).
     *
     * @var DateTime
     *
     * @ORM\Column(name="start", type="datetime")
     *
     * @Serializer\Groups({"public", "private"})
     */
    protected $start;

    /**
     * End Date time of the campaign in ISO 8601 format (2017-07-01T12:30:01+02:00).
     *
     * @var DateTime
     *
     * @ORM\Column(name="end", type="datetime", nullable=true)
     *
     * @Serializer\Groups({"public", "private"})
     */
    protected $end;

    /**
     * @Gedmo\SortablePosition
     *
     * @ORM\Column(name="position", type="integer")
     * @Serializer\Exclude
     */
    private $position;

    /**
     * @var Project
     *
     * @Gedmo\SortableGroup
     *
     * @ORM\ManyToOne(targetEntity="Project")
     * @ORM\JoinColumn(name="project_id", referencedColumnName="id", nullable=false, onDelete="cascade")
     *
     * @Serializer\Exclude
     */
    private $project;

    /**
     * Constructor.
     *
     * @param Project $project
     */
    public function __construct(Project $project)
    {
        $this->setWarning($project->getWarning());
        $this->setSuccess($project->getSuccess());
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
     * @param int $warning Warning limit
     *
     * @return Campaign
     */
    public function setWarning(int $warning): Campaign
    {
        $this->warning = $warning;

        return $this;
    }

    /**
     * Get warning limit.
     *
     * @return int
     */
    public function getWarning(): int
    {
        return $this->warning;
    }

    /**
     * Set success limit.
     *
     * @param int $success Success limit
     *
     * @return Campaign
     */
    public function setSuccess(int $success): Campaign
    {
        $this->success = $success;

        return $this;
    }

    /**
     * Get success limit.
     *
     * @return int
     */
    public function getSuccess(): int
    {
        return $this->success;
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
     * Set start datetime of campaign.
     *
     * @param DateTime $datetime start datetime of campaign.
     *
     * @return Campaign
     */
    public function setStart(DateTime $datetime): Campaign
    {
        $this->start = $datetime;

        return $this;
    }

    /**
     * Get start datetime of campaign.
     *
     * @return DateTime
     */
    public function getStart(): Datetime
    {
        return $this->start;
    }

    /**
     * Set end datetime of campaign.
     *
     * @param DateTime $datetime end datetime of campaign.
     *
     * @return Campaign
     */
    public function setEnd(DateTime $datetime): Campaign
    {
        $this->end = $datetime;

        return $this;
    }

    /**
     * Get end datetime of campaign.
     *
     * @return DateTime
     */
    public function getEnd(): ?Datetime
    {
        return $this->end;
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
     * Get reference id (Incremental integer).
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
        if ($this->getPercentage() < $this->getWarning()) {
            return Status::FAILED;
        }
        if ($this->getPercentage() < $this->getSuccess()) {
            return Status::WARNING;
        }

        return Status::SUCCESS;
    }
}
