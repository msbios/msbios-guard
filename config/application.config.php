<?php
/**
 * If you need an environment-specific system or application configuration,
 * there is an example in the documentation
 * @see https://docs.zendframework.com/tutorials/advanced-config/#environment-specific-system-configuration
 * @see https://docs.zendframework.com/tutorials/advanced-config/#environment-specific-application-configuration
 */
return [
    // Retrieve list of modules used in this application.
    'modules' => [

//        'Zend\Mvc\Plugin\FlashMessenger',
//        'Zend\Cache',
//        'Zend\Form',
//        'Zend\InputFilter',
//        'Zend\Filter',
//        'Zend\Paginator',
//        'Zend\Hydrator',
        'Zend\I18n',
//        'Zend\Session',
        'Zend\Navigation',
        'Zend\Router',
//        'Zend\Validator',
//        'DoctrineModule',
//        'DoctrineORMModule',
        'MSBios\Assetic',
        'MSBios\Theme',
        'MSBios\Widget',
        'MSBios\Navigation',
        'MSBios\Application',
        'MSBios\Guard',

        'Zend\Log',
        'ZendDeveloperTools',

//        'MSBios\Authentication',
//        'MSBios\CPanel',
//        'MSBios\Guard\CPanel',
//        'MSBios\Guard\Resource',
//        'MSBios\Resource'
    ],

    'module_listener_options' => [
        'module_paths' => [
            './module',
            './vendor',
        ],
        'config_glob_paths' => [
            realpath(__DIR__) . '/autoload/{{,*.}global,{,*.}local}.php',
        ],
        'config_cache_enabled' => false,
        // 'config_cache_key' => 'application.config.cache',
        'module_map_cache_enabled' => false,
        // 'module_map_cache_key' => 'application.module.cache',
        'cache_dir' => 'data/cache/',
    ],
];
