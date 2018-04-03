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

namespace App\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * API controller class.
 *
 * @category  ci-report app
 *
 * @author    Francois-Xavier Soubirou <soubirou@yahoo.fr>
 * @copyright 2017 Francois-Xavier Soubirou
 * @license   http://www.gnu.org/licenses/   GPLv3
 *
 * @see      https://www.ci-report.io
 */
abstract class AbstractApiController extends FOSRestController
{
    /**
     * Check token.
     *
     * @param Request $request The request
     * @param string  $tokenDB The referenced token in database
     *
     * @return bool
     */
    protected function isInvalidToken(Request $request, string $tokenDB): bool
    {
        $tokenRequest = $request->headers->get('X-CIR-TKN');

        return (null === $tokenRequest) || ($tokenDB !== $tokenRequest);
    }

    /**
     * Get invalid token view.
     *
     * @return View
     */
    protected function getInvalidTokenView(): View
    {
        return $this->view(
            array(
                'code' => Response::HTTP_UNAUTHORIZED,
                'message' => 'Invalid token',
            ),
            Response::HTTP_UNAUTHORIZED
        );
    }
}
