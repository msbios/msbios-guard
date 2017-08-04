<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace MSBios\Guard;

use Interop\Container\ContainerInterface;
use MSBios\Guard\Listener\ForbiddenListener;
use MSBios\Guard\Provider\GuardProviderInterface;
use MSBios\Guard\Service\GuardManager;
use MSBios\ModuleInterface;
use Zend\EventManager\EventInterface;
use Zend\Loader\AutoloaderFactory;
use Zend\Loader\StandardAutoloader;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\BootstrapListenerInterface;
use Zend\ModuleManager\Feature\ViewHelperProviderInterface;

/**
 * Class Module
 * @package MSBios\Guard
 */
class Module implements
    ModuleInterface,
    BootstrapListenerInterface,
    ViewHelperProviderInterface,
    AutoloaderProviderInterface
{
    /** @const VERSION */
    const VERSION = '0.0.1';

    /**
     * @return mixed
     */
    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    /**
     * Listen to the bootstrap event
     *
     * @param EventInterface $e
     * @return array
     */
    public function onBootstrap(EventInterface $e)
    {
        /** @var Application $target */
        $target = $e->getTarget();

        /** @var array $listeners */
        $listeners = $target->getServiceManager()
            ->get(GuardProviderInterface::class);

        foreach ($listeners as $listener) {
            $listener->attach($target->getEventManager());
        }

        (new ForbiddenListener)->attach($target->getEventManager());
    }

    /**
     * Expected to return \Zend\ServiceManager\Config object or array to
     * seed such an object.
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

    /**
     * Return an array for passing to Zend\Loader\AutoloaderFactory.
     *
     * @return array
     */
    public function getAutoloaderConfig()
    {
        return [
            AutoloaderFactory::STANDARD_AUTOLOADER => [
                StandardAutoloader::LOAD_NS => [
                    __NAMESPACE__ => __DIR__,
                ],
            ],
        ];
    }
}
