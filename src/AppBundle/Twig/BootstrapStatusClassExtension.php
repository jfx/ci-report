<?php

namespace AppBundle\Twig;

use AppBundle\Entity\Campaign;
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
        if ($status == Campaign::STATUS_FAILED) {
            return BootstrapStatusClassExtension::BOOTSTRAP_DANGER;
        } elseif ($status == Campaign::STATUS_WARNING) {
            return BootstrapStatusClassExtension::BOOTSTRAP_WARNING;
        } else {
            return BootstrapStatusClassExtension::BOOTSTRAP_SUCCESS;
        }
    }

    public function getStatusIconClass($status)
    {
        if ($status == Campaign::STATUS_FAILED) {
            return BootstrapStatusClassExtension::FA_DANGER;
        } elseif ($status == Campaign::STATUS_WARNING) {
            return BootstrapStatusClassExtension::FA_WARNING;
        } else {
            return BootstrapStatusClassExtension::FA_SUCCESS;
        }
    }
}