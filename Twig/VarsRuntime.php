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

    private $containerParameterService;

    public function __construct(ContainerParameterService $containerParameterService)
    {
        $this->containerParameterService = $containerParameterService;
    }

    public function getFileContent($filePath): ?string
    {
        $projectDir = $this->containerParameterService->get("kernel.project_dir");
        $publicDirectory = rtrim(UploadPath::getWebRoot(), '/');

        if ($this->containerParameterService->has("default_uri")) {
            $filePath = str_replace($this->containerParameterService->get("default_uri"), "", $filePath);
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
        return $this->containerParameterService->get($name);
    }

    public function staticVariable($class, $property)
    {
        if (property_exists($class, $property)) {
            return $class::$$property;
        }

        return null;
    }

    public function className($object): string
    {
        return (new \ReflectionClass($object))->getShortName();
    }

    public function priceFormat($price): string
    {
        return Number::currencyWithFormat($price, "EGP");
    }

    public function dateFormat(\DateTime $date): string
    {
        return $date->format(Date::DATE_FORMAT3);
    }

    public function timeFormat(\DateTime $date): string
    {
        return $date->format(Date::DATE_FORMAT_TIME);
    }

    public function dateTimeFormat(\DateTime $date): string
    {
        return $date->format(Date::DATE_FORMAT6);
    }

    public function currencyWithFormat($price): string
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
     * {{ dump(entity is instanceof("\\App\\ProductBundle\\Entity\\Category::class")) }}

     * @param $var
     * @param $instance
     * @return bool
     */
    public function isInstanceof($var, $instance): bool
    {
        return $var instanceof $instance;
    }

    public function rawurldecode($str): string
    {
        return rawurldecode($str);
    }

    public function jsonDecode($str)
    {
        return json_decode($str);
    }

    public function jsonEncode($str): string
    {
        return trim(json_encode($str), '"');
    }

    public function encodeEmailAddress(string $email): string
    {
        $output = '';
        for ($i = 0; $i < strlen($email); $i++) {
            $output .= '&#'.ord($email[$i]).';';
        }

        return $output;
    }

    public function enum(string $className): object
    {
        if (!is_subclass_of($className, \UnitEnum::class)) {
            throw new \InvalidArgumentException(sprintf('"%s" is not an enum.', $className));
        }

        return new class ($className) {
            public function __construct(private string $className)
            {
            }

            public function __call(string $caseName, array $arguments): mixed
            {
                return ($this->className)::$caseName();
            }
        };
    }
    
    /**
     * Remove unnecessary spaces from a css string
     * @param string $string
     * @return string
     **/
    private function removeSpaces(string $string): string
    {
        $string = preg_replace("/\s{2,}/", " ", $string);
        $string = str_replace("\n", "", $string);
        $string = str_replace('@CHARSET "UTF-8";', "", $string);

        return str_replace(', ', ",", $string);
    }

    /**
     * Remove all comments from css string
     * @param string $string
     * @return string
     **/
    private function removeComments(string $string): string
    {
        return preg_replace("/(\/\*[\w\'\s\r\n\*\+\,\"\-\.]*\*\/)/", "", $string);
    }

}
