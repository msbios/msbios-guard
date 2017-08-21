<?php

namespace MSBios\Guard;

return [
    Module::class => [
        'role_providers' => [
            Provider\RoleProvider::class => [
            ]
        ],

        // Resource providers to be used to load all available resources into Zend\Permissions\Acl\Acl
        // Keys are the provider service names, values are the options to be passed to the provider
        'resource_providers' => [
            Provider\ResourceProvider::class => [
                'route/home'
            ]
        ],

        // Rule providers to be used to load all available rules into Zend\Permissions\Acl\Acl
        // Keys are the provider service names, values are the options to be passed to the provider
        'rule_providers' => [
            Provider\RuleProvider::class => [
                'allow' => [],
                'deny' => []
            ]
        ],
    ]
];