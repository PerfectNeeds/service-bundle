<?php

namespace PN\ServiceBundle\Service;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Routing\RouterInterface;

/**
 * @author Peter Nassef <peter.nassef@gmail.com>
 * @version 1.0
 */
class CommonFunctionService {

    private $router;
    private $entityManager;

    public function __construct(EntityManagerInterface $em, RouterInterface $router) {
        $this->router = $router;
        $this->entityManager = $em;
    }

    public function getEntitiesWithObject($objectName, $excludeEntities = []) {
        $entities = [];
        $meta = $this->entityManager->getMetadataFactory()->getAllMetadata();
        foreach ($meta as $m) {
            if (array_key_exists($objectName, $m->getAssociationMappings())) {
                $entityNames = (new \ReflectionClass($m->getName()))->getShortName();
                if (!in_array($entityNames, $excludeEntities)) {
                    $entities[$entityNames] = $entityNames;
                }
            }
        }
        return $entities;
    }

    public function getClassNameByObject($entityName) {
        $reflect = new \ReflectionClass($entityName);
        return $reflect->getShortName();
    }

    public function getBundleNameByEntityName($entityName) {
        if (is_object($entityName)) {
            $entityName = $this->getClassNameByObject($entityName);
        }
        $bundle = "";
        $meta = $this->entityManager->getMetadataFactory()->getAllMetadata();
        foreach ($meta as $m) {
            $name = (new \ReflectionClass($m->getName()))->getShortName();
            if ($name == $entityName) {
                $nameSpaces = explode('\\', $m->getName());
                if (count($nameSpaces) == 5) {
                    return $nameSpaces[2];
                }
            }
        }
        return $bundle;
    }

    public function getAllEditRoutes() {
        $excludeBundles = ["SeoBundle", "MediaBundle"];
        /** @var $router \Symfony\Component\Routing\Router */
        $router = $this->router;
        /** @var $collection \Symfony\Component\Routing\RouteCollection */
        $collection = $router->getRouteCollection();
        $allRoutes = $collection->all();

        $routes = [];


        /** @var $params \Symfony\Component\Routing\Route */
        foreach ($allRoutes as $route => $params) {
            $defaults = $params->getDefaults();
            if (!array_key_exists("_controller", $defaults)) {
                continue;
            }
            $controller = $defaults['_controller'];
            $isExclude = $this->array_search_partial($excludeBundles, $controller);
            if (isset($defaults['_controller']) and $isExclude == false and strpos($route, "_edit") !== false) {
                $routes[$route] = $route;
            }
        }
        return $routes;
    }

    public function array_search_partial($arr, $needles) {
        foreach ($arr as $value) {
            if (strpos($needles, $value) !== FALSE) {
                return true;
            }
        }
        return false;
    }

}
