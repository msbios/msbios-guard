<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
namespace MSBios\Guard\Factory;

use Interop\Container\ContainerInterface;
use MSBios\Guard\Provider\RoleManager;
use MSBios\Guard\Provider\RoleProviderInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class RoleManagerFactory
 * @package MSBios\Guard\Factory
 */
class RoleManagerFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return RoleManager|RoleProviderInterface
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /** @var RoleProviderInterface $roleManager */
        $roleManager = new RoleManager;

        // foreach () {
        //
        // }

        return $roleManager;
    }
}