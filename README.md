ApiSecurityBundle
This bundle allows you to add a simple security to your API endpoints. It can also be used to restrict access to other areas.

When an authorized request is received, an user will be created with the role ROLE_SECURED_API_USER

Installation
Enable the Bundle in your AppKernel.php

public function registerBundles() { $bundles = [ // ... new DesarrolloHosting\ApiSecurityBundle\ApiSecurityBundle(), ];

 // ...
}

Edit your security settings in security.yml:

providers: api_user: id: desarrollo_hosting_api_security_user_provider

firewalls: secured_api: pattern: ^/api/secured/ stateless: true guard: provider: api_user authenticator: desarrollo_hosting_api_security_authenticator~

access_control: - { path: ^/api/secured/, roles: ROLE_SECURED_API_USER }

Configure the Bundle with the parameteres needed in config.yml

Configuration
You can configure the bundle adding this parameters in your config.yml file:

api_security: allow_localhost: (bool, defaults true) api_key: (string, defaults false) authorized_ips: (array/string with only 1 IP, defaults false) auth_header: (string, defaults "X-AUTH-TOKEN")

allow_localhost: when true, any request comming from 127.0.0.1 or ::1 will be authorized
api_key: string that the client must send in the auth header. If a falsey value is provided, this check will be skipped
authorized_ips: list of authorized ips or ranges in CIDR format. If a falsey value is provided, this check will be skipped
auth_header: header where to read the client's api key from.