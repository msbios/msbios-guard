<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace MSBios\Guard;

use MSBios\Db\TableManagerInitializer;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\ServiceManager\Factory\InvokableFactory;

return [

    'db' => [
        'dsn' => 'mysql:dbname=portal.dev;host=127.0.0.1',
        'username' => 'root',
        'password' => 'root',
    ],

    'service_manager' => [
        'initializers' => [
            TableManagerInitializer::class =>
            new TableManagerInitializer
        ],
    ],

    'controllers' => [
        'factories' => [
            Controller\IndexController::class =>
                InvokableFactory::class,
        ],
        'aliases' => [
            \MSBios\Application\Controller\IndexController::class =>
                Controller\IndexController::class
        ]
    ],

    'view_manager' => [
        'template_map' => [
            'ms-bios/guard/index/index' =>
                __DIR__ . '/../../view/ms-bios/guard/index/index.phtml',
        ],
    ],

    \MSBios\Assetic\Module::class => [
        'maps' => [
            // css
            'default/css/bootstrap.min.css' =>
                __DIR__ . '/../../vendor/msbios/application/themes/default/public/css/bootstrap.min.css',
            'default/css/bootstrap-theme.min.css' =>
                __DIR__ . '/../../vendor/msbios/application/themes/default/public/css/bootstrap-theme.min.css',
            'default/css/style.css' =>
                __DIR__ . '/../../vendor/msbios/application/themes/default/public/css/style.css',

            // js
            'default/js/jquery-3.1.0.min.js' =>
                __DIR__ . '/../../vendor/msbios/application/themes/default/public/js/jquery-3.1.0.min.js',
            'default/js/bootstrap.min.js' =>
                __DIR__ . '/../../vendor/msbios/application/themes/default/public/js/bootstrap.min.js',

            // imgs
            'default/img/zf-logo-mark.svg' =>
                __DIR__ . '/../../vendor/msbios/application/themes/default/public/img/zf-logo-mark.svg',
        ],
    ],

    Module::class => [
        'role_providers' => [
            Provider\RoleProvider::class => [
            ]
        ],

        // Resource providers to be used to load all available resources into Zend\Permissions\Acl\Acl
        // Keys are the provider service names, values are the options to be passed to the provider
        'resource_providers' => [
            Provider\ResourceProvider::class => [
                AbstractActionController::class => [
                    \MSBios\Application\Controller\IndexController::class
                ]
            ]
        ],

        // Rule providers to be used to load all available rules into Zend\Permissions\Acl\Acl
        // Keys are the provider service names, values are the options to be passed to the provider
        'rule_providers' => [
            Provider\RuleProvider::class => [
                'allow' => [
                    [['USER'], \MSBios\Application\Controller\IndexController::class],
                ],
                'deny' => []
            ]
        ],
    ]
];
