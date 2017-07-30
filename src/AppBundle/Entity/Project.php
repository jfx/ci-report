<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Project.
 *
 * @ORM\Table(name="cir_project")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProjectRepository")
 */
class Project
{
    const DEFAULT_WARNING_LIMIT = 80;
    const DEFAULT_SUCCESS_LIMIT = 95;

    /**
     * @var int Id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string Name
     *
     * @ORM\Column(name="name", type="string", length=50, unique=true)
     */
    private $name;

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
     * Constructor.
     */
    public function __construct()
    {
        $this->setWarningLimit(self::DEFAULT_WARNING_LIMIT);
        $this->setSuccessLimit(self::DEFAULT_SUCCESS_LIMIT);
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
     * Set name.
     *
     * @param string $name
     *
     * @return Project
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
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
}
