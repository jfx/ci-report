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

use AppBundle\Entity\Project;
use JMS\Serializer\Annotation\Type;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Suite limits data transfert object class.
 *
 * @category  ci-report app
 *
 * @author    Francois-Xavier Soubirou <soubirou@yahoo.fr>
 * @copyright 2017 Francois-Xavier Soubirou
 * @license   http://www.gnu.org/licenses/   GPLv3
 *
 * @see      https://www.ci-report.io
 */
class SuiteLimitsDTO
{
    /**
     * Tests warning limit. Integer between 0 and 100 %. Limit defined on project by default.
     *
     * @var int
     *
     * @Type("integer")
     *
     * @Assert\Type("integer")
     * @Assert\Range(min=0, max=100)
     */
    protected $warning;

    /**
     * Tests success limit. Integer between 0 and 100 %. Limit defined on project by default.
     *
     * @var int
     *
     * @Type("integer")
     *
     * @Assert\Type("integer")
     * @Assert\Range(min=0, max=100)
     */
    protected $success;

    /**
     * Set warning limit.
     *
     * @param int $warning
     *
     * @return SuiteLimitsDTO
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
    public function getWarning(): ?int
    {
        return $this->warning;
    }

    /**
     * Set success limit.
     *
     * @param int $success
     *
     * @return SuiteLimitsDTO
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
    public function getSuccess(): ?int
    {
        return $this->success;
    }
}
