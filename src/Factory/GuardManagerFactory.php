<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
namespace MSBios\Guard\Factory;

use Interop\Container\ContainerInterface;
use MSBios\Guard\GuardManager;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class GuardManagerFactory
 * @package MSBios\Guard\Factory
 */
class GuardManagerFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return GuardManager
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new GuardManager($container);
    }
}
