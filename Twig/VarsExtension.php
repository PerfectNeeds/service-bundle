<?php

namespace PN\ServiceBundle\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;
use Twig\TwigTest;

/**
 * @author Peter Nassef <peter.nassef@gmail.com>
 * @version 1.0
 */
class VarsExtension extends AbstractExtension
{

    public function getFilters(): array
    {
        return [
            new TwigFilter('currencyWithFormat', [VarsRuntime::class, 'currencyWithFormat']),
            new TwigFilter('rawText', [VarsRuntime::class, 'rawText']),
            new TwigFilter('pn_json_decode', [VarsRuntime::class, 'jsonDecode']),
            new TwigFilter('pn_json_encode', [VarsRuntime::class, 'jsonEncode']),
            new TwigFilter('rawurldecode', [VarsRuntime::class, 'rawurldecode']),
            new TwigFilter('className', [VarsRuntime::class, 'className']),
            new TwigFilter('className', [VarsRuntime::class, 'className']),
            new TwigFilter('priceFormat', [VarsRuntime::class, 'priceFormat']),
            new TwigFilter('dateFormat', [VarsRuntime::class, 'dateFormat']),
            new TwigFilter('timeFormat', [VarsRuntime::class, 'timeFormat']),
            new TwigFilter('dateTimeFormat', [VarsRuntime::class, 'dateTimeFormat']),
            new TwigFilter('encodeEmail', [VarsRuntime::class, 'encodeEmailAddress'], ["is_safe" => ["html"]]),
            new TwigFilter('fileContent', [VarsRuntime::class, 'getFileContent'], ["is_safe" => ["html"]]),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('getParameter', [VarsRuntime::class, 'getContainerParameter']),
            new TwigFunction('staticVariable', [VarsRuntime::class, 'staticVariable']),
        ];
    }

    public function getTests(): array
    {
        return [
            new TwigTest('instanceof', [VarsRuntime::class, 'isInstanceof']),
        ];
    }

    public function getName(): string
    {
        return 'pn.service.twig.extension';
    }

}
