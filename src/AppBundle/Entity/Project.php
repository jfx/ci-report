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

use AppBundle\DTO\ProjectDTO;
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
 * @UniqueEntity("name", groups={"input"})
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
     * @Assert\NotBlank(groups={"input"})
     *
     * @Serializer\Groups({"public", "private"})
     */
    private $name;

    /**
     * Unique short name of project defined on project creation.
     *
     * @var string
     *
     * @ORM\Column(name="refid", type="string", length=50, unique=true)
     *
     * @Serializer\Groups({"public", "private"})
     */
    private $refid;

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
     * @ORM\Column(name="warning", type="smallint")
     *
     * @Assert\NotBlank(groups={"input"})
     * @Assert\Range(min=0, max=100, groups={"input"})
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
     * @Assert\NotBlank(groups={"input"})
     * @Assert\Range(min=0, max=100, groups={"input"})
     *
     * @Serializer\Groups({"public", "private"})
     */
    private $success;

    /**
     * Email.
     *
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=50)
     *
     * @Assert\NotBlank(groups={"input"})
     * @Assert\Email(strict=true, groups={"input"})
     *
     * @Serializer\Groups({"private"})
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
        $this->setWarning(self::DEFAULT_WARNING_LIMIT);
        $this->setSuccess(self::DEFAULT_SUCCESS_LIMIT);
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
     * Set refid.
     *
     * @param string $refid
     *
     * @return Project
     */
    public function setRefid(string $refid): Project
    {
        $this->refid = $refid;

        return $this;
    }

    /**
     * Get refid.
     *
     * @return string
     */
    public function getRefid(): string
    {
        return $this->refid;
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
     * @param int $warning
     *
     * @return Project
     */
    public function setWarning(int $warning): Project
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
     * @param int $success
     *
     * @return Project
     */
    public function setSuccess(int $success): Project
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

    /**
     * Get created date.
     *
     * @return Datetime
     */
    public function getCreated(): ?Datetime
    {
        return $this->created;
    }

    /**
     * Set from DTO object.
     *
     * @param ProjectDTO $dto DTO object
     *
     * @return Project
     */
    public function setFromDTO(ProjectDTO $dto): Project
    {
        $this->setName($dto->getName())
            ->setEmail($dto->getEmail())
            ->setWarning($dto->getWarning())
            ->setSuccess($dto->getSuccess());

        return $this;
    }
}
