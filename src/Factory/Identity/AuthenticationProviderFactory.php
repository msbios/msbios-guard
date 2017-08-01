<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
namespace MSBios\Guard\Factory\Identity;

use Interop\Container\ContainerInterface;
use MSBios\Guard\Module;
use MSBios\Guard\Provider\Identity\AuthenticationProvider;
use Zend\Config\Config;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class AuthenticationProviderFactory
 * @package MSBios\Guard\Factory\Identity
 */
class AuthenticationProviderFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return $this
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /** @var Config $config */
        $config = $container->get(Module::class);

        /** @var AuthenticationProvider $provider */
        return (new AuthenticationProvider)
            ->setDefaultRole($config->get('default_role'))
            ->setAuthenticatedRole($config->get('authenticated_role'));
    }
}
