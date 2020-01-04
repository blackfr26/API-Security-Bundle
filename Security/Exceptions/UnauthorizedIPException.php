<?php
 
namespace DesarrolloHosting\ApiSecurityBundle\Security\Exceptions;
 
use Symfony\Component\Security\Core\Exception\AuthenticationException;
 
class UnauthorizedIPException extends AuthenticationException {
 
    public function getMessageKey() {
        return 'Unauthorized IP';
    }
 
}