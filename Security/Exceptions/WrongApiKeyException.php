<?php
 
namespace DesarrolloHosting\ApiSecurityBundle\Security\Exceptions;
 
use Symfony\Component\Security\Core\Exception\AuthenticationException;
 
class WrongApiKeyException extends AuthenticationException {
 
    public function getMessageKey() {
        return 'Wrong API Key';
    }
 
}