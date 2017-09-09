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

/**
 * Util service class.
 *
 * @category  ci-report app
 *
 * @author    Francois-Xavier Soubirou <soubirou@yahoo.fr>
 * @copyright 2017 Francois-Xavier Soubirou
 * @license   http://www.gnu.org/licenses/   GPLv3
 *
 * @see      https://www.ci-report.io
 */
class UtilService
{
    /**
     * Slugify a string.
     * Code from : http://ourcodeworld.com/articles/read/253/creating-url-slugs-properly-in-php-including-transliteration-support-for-utf-8.
     *
     * @param string $string String to convert
     *
     * @return string
     */
    public function toAscii(string $string): string
    {
        $clean1 = htmlentities($string, ENT_QUOTES, 'UTF-8');
        $clean2 = preg_replace(
            '~&([a-z]{1,2})(?:acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i',
            '$1',
            $clean1
        );
        $clean3 = html_entity_decode($clean2, ENT_QUOTES, 'UTF-8');
        $clean4 = preg_replace('~[^0-9a-z]+~i', '-', $clean3);
        $clean5 = strtolower(trim($clean4, '-'));

        return $clean5;
    }

    /**
     * Generate a token.
     *
     * @return string
     */
    public function generateToken(): string
    {
        $part1 = bin2hex(random_bytes(6));
        $part2 = bin2hex(random_bytes(6));
        $part3 = bin2hex(random_bytes(6));

        return $part1.'-'.$part2.'-'.$part3;
    }
}
