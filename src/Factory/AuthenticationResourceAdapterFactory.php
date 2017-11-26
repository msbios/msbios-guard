<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace MSBios\Guard\Factory;

use Interop\Container\ContainerInterface;
use MSBios\Guard\Authentication\Adapter\ResourceAdapter;
use Zend\Db\Adapter\AdapterInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class AuthenticationResourceAdapterFactory
 * @package MSBios\Guard\Factory
 */
class AuthenticationResourceAdapterFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return ResourceAdapter
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new ResourceAdapter(
            $container->get(AdapterInterface::class),
            'acl_t_users',
            'username',
            'password'
        );
    }
}
