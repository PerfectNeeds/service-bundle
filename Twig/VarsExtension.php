<?php

namespace PNServiceBundle\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;
use PNServiceBundle\Twig\VarsRuntime;

/**
 * @author Peter Nassef <peter.nassef@gmail.com>
 * @version 1.0
 */
class VarsExtension extends AbstractExtension {

    public function getFilters() {
        return array(
            new TwigFilter('currencyWithFormat', array(VarsRuntime::class, 'currencyWithFormat')),
            new TwigFilter('rawText', array(VarsRuntime::class, 'rawText')),
            new TwigFilter('className', array(VarsRuntime::class, 'className')),
            new TwigFilter('className', array(VarsRuntime::class, 'className')),
            new TwigFilter('priceFormat', array(VarsRuntime::class, 'priceFormat')),
            new TwigFilter('dateFormat', array(VarsRuntime::class, 'dateFormat')),
            new TwigFilter('timeFormat', array(VarsRuntime::class, 'timeFormat')),
            new TwigFilter('dateTimeFormat', array(VarsRuntime::class, 'dateTimeFormat')),
        );
    }

    public function getFunctions() {
        return array(
            new TwigFunction('getDCA', array(VarsRuntime::class, 'getDynamicContentAttribute')),
            new TwigFunction('getParameter', array(VarsRuntime::class, 'getContainerParameter')),
            new TwigFunction('staticVariable', array(VarsRuntime::class, 'staticVariable')),
        );
    }

    public function getName() {
        return 'service.twig.extension';
    }

}
