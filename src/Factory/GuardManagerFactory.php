<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace MSBios\Guard\Factory;

use Interop\Container\ContainerInterface;
use MSBios\Guard\GuardManager;
use MSBios\Guard\Provider\IdentityProviderInterface;
use MSBios\Guard\Provider\ResourceProviderInterface;
use MSBios\Guard\Provider\RoleProviderInterface;
use MSBios\Guard\Provider\RuleProviderInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class GuardManagerFactory
 * @package MSBios\Guard\Factory
 */
class GuardManagerFactory implements FactoryInterface
{
    /**
     * @inheritdoc
     *
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return GuardManager|object
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {

        /** @var IdentityProviderInterface $identityProvider */
        $identityProvider = $container->get(IdentityProviderInterface::class);

        /** @var array $resourceProvider */
        $resourceProvider = $container->get(ResourceProviderInterface::class);

        /** @var array $roleProvider */
        $roleProvider = $container->get(RoleProviderInterface::class);

        /** @var array $ruleProvider */
        $ruleProvider = $container->get(RuleProviderInterface::class);

        return new GuardManager(
            $identityProvider,
            $resourceProvider,
            $roleProvider,
            $ruleProvider
        );
    }
}
