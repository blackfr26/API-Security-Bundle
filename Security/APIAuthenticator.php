<?php
 
namespace DesarrolloHosting\ApiSecurityBundle\Security;
 
use DesarrolloHosting\ApiSecurityBundle\Security\Exceptions\WrongApiKeyException;
use DesarrolloHosting\ApiSecurityBundle\Security\Exceptions\UnauthorizedIPException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
 
class APIAuthenticator extends AbstractGuardAuthenticator {
 
    private $auth_header;
    private $logger;
    private $allow_localhost;
    private $api_key;
    private $authorized_ips;
 
    public function __construct($logger, $config) {
        $this->logger = $logger;
        $this->allow_localhost = $config['allow_localhost'];
        $this->api_key = $config['api_key'];
        $this->authorized_ips = $config['authorized_ips'];
        $this->auth_header = $config['auth_header'];
    }
 
    /**
     * Called on every request. Return whatever credentials you want to
     * be passed to getUser() as $credentials.
     */
    public function getCredentials(Request $request) {
        $ip = $request->server->get('SERVER_ADDR');
        $endpoint = $request->server->get('REQUEST_URI');
 
        if ($this->allow_localhost && in_array($ip, array("127.0.0.1", "::1"))) {
            return array(
                'endpoint' => $endpoint,
                'localhost' => true
            );
        }
 
        if ($this->api_key && !$request->headers->has($this->auth_header)) {
            $this->logger->error("API access error", array("message" => "Auth header not received", "header" => $this->auth_header, "endpoint" => $endpoint, "ip" => $ip));
            return null;
        }
 
        return array(
            'endpoint' => $endpoint,
            'token' => $request->headers->get($this->auth_header),
            'ip' => $ip
        );
    }
 
    public function getUser($credentials, UserProviderInterface $userProvider) {
        return $userProvider->getApiUser();
    }
 
    public function checkCredentials($credentials, UserInterface $user) {
        $endpoint = $credentials['endpoint'];
        
        if (array_key_exists('localhost', $credentials) && $credentials['localhost']) {
            $this->logger->info("Localhost API access", array("endpoint" => $endpoint));
            return true;
        }
 
        $token = $credentials['token'];
        $ip = $credentials['ip'];
 
        if ($this->api_key && $token !== $this->api_key) {
            $this->logger->error("API access error", array("message" => "Wrong API key", "endpoint" => $endpoint, "token" => $token, "ip" => $ip));
            throw new WrongApiKeyException();
        }
        
        if($this->authorized_ips){
            foreach($this->authorized_ips as $authorized_ip){
                if (\Symfony\Component\HttpFoundation\IpUtils::checkIp($ip, $authorized_ip)) {
                    return true;
                }
            }
            
            throw new UnauthorizedIPException();
        }
        
        return true;
    }
 
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey) {
        // on success, let the request continue
        return null;
    }
 
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception) {
        $message = strtr($exception->getMessageKey(), $exception->getMessageData());
 
        return new JsonResponse(array('success' => false, 'message' => "Authentication Failed: $message"), Response::HTTP_FORBIDDEN);
    }
 
    /**
     * Called when authentication is needed, but it's not sent
     */
    public function start(Request $request, AuthenticationException $authException = null) {
        return new JsonResponse(array('success' => false, 'message' => 'Authentication Required'), Response::HTTP_UNAUTHORIZED);
    }
 
    public function supportsRememberMe() {
        return false;
    }
 
}