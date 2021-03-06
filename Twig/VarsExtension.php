<?php

namespace PN\ServiceBundle\Twig;

use PN\ServiceBundle\Twig\VarsRuntime;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;
use Twig\TwigTest;

/**
 * @author Peter Nassef <peter.nassef@gmail.com>
 * @version 1.0
 */
class VarsExtension extends AbstractExtension {

    public function getFilters() {
        return array(
            new TwigFilter('currencyWithFormat', array(VarsRuntime::class, 'currencyWithFormat')),
            new TwigFilter('rawText', array(VarsRuntime::class, 'rawText')),
            new TwigFilter('pn_json_decode', array(VarsRuntime::class, 'jsonDecode')),
            new TwigFilter('pn_json_encode', array(VarsRuntime::class, 'jsonEncode')),
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
            new TwigFunction('getParameter', array(VarsRuntime::class, 'getContainerParameter')),
            new TwigFunction('staticVariable', array(VarsRuntime::class, 'staticVariable')),
        );
    }

    public function getTests() {
        return [
            new TwigTest('instanceof', array(VarsRuntime::class, 'isInstanceof')),
        ];
    }

    public function getName() {
        return 'pn.service.twig.extension';
    }

}
