<?php

namespace AppBundle\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Suite
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
     * @var string Name
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
     * @var DateTime Date time of the suite
     *
     * @ORM\Column(name="datetime_suite", type="datetime")
     */
    protected $datetimeSuite;

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
     * @ORM\JoinColumn(name="campaign_id", referencedColumnName="id", nullable=false)
     */
    private $campaign;

    /**
     * Constructor
     *
     * @param Campaign $campaign
     */
    public function __construct($campaign)
    {
        $this->setCampaign($campaign);
    }

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
     * Set name
     *
     * @param string $name
     *
     * @return Suite
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
    
    /**
     * Set passed
     *
     * @param integer $passed
     *
     * @return Suite
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
     * @return Suite
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
     * @return Suite
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
     * @return Suite
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
     * @return Suite
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
     * @return Suite
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
     * Set datetime of suite.
     *
     * @param DateTime $datetime datetime of suite.
     *
     * @return Suite
     */
    public function setDatetimeSuite($datetime)
    {
        $this->datetimeSuite = $datetime;

        return $this;
    }

    /**
     * Get datetime of suite.
     *
     * @return DateTime
     */
    public function getDatetimeSuite()
    {
        return $this->datetimeSuite;
    }

    /**
     * Set order.
     *
     * @param int $position The order.
     *
     * @return Suite
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
     * Set campaign
     *
     * @param Campaign $campaign
     *
     * @return Campaign
     */
    public function setCampaign($campaign)
    {
        $this->campaign = $campaign;

        return $this;
    }

    /**
     * Get campaign
     *
     * @return Campaign
     */
    public function getCampaign()
    {
        return $this->campaign;
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

    /**
     * Get suite status
     *
     * @return int Status::FAILED|Status::WARNING|Status::SUCCESS
     */
    public function getStatus()
    {
        if ($this->getPercentage() < $this->campaign->getWarningLimit()) {
            return Status::FAILED;
        } elseif ($this->getPercentage() <  $this->campaign->getSuccessLimit()) {
            return Status::WARNING;
        } else {
            return Status::SUCCESS;
        }
    }
}
