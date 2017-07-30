<?php

namespace AppBundle\Entity;

/**
 * Status class.
 */
class Status
{
    const SUCCESS = 1;
    const FAILED = 2;
    const ERROR = 4;
    const SKIPPED = 8;
    const WARNING = 16;
}
