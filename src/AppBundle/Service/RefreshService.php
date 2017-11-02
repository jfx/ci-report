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

namespace AppBundle\Service;

use AppBundle\Entity\Campaign;
use AppBundle\Entity\Status;
use AppBundle\Entity\Suite;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * Refresh service class.
 *
 * @category  ci-report app
 *
 * @author    Francois-Xavier Soubirou <soubirou@yahoo.fr>
 * @copyright 2017 Francois-Xavier Soubirou
 * @license   http://www.gnu.org/licenses/   GPLv3
 *
 * @see      https://www.ci-report.io
 */
class RefreshService
{
    /**
     * @var ManagerRegistry
     */
    private $doctrine;

    /**
     * Constructor.
     *
     * @param ManagerRegistry $doctrine Doctrine registry manager
     */
    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    /**
     * Refresh campaign status and tests results by scanning all suites.
     *
     * @param Campaign $campaign Campaign
     */
    public function refreshCampaign(Campaign $campaign): void
    {
        $repository = $this->doctrine->getRepository(Suite::class);
        $suites = $repository->findSuitesByCampaign($campaign);

        $passed = 0;
        $failed = 0;
        $errored = 0;
        $skipped = 0;
        $disabled = 0;
        $status = 0;

        foreach ($suites as $suite) {
            $passed += $suite->getPassed();
            $failed += $suite->getFailed();
            $errored += $suite->getErrored();
            $skipped += $suite->getSkipped();
            $disabled += $suite->getDisabled();
            $status = max($status, $suite->getStatus());
        }
        $campaign->setPassed($passed)
            ->setFailed($failed)
            ->setErrored($errored)
            ->setSkipped($skipped)
            ->setDisabled($disabled);

        // If no suite Status = Warning
        if (0 === $status) {
            $campaign->setStatus(Status::WARNING);
        } else {
            $campaign->setStatus($status);
        }
        $this->doctrine->getManager()->flush();
    }
}
