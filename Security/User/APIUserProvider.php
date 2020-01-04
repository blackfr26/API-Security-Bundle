<?php
 
namespace DesarrolloHosting\ApiSecurityBundle\Security\User;
 
use DesarrolloHosting\ApiSecurityBundle\Security\User\APIUser;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
 
class APIUserProvider implements UserProviderInterface {
    
    public function getApiUser() {
        return $this->loadUserByUsername(false);
    }
 
    public function loadUserByUsername($username) {
        return new APIUser();
    }
 
    public function refreshUser(UserInterface $user) {
        if (!$user instanceof APIUser) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', get_class($user)));
        }
 
        return $this->loadUserByUsername($user->getUsername());
    }
 
    public function supportsClass($class) {
        return APIUser::class === $class;
    }
 
}