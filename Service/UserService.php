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
        $token = $this->tokenStorage->getToken();
        if (!$token) {
            return null;
        }

        return $token->getUser();
    }

    public function getUserName(): string
    {
        if ('cli' === PHP_SAPI) {
            return "System-CLI";
        }

        $user = $this->getUser();
        if ($user == null) {
            return "none";
        }
        if (method_exists($user, 'getFullName')) {
            return $user->getFullName();
        } elseif (method_exists($user, 'getName')) {
            return $user->getName();
        } elseif (method_exists($user, 'getUserName')) {
            return $user->getUserName();
        }

        return "none";
    }

}
