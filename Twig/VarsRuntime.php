<?php

namespace PN\ServiceBundle\Twig;

use Doctrine\ORM\EntityManagerInterface;
use PN\ServiceBundle\Service\ContainerParameterService;
use PN\ServiceBundle\Utils\Date;
use PN\ServiceBundle\Utils\Number;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Twig\Extension\RuntimeExtensionInterface;

/**
 * @author Peter Nassef <peter.nassef@gmail.com>
 * @version 1.0
 */
class VarsRuntime implements RuntimeExtensionInterface
{

    private $em;
    private $containerParameterService;

    public function __construct(EntityManagerInterface $em, ContainerParameterService $containerParameterService)
    {
        $this->containerParameterService = $containerParameterService;
        $this->em = $em;
    }

    public function getContainerParameter($name)
    {
        return $this->containerParameterService->get($name);
    }

    public function staticVariable($class, $property)
    {
        if (property_exists($class, $property)) {
            return $class::$$property;
        }

        return null;
    }

    public function className($object)
    {
        return (new \ReflectionClass($object))->getShortName();
    }

    public function priceFormat($price)
    {
        return Number::currencyWithFormat($price, "EGP");
    }

    public function dateFormat(\DateTime $date)
    {
        return $date->format(Date::DATE_FORMAT3);
    }

    public function timeFormat(\DateTime $date)
    {
        return $date->format(Date::DATE_FORMAT_TIME);
    }

    public function dateTimeFormat(\DateTime $date)
    {
        return $date->format(Date::DATE_FORMAT6);
    }

    public function currencyWithFormat($price)
    {
        return Number::currencyWithFormat($price);
    }

    public function rawText($str, $length = null)
    {
        $str = strip_tags($str);
        $search = array('&rsquo;', '&nbsp;', '&bull;', "\n", "\t", "\r", "\v", "\e");
        $str = str_replace($search, '', $str);

        if ($length != null and strlen($str) > $length) {
            $str = htmlspecialchars_decode(substr($str, 0, strpos(wordwrap($str, $length), "\n"))).'...';
        }

        return $str;
    }

    /**
     * @param $var
     * @param $instance
     * @return bool
     */
    public function isInstanceof($var, $instance)
    {
        return $var instanceof $instance;
    }

    public function jsonDecode($str)
    {
        return json_decode($str);
    }

    public function jsonEncode($str)
    {
        return trim(json_encode($str), '"');
    }

}
