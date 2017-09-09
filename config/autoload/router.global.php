<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
namespace MSBios\Guard;

use Zend\Router\Http\Literal;

return [
    'router' => [
        'routes' => [
            'home' => [
                'type' => Router\Http\Literal::class,
                'options' => [
                    'route' => '/'
                ],
            ],
        ],
        'default_params' => [
            // Specify default parameters here for all routes here ...
        ]
    ],
];
