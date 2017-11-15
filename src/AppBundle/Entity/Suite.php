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

use AppBundle\DTO\SuiteDTO;
use AppBundle\DTO\SuiteLimitsDTO;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

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
    const DEFAULT_NAME = 'DEFAULT_SUITE_NAME';

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
     * Name of the suite.
     *
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=50)
     *
     * @Assert\NotBlank()
     *
     * @Serializer\Groups({"public", "private"})
     */
    private $name;

    /**
     * Tests warning limit.
     *
     * @var int
     *
     * @ORM\Column(name="warning", type="smallint")
     *
     * @Assert\NotBlank()
     * @Assert\Range(min=0, max=100)
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
     * @Assert\NotBlank()
     * @Assert\Range(min=0, max=100)
     *
     * @Serializer\Groups({"public", "private"})
     */
    private $success;

    /**
     * Total number of passed tests.
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
     * Total number of disabled tests.
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
     * Total number of errored tests.
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
     * Total number of skipped tests.
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
     * Total number of disabled tests.
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
     * Duration of the suite in seconds.
     *
     * @var float
     *
     * @ORM\Column(name="duration", type="float")
     *
     * @Assert\NotBlank()
     * @Assert\Type("float")
     *
     * @Serializer\Groups({"public", "private"})
     */
    private $duration = 0;

    /**
     * Date time of the suite in ISO 8601 format (2017-07-01T12:30:01+02:00).
     *
     * @var DateTime
     *
     * @ORM\Column(name="datetime_suite", type="datetime")
     *
     * @Assert\NotBlank()
     * @Assert\DateTime()
     *
     * @Serializer\Groups({"public", "private"})
     */
    protected $datetime;

    /**
     * @Gedmo\SortablePosition
     * @ORM\Column(name="position", type="integer")
     *
     * @Serializer\Exclude
     */
    private $position;

    /**
     * @var Campaign
     *
     * @Gedmo\SortableGroup
     * @ORM\ManyToOne(targetEntity="Campaign")
     * @ORM\JoinColumn(name="campaign_id", referencedColumnName="id", nullable=false, onDelete="cascade")
     *
     * @Serializer\Exclude
     */
    private $campaign;

    /**
     * Constructor.
     *
     * @param Project  $project
     * @param Campaign $campaign
     */
    public function __construct(Project $project, Campaign $campaign)
    {
        $this->setWarning($project->getWarning());
        $this->setSuccess($project->getSuccess());
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
     * Set warning limit.
     *
     * @param int $warning Warning limit
     *
     * @return Suite
     */
    public function setWarning(int $warning): self
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
     * @return Suite
     */
    public function setSuccess(int $success): self
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
     * @return Suite
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
     * @return Suite
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
     * @return Suite
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
     * @return Suite
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
     * @param int $disabled Disable tests
     *
     * @return Suite
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
     * Set duration of the suite in second.
     *
     * @param float $duration Duration
     *
     * @return Suite
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
     * Set datetime of suite.
     *
     * @param DateTime $datetime DateTime of suite.
     *
     * @return Suite
     */
    public function setDateTime(DateTime $datetime): self
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
     * Set campaign.
     *
     * @param Campaign $campaign Campaign
     *
     * @return Suite
     */
    public function setCampaign(Campaign $campaign): self
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
        if ($this->getPercentage() < $this->getWarning()) {
            return Status::FAILED;
        }
        if ($this->getPercentage() < $this->getSuccess()) {
            return Status::WARNING;
        }

        return Status::SUCCESS;
    }

    /**
     * Set from DTO limits suite.
     *
     * @param SuiteLimitsDTO $dto DTO object
     *
     * @return Suite
     */
    public function setFromLimitsDTO(SuiteLimitsDTO $dto): self
    {
        if (null !== $dto->getWarning()) {
            $this->setWarning($dto->getWarning());
        } else {
            $project = $this->getCampaign()->getProject();
            $this->setWarning($project->getWarning());
        }
        if (null !== $dto->getSuccess()) {
            $this->setSuccess($dto->getSuccess());
        } else {
            $project = $this->getCampaign()->getProject();
            $this->setSuccess($project->getSuccess());
        }

        return $this;
    }

    /**
     * Set from DTO suite.
     *
     * @param SuiteDTO $dto DTO object
     *
     * @return Suite
     */
    public function setFromDTO(SuiteDTO $dto): self
    {
        $this->setFromLimitsDTO($dto);

        $this->setName($dto->getName());
        $this->setDisabled($dto->getDisabled());
        $this->setDuration($dto->getDuration());
        $this->setDateTime($dto->getDatetime());

        return $this;
    }
}
