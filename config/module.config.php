<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace MSBios\Guard;

return [

    'view_manager' => [
        'template_map' => [
            'error/403' => __DIR__ . '/../view/error/403.phtml',
            'zend-developer-tools/toolbar/msbios-guard-authorize-role' =>
                __DIR__ . '/../view/zend-developer-tools/toolbar/msbios-guard-authorize-role.phtml',
        ],
    ],

    'service_manager' => [
        'factories' => [
            Collector\RoleCollector::class => \Zend\ServiceManager\Factory\InvokableFactory::class,

            // Providers
            Provider\GuardProviderInterface::class => Factory\ListenerFactory::class,
            Provider\IdentityProviderInterface::class => Factory\IdentityProviderFactory::class,
            Provider\Identity\AuthenticationProvider::class => Factory\Identity\AuthenticationProviderFactory::class,
            Provider\ResourceInterface::class => Factory\ResourceProvidersFactory::class,
            Provider\RoleProviderInterface::class => Factory\RoleProvidersFactory::class,
            Provider\RuleProviderInterface::class => Factory\RuleProvidersFactory::class,

            Service\AuthenticationService::class => Factory\AuthenticationServiceFactory::class,
            Module::class => Factory\ModuleFactory::class
        ]
    ],

    Module::class => [

        // default role for unauthenticated users
        'default_role' => 'GUEST',

        // default role for authenticated users (if using the identity provider)
        'authenticated_role' => 'USER',

        // identity provider service name
        'identity_provider' => Provider\Identity\AuthenticationProvider::class,

        // strategy service name for the strategy listener to be used when permission-related errors are detected
        'unauthorized_strategy' => '',

        // Template name for the unauthorized strategy
        'template' => 'error/403',

        // Role providers to be used to load all available roles into Zend\Permissions\Acl\Acl
        // Keys are the provider service names, values are the options to be passed to the provider
        'role_providers' => [
            Provider\RoleProvider::class => [
                // 'GUEST' => [],
                // 'USER' => [
                //     'children' => [
                //         'ADMIN' => []
                //     ]
                // ]

                'GUEST',
                'USER' => [
                    'MODERATOR' => [
                        'ADMIN'
                    ]
                ]
            ]
        ],

        // Resource providers to be used to load all available resources into Zend\Permissions\Acl\Acl
        // Keys are the provider service names, values are the options to be passed to the provider
        'resource_providers' => [
            Provider\ResourceProvider::class => [
                'NEWS',
                'FORUM' => [
                    'COMMENTS' => [
                        'CREATE', 'EDIT'
                    ]
                ],
            ]
        ],

        // Rule providers to be used to load all available rules into Zend\Permissions\Acl\Acl
        // Keys are the provider service names, values are the options to be passed to the provider
        'rule_providers' => [
            Provider\RuleProvider::class => [
                'allow' => [
                    // allow guests and users (and admins, through inheritance)
                    // the "wear" privilege on the resource "pants"
                    [['GUEST', 'USER'], 'NEWS', 'VIEW'],
                    [['USER'], 'FORUM', 'VIEW'],
                    [['USER'], 'COMMENTS', ['VIEW', 'CREATE']],
                    [['ADMIN'], 'COMMENTS', ['EDIT', 'DELETE']],
                ],
                'deny' => [
                    [['ADMIN'], 'COMMENTS', ['EDIT', 'DELETE']],
                ]
            ]
        ],

        // Guard listeners to be attached to the application event manager
        'guard_listeners' => [
            // Listener\ControllerListenerAggregate::class => [
            //     [
            //         'controller' => \MSBios\Application\Controller\IndexController::class,
            //         'action' => 'index',
            //         'roles' => ['GUEST']
            //     ]
            // ],
            // Listener\RouteListenerAggregate::class => [
            //     [
            //         'route' => 'home',
            //         'roles' => ['GUEST']
            //     ]
            // ]
        ]
    ],


    'zenddevelopertools' => [
        'profiler' => [
            'collectors' => [
                'msbios_guard_authorize_role_collector' => Collector\RoleCollector::class,
            ],
        ],
        'toolbar' => [
            'entries' => [
                'msbios_guard_authorize_role_collector' => 'zend-developer-tools/toolbar/msbios-guard-authorize-role',
            ],
        ],
    ],
];
