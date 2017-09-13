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

namespace AppBundle\Twig;

use Datetime;
use Twig_Extension;
use Twig_SimpleFunction;

/**
 * Datetime formater class Twig extension.
 *
 * @category  ci-report app
 *
 * @author    Francois-Xavier Soubirou <soubirou@yahoo.fr>
 * @copyright 2017 Francois-Xavier Soubirou
 * @license   http://www.gnu.org/licenses/   GPLv3
 *
 * @see      https://www.ci-report.io
 */
class DatetimeFormater extends Twig_Extension
{
    /**
     * Returns a list of functions to add to the existing list.
     *
     * @return array
     */
    public function getFunctions(): array
    {
        return array(
            new Twig_SimpleFunction('datetimeFormat', array($this, 'formatDatetime')),
        );
    }

    /**
     * Return formated datetime.
     *
     * @param Datetime $datetime Datetime object
     *
     * @return string
     */
    public function formatDatetime(?Datetime $datetime): string
    {
        if (null === $datetime) {
            return '-';
        }

        return $datetime->format('d/m/Y H:i:s');
    }
}
