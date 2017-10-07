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

use Twig_Extension;
use Twig_SimpleFunction;

/**
 * graph.js values from campaigns array class Twig extension.
 *
 * @category  ci-report app
 *
 * @author    Francois-Xavier Soubirou <soubirou@yahoo.fr>
 * @copyright 2017 Francois-Xavier Soubirou
 * @license   http://www.gnu.org/licenses/   GPLv3
 *
 * @see      https://www.ci-report.io
 */
class GraphSuitesValues extends Twig_Extension
{
    /**
     * Returns a list of functions to add to the existing list.
     *
     * @return array
     */
    public function getFunctions(): array
    {
        return array(
            new Twig_SimpleFunction('graphBarSuitesValues', array($this, 'getValues')),
        );
    }

    /**
     * Return values of graph bars for campaigns.
     *
     * @param array $suites Array of suites
     *
     * @return array
     */
    public function getValues(array $suites): array
    {
        $yAxisValues = '';
        $redValues = '';
        $yellowValues = '';
        $greenValues = '';

        foreach ($suites as $suite) {
            $yAxisValues .= '"#'.$suite->getRefid().'",';
            $redValues .= $suite->getErrored() + $suite->getFailed().',';
            $yellowValues .= $suite->getSkipped().',';
            $greenValues .= $suite->getPassed().',';
        }

        return array(
            'yAxis' => $yAxisValues,
            'red' => $redValues,
            'yellow' => $yellowValues,
            'green' => $greenValues,
        );
    }
}
