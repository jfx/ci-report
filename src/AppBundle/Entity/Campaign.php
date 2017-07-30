<?php

namespace AppBundle\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Campaign.
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
     * @var int warningLimit
     *
     * @ORM\Column(name="warning_limit", type="smallint")
     */
    private $warningLimit;

    /**
     * @var int successLimit
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
     * @var DateTime Date time of the campaign
     *
     * @ORM\Column(name="datetime_campaign", type="datetime")
     */
    protected $datetimeCampaign;

    /**
     * @Gedmo\SortablePosition
     * @ORM\Column(name="position", type="integer")
     */
    private $position;

    /**
     * @var Project
     *
     * @Gedmo\SortableGroup
     * @ORM\ManyToOne(targetEntity="Project")
     * @ORM\JoinColumn(name="project_id", referencedColumnName="id", nullable=false)
     */
    private $project;

    /**
     * Constructor.
     *
     * @param Project $project
     */
    public function __construct($project)
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
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set warning limit.
     *
     * @param int $warningLimit
     *
     * @return Project
     */
    public function setWarningLimit($warningLimit)
    {
        $this->warningLimit = $warningLimit;

        return $this;
    }

    /**
     * Get warning limit.
     *
     * @return int
     */
    public function getWarningLimit()
    {
        return $this->warningLimit;
    }

    /**
     * Set success limit.
     *
     * @param int $successLimit
     *
     * @return Project
     */
    public function setSuccessLimit($successLimit)
    {
        $this->successLimit = $successLimit;

        return $this;
    }

    /**
     * Get success limit.
     *
     * @return int
     */
    public function getSuccessLimit()
    {
        return $this->successLimit;
    }

    /**
     * Set passed.
     *
     * @param int $passed
     *
     * @return Campaign
     */
    public function setpassed($passed)
    {
        $this->passed = $passed;

        return $this;
    }

    /**
     * Get passed.
     *
     * @return int
     */
    public function getPassed()
    {
        return $this->passed;
    }

    /**
     * Set failed.
     *
     * @param int $failed
     *
     * @return Campaign
     */
    public function setFailed($failed)
    {
        $this->failed = $failed;

        return $this;
    }

    /**
     * Get failed.
     *
     * @return int
     */
    public function getFailed()
    {
        return $this->failed;
    }

    /**
     * Set errored.
     *
     * @param int $errored
     *
     * @return Campaign
     */
    public function setErrored($errored)
    {
        $this->errored = $errored;

        return $this;
    }

    /**
     * Get errored.
     *
     * @return int
     */
    public function getErrored()
    {
        return $this->errored;
    }

    /**
     * Set skipped.
     *
     * @param int $skipped
     *
     * @return Campaign
     */
    public function setSkipped($skipped)
    {
        $this->skipped = $skipped;

        return $this;
    }

    /**
     * Get skipped.
     *
     * @return int
     */
    public function getSkipped()
    {
        return $this->skipped;
    }

    /**
     * Set disabled.
     *
     * @param int $disabled
     *
     * @return Campaign
     */
    public function setDisabled($disabled)
    {
        $this->disabled = $disabled;

        return $this;
    }

    /**
     * Get disabled.
     *
     * @return int
     */
    public function getDisabled()
    {
        return $this->disabled;
    }

    /**
     * Set duration.
     *
     * @param float $duration
     *
     * @return Campaign
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;

        return $this;
    }

    /**
     * Get duration.
     *
     * @return float
     */
    public function getDuration()
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
    public function setDatetimeCampaign($datetime)
    {
        $this->datetimeCampaign = $datetime;

        return $this;
    }

    /**
     * Get datetime of campaign.
     *
     * @return DateTime
     */
    public function getDatetimeCampaign()
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
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Get position.
     *
     * @return int
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Get reference id.
     *
     * @return int
     */
    public function getRefId()
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
    public function setProject($project)
    {
        $this->project = $project;

        return $this;
    }

    /**
     * Get project.
     *
     * @return Project
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * Get enabled tests.
     *
     * @return int
     */
    public function getEnabled()
    {
        return $this->passed
            + $this->failed
            + $this->errored
            + $this->skipped;
    }

    /**
     * Get percentage of successful tests.
     *
     * @return int
     */
    public function getPercentage()
    {
        if ($this->getEnabled() != 0) {
            return round($this->passed / $this->getEnabled() * 100);
        } else {
            0;
        }
    }

    /**
     * Get campaign status.
     *
     * @return int Status::FAILED|Status::WARNING|Status::SUCCESS
     */
    public function getStatus()
    {
        if ($this->getPercentage() < $this->getWarningLimit()) {
            return Status::FAILED;
        } elseif ($this->getPercentage() < $this->getSuccessLimit()) {
            return Status::WARNING;
        } else {
            return Status::SUCCESS;
        }
    }
}
