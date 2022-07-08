<?php

namespace PN\ServiceBundle\Service;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class UserService
{

    protected $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    public function getUser()
    {
        return $this->tokenStorage->getToken()->getUser();
    }

    public function getUserName()
    {
        if ('cli' === PHP_SAPI) {
            return "System-CLI";
        }

        $user = $this->getUser();
        if (method_exists($user, 'getFullName')) {
            return $user->getFullName();
        } elseif (method_exists($user, 'getUserName')) {
            return $user->getUserName();
        }

        return "none";
    }

}
