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

use AppBundle\DTO\CampaignDTO;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

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
     * Total number of passed tests from all testsuites.
     *
     * @var int
     *
     * @ORM\Column(name="passed", type="integer")
     *
     * @Assert\NotBlank()
     * @Assert\Type("integer")
     *
     * @Serializer\Groups({"public", "private"})
     */
    private $passed = 0;

    /**
     * Total number of failed tests from all testsuites.
     *
     * @var int
     *
     * @ORM\Column(name="failed", type="integer")
     *
     * @Assert\NotBlank()
     * @Assert\Type("integer")
     *
     * @Serializer\Groups({"public", "private"})
     */
    private $failed = 0;

    /**
     * Total number of errored tests from all testsuites.
     *
     * @var int
     *
     * @ORM\Column(name="errored", type="integer")
     *
     * @Assert\NotBlank()
     * @Assert\Type("integer")
     *
     * @Serializer\Groups({"public", "private"})
     */
    private $errored = 0;

    /**
     * Total number of skipped tests from all testsuites.
     *
     * @var int
     *
     * @ORM\Column(name="skipped", type="integer")
     *
     * @Assert\NotBlank()
     * @Assert\Type("integer")
     *
     * @Serializer\Groups({"public", "private"})
     */
    private $skipped = 0;

    /**
     * Total number of disabled tests from all testsuites.
     *
     * @var int
     *
     * @ORM\Column(name="disabled", type="integer")
     *
     * @Assert\NotBlank()
     * @Assert\Type("integer")
     *
     * @Serializer\Groups({"public", "private"})
     */
    private $disabled = 0;

    /**
     * Status of campaign defined by lowest status of all suites. If no suite, status is warning.
     *
     * @var int
     *
     * @ORM\Column(name="status", type="smallint")
     *
     * @Assert\NotBlank()
     * @Assert\Type("integer")
     *
     * @Serializer\Groups({"public", "private"})
     */
    private $status = 16;

    /**
     * Start Date time of the campaign in ISO 8601 format (2017-07-01T12:30:01+02:00).
     *
     * @var DateTime
     *
     * @ORM\Column(name="start", type="datetime")
     *
     * @Assert\NotBlank()
     * @Assert\DateTime()
     *
     * @Serializer\Groups({"public", "private"})
     */
    protected $start;

    /**
     * End Date time of the campaign in ISO 8601 format (2017-07-01T12:30:01+02:00). Returned if not null.
     *
     * @var DateTime
     *
     * @ORM\Column(name="end", type="datetime", nullable=true)
     *
     * @Assert\DateTime()
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
        $this->setStart(new DateTime());
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
     * Set passed tests count.
     *
     * @param int $passed Passed tests
     *
     * @return Campaign
     */
    public function setpassed(int $passed): self
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
    public function setFailed(int $failed): self
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
    public function setErrored(int $errored): self
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
    public function setSkipped(int $skipped): self
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
    public function setDisabled(int $disabled): self
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
     * Set status.
     *
     * @param int $status Status
     *
     * @return Campaign
     */
    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status of campaign.
     *
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * Set start datetime of campaign.
     *
     * @param DateTime $datetime start datetime of campaign.
     *
     * @return Campaign
     */
    public function setStart(DateTime $datetime): self
    {
        $this->start = $datetime;

        return $this;
    }

    /**
     * Get start datetime of campaign.
     *
     * @return DateTime
     */
    public function getStart(): DateTime
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
    public function setEnd(DateTime $datetime): self
    {
        $this->end = $datetime;

        return $this;
    }

    /**
     * Get end datetime of campaign.
     *
     * @return DateTime
     */
    public function getEnd(): ?DateTime
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
    public function setProject(Project $project): self
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
        if (0 !== $this->getEnabled()) {
            return round($this->passed / $this->getEnabled() * 100);
        }

        return 0;
    }

    /**
     * Set from DTO campaign.
     *
     * @param CampaignDTO $dto DTO object
     *
     * @return Campaign
     */
    public function setFromDTO(CampaignDTO $dto): self
    {
        if (null !== $dto->getStart()) {
            $this->setStart($dto->getStart());
        }
        if (null !== $dto->getEnd()) {
            $this->setEnd($dto->getEnd());
        }

        return $this;
    }
}
