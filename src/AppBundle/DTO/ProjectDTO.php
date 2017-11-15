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

namespace AppBundle\DTO;

use JMS\Serializer\Annotation\Type;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Project data transfert object class.
 *
 * @category  ci-report app
 *
 * @author    Francois-Xavier Soubirou <soubirou@yahoo.fr>
 * @copyright 2017 Francois-Xavier Soubirou
 * @license   http://www.gnu.org/licenses/   GPLv3
 *
 * @see      https://www.ci-report.io
 */
class ProjectDTO
{
    /**
     * Name of the project.
     *
     * @var string
     *
     * @Type("string")
     *
     * @Assert\NotBlank
     */
    private $name;

    /**
     * Tests warning limit. Integer between 0 and 100 %.
     *
     * @var int
     *
     * @Type("integer")
     *
     * @Assert\NotBlank
     * @Assert\Type("integer")
     * @Assert\Range(min=0, max=100)
     */
    private $warning;

    /**
     * Tests success limit. Integer between 0 and 100 %.
     *
     * @var int
     *
     * @Type("integer")
     *
     * @Assert\NotBlank
     * @Assert\Type("integer")
     * @Assert\Range(min=0, max=100)
     */
    private $success;

    /**
     * Email.
     *
     * @var string
     *
     * @Type("string")
     *
     * @Assert\NotBlank
     * @Assert\Email(strict=true)
     */
    private $email;

    /**
     * Set name.
     *
     * @param string $name
     *
     * @return ProjectDTO
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
     * @param int $warning
     *
     * @return ProjectDTO
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
     * @param int $success
     *
     * @return ProjectDTO
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
     * Set email.
     *
     * @param string $email
     *
     * @return ProjectDTO
     */
    public function setEmail(string $email): self
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
