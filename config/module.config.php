<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace MSBios\Guard;

use MSBios\Factory\ModuleFactory;
use MSBios\Guard\Form\JoinForm;
use MSBios\Guard\Form\LoginForm;
use Zend\InputFilter\InputFilterAbstractServiceFactory;
use Zend\Router\Http\Method;
use Zend\Router\Http\Segment;
use Zend\ServiceManager\Factory\InvokableFactory;
use Zend\Validator\Identical;
use Zend\Validator\NotEmpty;
use Zend\Validator\StringLength;

return [
    'router' => [
        'router_class' => Router\Http\TreeRouteStack::class,
        'default_params' => [
            // Specify default parameters here for all routes here ...
        ],
        'routes' => [
            'home' => [
                'may_terminate' => true,
                'child_routes' => [
                    'login' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => 'login[/]',
                            'defaults' => [
                                'controller' => Controller\GuardController::class,
                                'action' => 'login'
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes' => [
                            [
                                'type' => Method::class,
                                'options' => [
                                    'verb' => 'post'
                                ]
                            ]
                        ]
                    ],
                    'logout' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => 'logout[/]',
                            'defaults' => [
                                'controller' => Controller\GuardController::class,
                                'action' => 'logout'
                            ]
                        ]
                    ],
                    'join' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => 'join[/]',
                            'defaults' => [
                                'controller' => Controller\GuardController::class,
                                'action' => 'join'
                            ],
                        ],
                    ],
                    'reset' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => 'password_reset[/]',
                            'defaults' => [
                                'controller' => Controller\GuardController::class,
                                'action' => 'reset'
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],

    'controllers' => [
        'factories' => [
            Controller\GuardController::class =>
                Factory\GuardControllerFactory::class,
        ]
    ],

    'service_manager' => [
        'factories' => [
            // Authentication Service
            Authentication\Storage\SessionStorage::class =>
                Factory\ResourceStorageFactory::class,

            // Listeners
            ListenerAggregate::class =>
                Factory\ListenerAggregateFactory::class,

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

            // // Customs
            // Module::class =>
            //     ModuleFactory::class
        ],
    ],

    'table_manager' => [
        'aliases' => [
            Provider\Identity\AuthenticationProvider::class =>
                Resource\Table\RoleTableGateway::class
        ]
    ],

    'view_manager' => [
        'template_map' => [
            'error/403' =>
                __DIR__ . '/../view/error/403.phtml',
            'ms-bios/guard/guard/join' =>
                __DIR__ . '/../view/ms-bios/guard/guard/join.phtml',
            'ms-bios/guard/guard/reset' =>
                __DIR__ . '/../view/ms-bios/guard/guard/reset.phtml',
        ],
    ],

    'form_elements' => [
        'factories' => [
            Form\JoinForm::class =>
                Factory\JoinFormFactory::class,
            Form\LoginForm::class =>
                Factory\FormInvokableFactory::class,
        ]
    ],

    'input_filters' => [
        'abstract_factories' => [
            InputFilterAbstractServiceFactory::class =>
                InputFilterAbstractServiceFactory::class
        ],
        'factories' => [
            InputFilter\LoginInputFilter::class =>
                InvokableFactory::class
        ]
    ],

    'validators' => [
        'factories' => [
            Validator\Db\UsernameExists::class =>
                Factory\UsernameExistsFactory::class
        ]
    ],

    'input_filter_specs' => [
        JoinForm::class => [
            [
                'name' => 'username',
                'required' => true,
                'validators' => [
                    [
                        'name' => NotEmpty::class,
                    ], [
                        'name' => StringLength::class,
                        'options' => [
                            'min' => 8
                        ],
                    ],
                ],
            ], [
                'name' => 'password',
                'required' => true,
                'validators' => [
                    [
                        'name' => NotEmpty::class,
                    ], [
                        'name' => StringLength::class,
                        'options' => [
                            'min' => 8
                        ],
                    ],
                ],
            ], [
                'name' => 'confirm',
                'required' => true,
                'validators' => [
                    [
                        'name' => Identical::class,
                        'options' => [
                            'token' => 'password',
                        ],
                    ],
                ],
            ], [
                'name' => 'redirect',
                'required' => false,
                'validators' => [
                    // ...
                ],
            ]
        ],
        LoginForm::class => [
            [
                'name' => 'username',
                'required' => true,
                'validators' => [
                    [
                        'name' => NotEmpty::class,
                    ], [
                        'name' => StringLength::class,
                        'options' => [
                            'min' => 8
                        ],
                    ],
                ],
            ], [
                'name' => 'password',
                'required' => true,
                'validators' => [
                    // ...
                ],
            ], [
                'name' => 'redirect',
                'required' => false,
                'validators' => [
                    // ...
                ],
            ]
        ],
    ],

    \MSBios\Authentication\Module::class => [
        'default_authentication_storage' =>
            Authentication\Storage\SessionStorage::class,
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
                                ],
                            ],
                        ],
                    ],
                ],
            ],
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
    ],

    'listeners' => [
        ListenerAggregate::class
    ]
];
