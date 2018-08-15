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

namespace App\Util;

use App\DTO\SuiteDTO;
use App\DTO\TestDTO;

/**
 * Suite and tests container class.
 *
 * @category  ci-report app
 *
 * @author    Francois-Xavier Soubirou <soubirou@yahoo.fr>
 * @copyright 2017 Francois-Xavier Soubirou
 * @license   http://www.gnu.org/licenses/   GPLv3
 *
 * @see      https://www.ci-report.io
 */
class SuiteTests
{
    /**
     * @var SuiteDTO
     */
    private $suite;

    /**
     * @var array
     */
    private $tests = array();

    /**
     * Constructor.
     *
     * @param SuiteDTO $suite The suite
     */
    public function __construct(SuiteDTO $suite)
    {
        $this->setSuite($suite);
    }

    /**
     * Set suite.
     *
     * @param SuiteDTO $suite
     *
     * @return SuiteTests
     */
    public function setSuite(SuiteDTO $suite): self
    {
        $this->suite = $suite;

        return $this;
    }

    /**
     * Get suite.
     *
     * @return SuiteDTO
     */
    public function getSuite(): SuiteDTO
    {
        return $this->suite;
    }

    /**
     * Set tests.
     *
     * @param array $tests Array of tests
     *
     * @return SuiteTests
     */
    public function setTests(array $tests): self
    {
        $this->tests = $tests;

        return $this;
    }

    /**
     * Get tests.
     *
     * @return array
     */
    public function getTests(): array
    {
        return $this->tests;
    }

    /**
     * Add a test.
     *
     * @param TestDTO $test Test object
     *
     * @return SuiteTests
     */
    public function addTest(TestDTO $test): self
    {
        $this->tests[] = $test;

        return $this;
    }
}
