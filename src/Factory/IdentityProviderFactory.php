<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
namespace MSBios\Guard\Factory;

use Interop\Container\ContainerInterface;
use MSBios\Guard\Module;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class IdentityProviderFactory
 * @package MSBios\Guard\Factory
 */
class IdentityProviderFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return mixed
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return $container->get($container->get(Module::class)['identity_provider']);
    }
}
