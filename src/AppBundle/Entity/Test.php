<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Test
 *
 * @ORM\Table(name="cir_test")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TestRepository")
 */
class Test
{
    const DEFAULT_PACKAGE = '_root_';

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
     * @ORM\Column(name="name", type="string", length=256)
     */
    private $name;

    /**
     * @var string classname
     *
     * @ORM\Column(name="classname", type="string", length=256)
     */
    private $className;

    /**
     * @var string package
     *
     * @ORM\Column(name="package", type="string", length=256)
     */
    private $package;

    /**
     * @var int
     *
     * @ORM\Column(name="passed", type="smallint")
     */
    private $passed;

    /**
     * @var int
     *
     * @ORM\Column(name="failed", type="smallint")
     */
    private $failed;

    /**
     * @var int
     *
     * @ORM\Column(name="errored", type="smallint")
     */
    private $errored;

    /**
     * @var int
     *
     * @ORM\Column(name="skipped", type="smallint")
     */
    private $skipped;

    /**
     * @var float
     *
     * @ORM\Column(name="duration", type="float")
     */
    private $duration;

    /**
     * @var string
     *
     * @ORM\Column(name="system_out", type="text")
     */
    private $systemOut;

    /**
     * @var string
     *
     * @ORM\Column(name="system_err", type="text")
     */
    private $systemErr;

    /**
     * @Gedmo\SortablePosition
     * @ORM\Column(name="position", type="integer")
     */
    private $position;

    /**
     * @var Suite
     * 
     * @Gedmo\SortableGroup
     * @ORM\ManyToOne(targetEntity="Suite")
     * @ORM\JoinColumn(name="suite_id", referencedColumnName="id", nullable=false)
     */
    private $suite;

    /**
     * Constructor
     *
     * @param Suite $suite
     */
    public function __construct($suite)
    {
        $this->setSuite($suite);
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
     * @return Test
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
     * Set classname
     *
     * @param string $classname
     *
     * @return Test
     */
    public function setClassName($classname)
    {
        $this->className = $classname;

        return $this;
    }

    /**
     * Get classname
     *
     * @return string
     */
    public function getClassName()
    {
        return $this->className;
    }

    /**
     * Set package
     *
     * @param string $package
     *
     * @return Test
     */
    public function setPackage($package)
    {
        $this->package = $package;

        return $this;
    }

    /**
     * Get package
     *
     * @return string
     */
    public function getPackage()
    {
        return $this->package;
    }

    /**
     * Set full class name
     *
     * @return Test
     */
    public function setFullClassName($fullClassName)
    {
        if (substr_count($fullClassName, '.') > 0) {
            $index = strrpos($fullClassName, '.');
            $this->setPackage(substr($fullClassName, 0, $index));
            $this->setClassName(substr($fullClassName, $index+1));
        } else {
            $this->setPackage(Test::DEFAULT_PACKAGE);
            $this->setClassName($fullClassName);
        }
    }

    /**
     * Set passed
     *
     * @return Test
     */
    public function setpassed()
    {
        $this->passed  = 1;
        $this->failed  = 0;
        $this->errored = 0;
        $this->skipped = 0;

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
     * @return Test
     */
    public function setFailed()
    {
        $this->passed  = 0;
        $this->failed  = 1;
        $this->errored = 0;
        $this->skipped = 0;

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
     * @return Test
     */
    public function setErrored()
    {
        $this->passed  = 0;
        $this->failed  = 0;
        $this->errored = 1;
        $this->skipped = 0;

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
     * @return Test
     */
    public function setSkipped()
    {
        $this->passed  = 0;
        $this->failed  = 0;
        $this->errored = 0;
        $this->skipped = 1;

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
     * Set status
     *
     * @param int $status
     *
     * @return Test
     */
    public function setStatus($status)
    {
        switch ($status) {
            case Status::SUCCESS:
                $this->setPassed();
                break;
            case Status::FAILED:
                $this->setFailed();
                break;
            case Status::ERROR:
                $this->setErrored();
                break;
            case Status::SKIPPED:
                $this->setSkipped();
                break;                               
        }
    }

    /**
     * Get status
     *
     * @return int
     */
    public function getStatus()
    {
        if ($this->passed > 0) {
            return Status::SUCCESS;
        } elseif ($this->failed > 0) {
            return Status::FAILED;
        } elseif ($this->skipped > 0) {
            return Status::SKIPPED;
        } else {
            return Status::ERROR;
        }
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
     * Set system out message 
     *
     * @param string $systemOut
     *
     * @return Test
     */
    public function setSystemOut($systemOut)
    {
        $this->systemOut = $systemOut;

        return $this;
    }

    /**
     * Get system out message
     *
     * @return string
     */
    public function getSystemOut()
    {
        return $this->systemOut;
    }

    /**
     * Set system error message 
     *
     * @param string $systemErr
     *
     * @return Test
     */
    public function setSystemErr($systemErr)
    {
        $this->systemErr = $systemErr;

        return $this;
    }

    /**
     * Get system error message
     *
     * @return string
     */
    public function getSystemErr()
    {
        return $this->systemErr;
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
     * Set suite
     *
     * @param Suite $suite
     *
     * @return Suite
     */
    public function setSuite($suite)
    {
        $this->suite = $suite;

        return $this;
    }

    /**
     * Get suite
     *
     * @return Suite
     */
    public function getSuite()
    {
        return $this->suite;
    }
}
