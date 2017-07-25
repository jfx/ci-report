<?php

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
                $color = BootstrapStatusClassExtension::BOOTSTRAP_DANGER;
                break;
            case Status::WARNING:
            case Status::SKIPPED:
                $color = BootstrapStatusClassExtension::BOOTSTRAP_WARNING;
                break;
            default:
                $color = BootstrapStatusClassExtension::BOOTSTRAP_SUCCESS;
        }
        return $color;
    }

    public function getStatusIconClass($status)
    {
        switch ($status) {
            case Status::FAILED:
            case Status::ERROR:
                $icon = BootstrapStatusClassExtension::FA_DANGER;
                break;
            case Status::WARNING:
            case Status::SKIPPED:
                $icon = BootstrapStatusClassExtension::FA_WARNING;
                break;
            default:
                $icon = BootstrapStatusClassExtension::FA_SUCCESS;
        }
        return $icon;
    }
}