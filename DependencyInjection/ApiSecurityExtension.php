<?php

namespace DesarrolloHosting\ApiSecurityBundle\DependencyInjection;
 
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;
 
/**
 * This is the class that loads and manages your bundle configuration.
 *
 * @link http://symfony.com/doc/current/cookbook/bundles/extension.html
 */
class ApiSecurityExtension extends Extension {
 
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container) {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);
        
        $container->setParameter('api_security.config', array(
            "allow_localhost" => $config['allow_localhost'],
            "api_key" => $config['api_key'],
            "authorized_ips" => $config['authorized_ips'],
            "auth_header" => $config['auth_header'],
        ));
        
        $loader = new Loader\YamlFileLoader($container, new FileLocator(_DIR_ . '/../Resources/config'));
        $loader->load('services.yml');
    }
 
    public function getAlias() {
        return 'api_security';
    }
 
}