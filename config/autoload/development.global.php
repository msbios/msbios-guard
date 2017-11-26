<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace MSBios\Guard;

use Zend\Router\Http\Literal;
use Zend\ServiceManager\Factory\InvokableFactory;

return [

    'db' => [
        'dsn' => 'mysql:dbname=portal.dev;host=127.0.0.1',
        'username' => 'root',
        'password' => 'root',
    ],

    'router' => [
        'routes' => [
            'home' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action' => 'index',
                    ],
                ],
            ],
        ],
    ],

    'controllers' => [
        'factories' => [
            Controller\IndexController::class =>
                Factory\IndexControllerFactory::class,
        ]
    ],

    'view_manager' => [
        //'display_not_found_reason' => true,
        //'display_exceptions' => true,
        //'doctype' => 'HTML5',
        //'not_found_template' => 'error/404',
        //'exception_template' => 'error/index',
        //'template_map' => [
        //    // 'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
        //    // 'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
        //    // 'error/404'               => __DIR__ . '/../view/error/404.phtml',
        //    // 'error/index'             => __DIR__ . '/../view/error/index.phtml',
        //],
        'template_path_stack' => [
            __DIR__ . '/../../view',
        ],
    ],

    \MSBios\Assetic\Module::class => [

        'paths' => [
            __DIR__ . '/../../vendor/msbios/cpanel/themes/limitless/public',
        ],

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


//            // css
//            'limitless/assets/css/icons/fontawesome/styles.min.css' =>
//                __DIR__ . '/../../themes/limitless/public/assets/css/icons/fontawesome/styles.min.css',
//            'limitless/assets/css/bootstrap.css' =>
//                __DIR__ . '/../../themes/limitless/public/assets/css/bootstrap.css',
//            'limitless/assets/css/colors.css' =>
//                __DIR__ . '/../../themes/limitless/public/assets/css/colors.css',
//            'limitless/assets/css/components.css' =>
//                __DIR__ . '/../../themes/limitless/public/assets/css/components.css',
//            'limitless/assets/css/core.css' =>
//                __DIR__ . '/../../themes/limitless/public/assets/css/core.css',
//            'limitless/assets/css/icons/icomoon/styles.css' =>
//                __DIR__ . '/../../themes/limitless/public/assets/css/icons/icomoon/styles.css',
//            'limitless/assets/css/icons/icomoon/fonts/icomoon.woff' =>
//                __DIR__ . '/../../themes/limitless/public/assets/css/icons/icomoon/fonts/icomoon.woff',
//            'limitless/assets/css/icons/icomoon/fonts/icomoon.ttf' =>
//                __DIR__ . '/../../themes/limitless/public/assets/css/icons/icomoon/fonts/icomoon.ttf',
//            // js
//            'limitless/assets/js/plugins/loaders/pace.min.js' =>
//                __DIR__ . '/../../themes/limitless/public/assets/js/plugins/loaders/pace.min.js',
//            'limitless/assets/js/core/libraries/jquery.min.js' =>
//                __DIR__ . '/../../themes/limitless/public/assets/js/core/libraries/jquery.min.js',
//            'limitless/assets/js/core/libraries/bootstrap.min.js' =>
//                __DIR__ . '/../../themes/limitless/public/assets/js/core/libraries/bootstrap.min.js',
//            'limitless/assets/js/plugins/loaders/blockui.min.js' =>
//                __DIR__ . '/../../themes/limitless/public/assets/js/plugins/loaders/blockui.min.js',
//            'limitless/assets/js/core/app.js' =>
//                __DIR__ . '/../../themes/limitless/public/assets/js/core/app.js',
//            // images
//            'limitless/assets/images/logo_light.png' =>
//                __DIR__ . '/../../themes/limitless/public/assets/images/logo_light.png',
//            'limitless/assets/images/logo_light_msbios.png' =>
//                __DIR__ . '/../../themes/limitless/public/assets/images/logo_light_msbios.png',
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
                'route/home',
                Controller\IndexController::class
            ]
        ],

        // Rule providers to be used to load all available rules into Zend\Permissions\Acl\Acl
        // Keys are the provider service names, values are the options to be passed to the provider
        'rule_providers' => [
            Provider\RuleProvider::class => [
                'allow' => [
                    [['USER'], Controller\IndexController::class],
                ],
                'deny' => []
            ]
        ],
    ]
];
