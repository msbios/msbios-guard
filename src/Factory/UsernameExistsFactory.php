<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
namespace MSBios\Guard\Factory;

use Interop\Container\ContainerInterface;
use MSBios\Guard\Validator\Db\UsernameExists;
use Zend\Db\Adapter\AdapterInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class UsernameExistsFactory
 * @package MSBios\Guard\Factory
 */
class UsernameExistsFactory implements FactoryInterface
{
    /**
     * @inheritdoc
     *
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new UsernameExists($container->get(AdapterInterface::class));
    }
}