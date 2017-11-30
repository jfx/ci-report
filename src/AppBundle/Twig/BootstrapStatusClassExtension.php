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

use AppBundle\Entity\Status;
use AppBundle\Entity\Test;
use Twig_Extension;
use Twig_SimpleFunction;

/**
 * Status class Twig extension.
 *
 * @category  ci-report app
 *
 * @author    Francois-Xavier Soubirou <soubirou@yahoo.fr>
 * @copyright 2017 Francois-Xavier Soubirou
 * @license   http://www.gnu.org/licenses/   GPLv3
 *
 * @see      https://www.ci-report.io
 */
class BootstrapStatusClassExtension extends Twig_Extension
{
    const BOOTSTRAP_SUCCESS = 'success';
    const BOOTSTRAP_WARNING = 'warning';
    const BOOTSTRAP_DANGER = 'danger';
    const BOOTSTRAP_UNKNOWN = 'secondary';
    const FA_SUCCESS = 'fa-check-circle';
    const FA_WARNING = 'fa-exclamation-circle';
    const FA_DANGER = 'fa-times-circle';
    const FA_UNKNOWN = 'fa-question-circle';

    /**
     * Returns a list of functions to add to the existing list.
     *
     * @return array
     */
    public function getFunctions(): array
    {
        return array(
            new Twig_SimpleFunction('statusColorClass', array($this, 'getStatusColorClass')),
            new Twig_SimpleFunction('statusIconClass', array($this, 'getStatusIconClass')),
        );
    }

    /**
     * Return Bootstrap style class of status.
     *
     * @param int|string $status Status id (const of Status class)
     *
     * @return string
     */
    public function getStatusColorClass($status): string
    {
        switch ($status) {
            case Test::ERRORED:
            case Test::FAILED:
            case Status::FAILED:
                $color = self::BOOTSTRAP_DANGER;
                break;
            case Test::SKIPPED:
            case Status::WARNING:
                $color = self::BOOTSTRAP_WARNING;
                break;
            case Status::UNKNOWN:
                $color = self::BOOTSTRAP_UNKNOWN;
                break;
            default:
                $color = self::BOOTSTRAP_SUCCESS;
        }

        return $color;
    }

    /**
     * Return Font Awesome icon of status.
     *
     * @param int|string $status Status id (const of Status class)
     *
     * @return string
     */
    public function getStatusIconClass($status): string
    {
        switch ($status) {
            case Test::ERRORED:
            case Test::FAILED:
            case Status::FAILED:
                $icon = self::FA_DANGER;
                break;
            case Test::SKIPPED:
            case Status::WARNING:
                $icon = self::FA_WARNING;
                break;
            case Status::UNKNOWN:
                $icon = self::FA_UNKNOWN;
                break;
            default:
                $icon = self::FA_SUCCESS;
        }

        return $icon;
    }
}
