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

/**
 * Status class.
 *
 * @category  ci-report app
 *
 * @author    Francois-Xavier Soubirou <soubirou@yahoo.fr>
 * @copyright 2017 Francois-Xavier Soubirou
 * @license   http://www.gnu.org/licenses/   GPLv3
 *
 * @see      https://www.ci-report.io
 */
class Status
{
    const SUCCESS = 1;
    const WARNING = 2;
    const FAILED = 4;
    const ERROR = 8;
    const SKIPPED = 16;
    const LABEL = array(
        1 => 'Passed',
        2 => 'Warning',
        4 => 'Failed',
        8 => 'Errored',
        16 => 'Skipped',
    );

    /**
     * Get label status.
     *
     * @param int $status Status id
     *
     * @return string
     */
    public static function getLabel(int $status): string
    {
        return self::LABEL[$status];
    }
}
