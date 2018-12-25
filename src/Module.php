<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
namespace MSBios\Guard;

use Interop\Container\ContainerInterface;
use Zend\ModuleManager\Feature\ViewHelperProviderInterface;

/**
 * Class Module
 * @package MSBios\Guard
 */
class Module extends \MSBios\Module implements ViewHelperProviderInterface
{
    /** @const VERSION */
    const VERSION = '1.0.32';

    /**
     * @inheritdoc
     *
     * @return string
     */
    protected function getDir()
    {
        return __DIR__;
    }

    /**
     * @inheritdoc
     *
     * @return string
     */
    protected function getNamespace()
    {
        return __NAMESPACE__;
    }

    /**
     * @inheritdoc
     *
     * @return array|\Zend\ServiceManager\Config
     */
    public function getViewHelperConfig()
    {
        return [
            'factories' => [
                'isAllowed' => function (ContainerInterface $container) {
                    return new View\Helper\IsAllowed(
                        $container->get(GuardManager::class)
                    );
                }
            ],
        ];
    }
}
