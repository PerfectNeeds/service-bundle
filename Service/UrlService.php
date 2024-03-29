<?php

namespace PN\ServiceBundle\Service;

use Symfony\Component\Asset\Packages;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

class UrlService
{

    private Packages $assetsManager;
    private RouterInterface $router;


    public function __construct(Packages $assetsManager, RouterInterface $router)
    {
        $this->assetsManager = $assetsManager;
        $this->router = $router;
    }

    public function generateUrl($route, $parameters = [], $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH)
    {
        return $this->router->generate($route, $parameters, $referenceType);
    }

    public function asset($url)
    {
        return $this->assetsManager->getUrl($url);
    }

}
