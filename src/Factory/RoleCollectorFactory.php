<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
namespace MSBios\Guard\Factory;

use Interop\Container\ContainerInterface;
use MSBios\Guard\Collector\RoleCollector;
use MSBios\Guard\Provider\IdentityProviderInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class RoleCollectorFactory
 * @package MSBios\Guard\Factory
 */
class RoleCollectorFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return RoleCollector
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new RoleCollector(
            $container->get(IdentityProviderInterface::class)
        );
    }
}