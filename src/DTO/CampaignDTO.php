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

namespace App\DTO;

use DateTime;
use JMS\Serializer\Annotation\Type;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Campaign data transfert object class.
 *
 * @category  ci-report app
 *
 * @author    Francois-Xavier Soubirou <soubirou@yahoo.fr>
 * @copyright 2017 Francois-Xavier Soubirou
 * @license   http://www.gnu.org/licenses/   GPLv3
 *
 * @see      https://www.ci-report.io
 */
class CampaignDTO
{
    /**
     * Start Date time of the campaign in format (2017-07-01 12:30:01). Now by default.
     *
     * @var DateTime
     *
     * @Type("DateTime<'Y-m-d H:i:s'>")
     *
     * @Assert\DateTime()
     */
    private $start;

    /**
     * End Date time of the campaign in format (2017-07-01 12:30:01). Null by default.
     *
     * @var DateTime
     *
     * @Type("DateTime<'Y-m-d H:i:s'>")
     *
     * @Assert\DateTime()
     */
    private $end;

    /**
     * Set start datetime of campaign.
     *
     * @param DateTime $datetime start datetime of campaign.
     *
     * @return CampaignDTO
     */
    public function setStart(DateTime $datetime): self
    {
        $this->start = $datetime;

        return $this;
    }

    /**
     * Get start datetime of campaign.
     *
     * @return DateTime
     */
    public function getStart(): ?DateTime
    {
        return $this->start;
    }

    /**
     * Set end datetime of campaign.
     *
     * @param DateTime $datetime end datetime of campaign.
     *
     * @return CampaignDTO
     */
    public function setEnd(DateTime $datetime): self
    {
        $this->end = $datetime;

        return $this;
    }

    /**
     * Get end datetime of campaign.
     *
     * @return DateTime
     */
    public function getEnd(): ?DateTime
    {
        return $this->end;
    }
}
