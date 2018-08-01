<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace MSBios\Guard;

use Zend\ServiceManager\Factory\InvokableFactory;

return [

    'router' => [
        'router_class' => Router\Http\TreeRouteStack::class,
        'default_params' => [
            // Specify default parameters here for all routes here ...
        ]
    ],

    'service_manager' => [
        'factories' => [
            // Authentication Service
            \Zend\Authentication\AuthenticationService::class =>
                Factory\AuthenticationServiceFactory::class,
            Authentication\Storage\ResourceStorage::class =>
                InvokableFactory::class,
            Authentication\Adapter\ResourceAdapter::class =>
                Factory\AuthenticationResourceAdapterFactory::class,

            // Listeners
            Listener\DispatchListener::class =>
                InvokableFactory::class,
            Listener\ForbiddenListener::class =>
                InvokableFactory::class,
            Listener\RouteListener::class =>
                InvokableFactory::class,

            // Providers
            Provider\IdentityProviderInterface::class =>
                Factory\IdentityProviderFactory::class,
            Provider\Identity\AuthenticationProvider::class =>
                Factory\Identity\AuthenticationProviderFactory::class,
            Provider\ResourceProviderInterface::class =>
                Factory\ResourceProvidersFactory::class,
            Provider\RoleProviderInterface::class =>
                Factory\RoleProvidersFactory::class,
            Provider\RuleProviderInterface::class =>
                Factory\RuleProvidersFactory::class,

            // Managers
            GuardManager::class =>
                Factory\GuardManagerFactory::class,

            // Customs
            Module::class =>
                Factory\ModuleFactory::class
        ],
    ],

    'table_manager' => [
        'aliases' => [
            Authentication\Storage\ResourceStorage::class =>
                Resource\Table\UserTableGateway::class,
            Provider\Identity\AuthenticationProvider::class =>
                Resource\Table\RoleTableGateway::class
        ]
    ],

    'view_manager' => [
        'template_map' => [
            'error/403' => __DIR__ . '/../view/error/403.phtml',
        ],
    ],

    'form_elements' => [
        'factories' => [
            Form\LoginForm::class =>
                Factory\LoginFormFactory::class,
        ]
    ],

    'input_filters' => [
        'factories' => [
            InputFilter\LoginInputFilter::class =>
                InvokableFactory::class
        ],
        'aliases' => [
            Form\LoginForm::class =>
                InputFilter\LoginInputFilter::class
        ]
    ],

    Module::class => [

        // default role for unauthenticated users
        'default_role' => 'GUEST',

        // default role for authenticated users (if using the identity provider)
        'authenticated_role' => 'USER',

        // identity provider service name
        'identity_provider' => Provider\Identity\AuthenticationProvider::class,

        // Template name for the unauthorized strategy
        'template' => 'error/403',

        // Define roles
        'roles' => [
            'GUEST',
            'GUEST' => ['USER'],
            'USER' => ['MODERATOR'],
            'MODERATOR' => ['ADMIN'],
            'ADMIN' => ['SUPERADMIN'],
            'SUPERADMIN' => 'DEVELOPER'
        ],

        // Role providers to be used to load all available roles into Zend\Permissions\Acl\Acl
        // Keys are the provider service names, values are the options to be passed to the provider
        'role_providers' => [
            Provider\RoleProvider::class => [
                'GUEST' => [
                    'USER' => [
                        'MODERATOR' => [
                            'ADMIN' => [
                                'SUPERADMIN' => [
                                    'DEVELOPER'
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ],

        // Resource providers to be used to load all available resources into Zend\Permissions\Acl\Acl
        // Keys are the provider service names, values are the options to be passed to the provider
        'resource_providers' => [
            Provider\ResourceProvider::class => []
        ],

        // Rule providers to be used to load all available rules into Zend\Permissions\Acl\Acl
        // Keys are the provider service names, values are the options to be passed to the provider
        'rule_providers' => [
            Provider\RuleProvider::class => [
                'allow' => [],
                'deny' => []
            ]
        ],

        'listeners' => [
            Listener\RouteListener::class => [
                'listener' => Listener\RouteListener::class,
                'method' => 'onRoute',
                'event' => \Zend\Mvc\MvcEvent::EVENT_ROUTE,
                'priority' => 1,
            ],
            Listener\DispatchListener::class => [
                'listener' => Listener\DispatchListener::class,
                'method' => 'onDispatch',
                'event' => \Zend\Mvc\MvcEvent::EVENT_DISPATCH,
                'priority' => 1,
            ],
            Listener\ForbiddenListener::class => [
                'listener' => Listener\ForbiddenListener::class,
                'method' => 'onDispatchError',
                'event' => \Zend\Mvc\MvcEvent::EVENT_DISPATCH_ERROR,
                'priority' => -100900,
            ],
        ]
    ],
];
