<?php

namespace PN\ServiceBundle\Twig;

use PN\ServiceBundle\Lib\UploadPath;
use PN\ServiceBundle\Service\ContainerParameterService;
use PN\ServiceBundle\Utils\Date;
use PN\ServiceBundle\Utils\Number;
use Twig\Extension\RuntimeExtensionInterface;

/**
 * @author Peter Nassef <peter.nassef@gmail.com>
 * @version 1.0
 */
class VarsRuntime implements RuntimeExtensionInterface
{

    private $containerParameter;

    public function __construct(ContainerParameterService $containerParameter)
    {
        $this->containerParameter = $containerParameter;
    }

    public function getFileContent($filePath)
    {
        $projectDir = $this->containerParameter->get("kernel.project_dir");
        $publicDirectory = rtrim(UploadPath::getWebRoot(), '/');

        if ($this->containerParameter->has("router.request_context.scheme") and $this->containerParameter->has("router.request_context.host")) {
            $baseUrl = $this->containerParameter->get("router.request_context.scheme")."://".$this->containerParameter->get("router.request_context.host");
            $filePath = str_replace($baseUrl, "", $filePath);
        }

        $fullFilePath = "{$projectDir}/{$publicDirectory}{$filePath}";

        if (file_exists($fullFilePath)) {
            $str = file_get_contents($fullFilePath);
            $str = $this->removeSpaces($str);

            return $this->removeComments($str);
        }

        return null;
    }

    public function getContainerParameter($name)
    {
        return $this->containerParameter->get($name);
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

    public function rawurldecode($str)
    {
        return rawurldecode($str);
    }

    public function jsonDecode($str)
    {
        return json_decode($str);
    }

    public function jsonEncode($str)
    {
        return trim(json_encode($str), '"');
    }

    /**
     * Remove unnecessary spaces from a css string
     * @param String $string
     * @return String
     **/
    private function removeSpaces($string)
    {
        $string = preg_replace("/\s{2,}/", " ", $string);
        $string = str_replace("\n", "", $string);
        $string = str_replace('@CHARSET "UTF-8";', "", $string);

        return str_replace(', ', ",", $string);
    }

    /**
     * Remove all comments from css string
     * @param String $string
     * @return String
     **/
    private function removeComments($string)
    {
        return preg_replace("/(\/\*[\w\'\s\r\n\*\+\,\"\-\.]*\*\/)/", "", $string);
    }
}
