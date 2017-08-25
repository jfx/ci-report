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

use Datetime;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Project entity class.
 *
 * @category  ci-report app
 *
 * @author    Francois-Xavier Soubirou <soubirou@yahoo.fr>
 * @copyright 2017 Francois-Xavier Soubirou
 * @license   http://www.gnu.org/licenses/   GPLv3
 *
 * @see      https://ci-report.io
 *
 * @ORM\Table(name="cir_project")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProjectRepository")
 * @ORM\HasLifecycleCallbacks()
 *
 * @UniqueEntity("name")
 */
class Project
{
    const DEFAULT_WARNING_LIMIT = 80;
    const DEFAULT_SUCCESS_LIMIT = 95;

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
     * Name of the project.
     *
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=50, unique=true)
     *
     * @Assert\NotBlank
     *
     * @Serializer\Groups({"create", "public"})
     */
    private $name;

    /**
     * Unique short name of project defined on project creation.
     *
     * @var string
     *
     * @ORM\Column(name="refid", type="string", length=50, unique=true)
     *
     * @Serializer\Groups({"public"})
     */
    private $refId;

    /**
     * @var string
     *
     * @ORM\Column(name="token", type="string", length=50)
     *
     * @Serializer\Exclude
     */
    private $token;

    /**
     * Tests warning limit.
     *
     * @var int
     *
     * @ORM\Column(name="warning_limit", type="smallint")
     *
     * @Serializer\Groups({"create", "public"})
     */
    private $warningLimit;

    /**
     * Tests success limit.
     *
     * @var int
     *
     * @ORM\Column(name="success_limit", type="smallint")
     *
     * @Serializer\Groups({"create", "public"})
     */
    private $successLimit;

    /**
     * Email.
     *
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=50)
     *
     * @Assert\NotBlank
     * @Assert\Email(strict=true)
     *
     * @Serializer\Groups({"create"})
     */
    private $email;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="created", type="datetime")
     *
     * @Serializer\Exclude
     */
    private $created;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->setWarningLimit(self::DEFAULT_WARNING_LIMIT);
        $this->setSuccessLimit(self::DEFAULT_SUCCESS_LIMIT);
    }

    /**
     * Triggered on insert.
     *
     * @ORM\PrePersist
     */
    public function onPrePersist()
    {
        $this->created = new DateTime();
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
     * @param string $name
     *
     * @return Project
     */
    public function setName(string $name): Project
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
     * Set refId.
     *
     * @param string $refId
     *
     * @return Project
     */
    public function setRefId(string $refId): Project
    {
        $this->refId = $refId;

        return $this;
    }

    /**
     * Get refId.
     *
     * @return string
     */
    public function getRefId(): string
    {
        return $this->refId;
    }

    /**
     * Set token.
     *
     * @param string $token
     *
     * @return Project
     */
    public function setToken(string $token): Project
    {
        $this->token = $token;

        return $this;
    }

    /**
     * Get token.
     *
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * Set warning limit.
     *
     * @param int $warningLimit
     *
     * @return Project
     */
    public function setWarningLimit(int $warningLimit): Project
    {
        $this->warningLimit = $warningLimit;

        return $this;
    }

    /**
     * Get warning limit.
     *
     * @return int
     */
    public function getWarningLimit(): ?int
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
    public function setSuccessLimit(int $successLimit): Project
    {
        $this->successLimit = $successLimit;

        return $this;
    }

    /**
     * Get success limit.
     *
     * @return int
     */
    public function getSuccessLimit(): ?int
    {
        return $this->successLimit;
    }

    /**
     * Set email.
     *
     * @param string $email
     *
     * @return Project
     */
    public function setEmail(string $email): Project
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email.
     *
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }
}
