<?php

namespace AppBundle\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * Campaign
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
     */
    private $id;

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
     * @var Project
     *
     * @ORM\ManyToOne(targetEntity="Project")
     * @ORM\JoinColumn(name="project_id", referencedColumnName="id", nullable=false)
     */
    private $project;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set passed
     *
     * @param integer $passed
     *
     * @return Campaign
     */
    public function setpassed($passed)
    {
        $this->passed = $passed;

        return $this;
    }

    /**
     * Get passed
     *
     * @return int
     */
    public function getPassed()
    {
        return $this->passed;
    }

    /**
     * Set failed
     *
     * @param integer $failed
     *
     * @return Campaign
     */
    public function setFailed($failed)
    {
        $this->failed = $failed;

        return $this;
    }

    /**
     * Get failed
     *
     * @return int
     */
    public function getFailed()
    {
        return $this->failed;
    }

    /**
     * Set errored
     *
     * @param integer $errored
     *
     * @return Campaign
     */
    public function setErrored($errored)
    {
        $this->errored = $errored;

        return $this;
    }

    /**
     * Get errored
     *
     * @return int
     */
    public function getErrored()
    {
        return $this->errored;
    }

    /**
     * Set skipped
     *
     * @param integer $skipped
     *
     * @return Campaign
     */
    public function setSkipped($skipped)
    {
        $this->skipped = $skipped;

        return $this;
    }

    /**
     * Get skipped
     *
     * @return int
     */
    public function getSkipped()
    {
        return $this->skipped;
    }

    /**
     * Set disabled
     *
     * @param integer $disabled
     *
     * @return Campaign
     */
    public function setDisabled($disabled)
    {
        $this->disabled = $disabled;

        return $this;
    }

    /**
     * Get disabled
     *
     * @return int
     */
    public function getDisabled()
    {
        return $this->disabled;
    }

    /**
     * Set duration
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
     * Get duration
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
     * Set project
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
     * Get project
     *
     * @return Project
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * Get enabled tests
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
     * Get percentage of successful tests
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
}
