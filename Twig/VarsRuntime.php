<?php

namespace PN\ServiceBundle\Twig;

use Twig\Extension\RuntimeExtensionInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use PN\ServiceBundle\Service\ContainerParameterService;
use PN\ServiceBundle\Utils\Date,
    PN\ServiceBundle\Utils\Number;

/**
 * @author Peter Nassef <peter.nassef@gmail.com>
 * @version 1.0
 */
class VarsRuntime implements RuntimeExtensionInterface {

    private $container;
    private $em;

    public function __construct(ContainerInterface $container) {
        $this->container = $container;
        $this->em = $container->get('doctrine')->getManager();
    }

    public function getContainerParameter($name) {
        return $this->container->get(ContainerParameterService::class)->get($name);
    }

    public function staticVariable($class, $property) {
        if (property_exists($class, $property)) {
            return $class::$$property;
        }
        return null;
    }

    public function className($object) {
        return (new \ReflectionClass($object))->getShortName();
    }

    public function priceFormat($price) {
        return Number::currencyWithFormat($price, "EGP");
    }

    public function dateFormat(\DateTime $date) {
        return $date->format(Date::DATE_FORMAT3);
    }

    public function timeFormat(\DateTime $date) {
        return $date->format(Date::DATE_FORMAT_TIME);
    }

    public function dateTimeFormat(\DateTime $date) {
        return $date->format(Date::DATE_FORMAT6);
    }

    public function currencyWithFormat($price) {
        return Number::currencyWithFormat($price);
    }

    public function rawText($str, $length = null) {
        $str = strip_tags($str);
        $search = array('&rsquo;', '&nbsp;', '&bull;');
        $str = str_replace($search, '', $str);

        if ($length != null AND strlen($str) > $length) {
            $str = htmlspecialchars_decode(substr($str, 0, strpos(wordwrap($str, $length), "\n"))) . '...';
        }
        return $str;
    }

    /**
     * @param $var
     * @param $instance
     * @return bool
     */
    public function isInstanceof($var, $instance) {
        return $var instanceof $instance;
    }

}
