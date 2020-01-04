<?php
 
namespace DesarrolloHosting\ApiSecurityBundle\Security\User;
 
use Symfony\Component\Security\Core\User\UserInterface;
 
class APIUser implements UserInterface {
 
    private $username = "API User";
    private $password = false;
    private $salt = false;
    private $roles = array("ROLE_SECURED_API_USER");
    
    public function getRoles() {
        return $this->roles;
    }
 
    public function getPassword() {
        return $this->password;
    }
 
    public function getSalt() {
        return $this->salt;
    }
 
    public function getUsername() {
        return $this->username;
    }
 
    public function eraseCredentials() {}
 
    public function isEqualTo(UserInterface $user) {
        if ($user instanceof APIUser) {
            return true;
        }
    }
 
}