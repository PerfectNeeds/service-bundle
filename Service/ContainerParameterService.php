<?php

namespace PN\ServiceBundle\Service;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Exception\ParameterNotFoundException;

/**
 * @author Peter Nassef <peter.nassef@gmail.com>
 * @version 1.0
 */
class ContainerParameterService {

    private $container;

    public function __construct(ContainerInterface $container) {
        $this->container = $container;
    }

    /**
     * {@inheritDoc}
     */
    public function get($name, $parent = null) {
        if (null === $parent) {
            $parent = $this->container->getParameterBag()->all();
        }
        $name = strtolower($name);
        if (!array_key_exists($name, $parent)) {
            if (!$name) {
                throw new ParameterNotFoundException($name);
            }
            if (false !== strpos($name, '.')) {
                $parts = explode('.', $name);
                $key = array_shift($parts);
                if (isset($parent[$key])) {
                    return $this->get(implode('.', $parts), $parent[$key]);
                }
            }
            $alternatives = [];
            foreach ($parent as $key => $parameterValue) {
                $lev = levenshtein($name, $key);
                if ($lev <= strlen($name) / 3 || false !== strpos($key, $name)) {
                    $alternatives[] = $key;
                }
            }
            throw new ParameterNotFoundException($name, null, null, null, $alternatives);
        }

        return $parent[$name];
    }

    public function has($name, $parent = null) {
        if (null === $parent) {
            $parent = $this->container->getParameterBag()->all();
        }
        $name = strtolower($name);
        if (!array_key_exists($name, $parent)) {
            if (!$name) {
                return false;
            }
            if (false !== strpos($name, '.')) {
                $parts = explode('.', $name);
                $key = array_shift($parts);
                if (isset($parent[$key])) {
                    return $this->has(implode('.', $parts), $parent[$key]);
                }
            }
            $alternatives = [];
            foreach ($parent as $key => $parameterValue) {
                $lev = levenshtein($name, $key);
                if ($lev <= strlen($name) / 3 || false !== strpos($key, $name)) {
                    $alternatives[] = $key;
                }
            }
            return false;
        }


        return true;
    }

}
