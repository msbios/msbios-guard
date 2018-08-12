<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
namespace MSBios\Guard\Factory;

use Interop\Container\ContainerInterface;
use MSBios\Db\TablePluginManager;
use MSBios\Guard\Authentication\Storage\ResourceStorage;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class ResourceStorageFactory
 * @package MSBios\Guard\Factory
 */
class ResourceStorageFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return ResourceStorage
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new ResourceStorage(
            $container->get(TablePluginManager::class)
        );
    }
}