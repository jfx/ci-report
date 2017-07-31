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
namespace AppBundle\Twig;

use AppBundle\Entity\Status;
use Twig_Extension;
use Twig_SimpleFunction;

class BootstrapStatusClassExtension extends Twig_Extension
{
    const BOOTSTRAP_SUCCESS = 'success';
    const BOOTSTRAP_WARNING = 'warning';
    const BOOTSTRAP_DANGER = 'danger';

    const FA_SUCCESS = 'fa-check-circle';
    const FA_WARNING = 'fa-exclamation-circle';
    const FA_DANGER = 'fa-times-circle';

    public function getFunctions()
    {
        return array(
            new Twig_SimpleFunction('statusColorClass', array($this, 'getStatusColorClass')),
            new Twig_SimpleFunction('statusIconClass', array($this, 'getStatusIconClass')),
        );
    }

    public function getStatusColorClass($status)
    {
        switch ($status) {
            case Status::FAILED:
            case Status::ERROR:
                $color = self::BOOTSTRAP_DANGER;
                break;
            case Status::WARNING:
            case Status::SKIPPED:
                $color = self::BOOTSTRAP_WARNING;
                break;
            default:
                $color = self::BOOTSTRAP_SUCCESS;
        }

        return $color;
    }

    public function getStatusIconClass($status)
    {
        switch ($status) {
            case Status::FAILED:
            case Status::ERROR:
                $icon = self::FA_DANGER;
                break;
            case Status::WARNING:
            case Status::SKIPPED:
                $icon = self::FA_WARNING;
                break;
            default:
                $icon = self::FA_SUCCESS;
        }

        return $icon;
    }
}
