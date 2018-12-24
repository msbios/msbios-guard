<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace MSBios\Guard\Factory;

use Interop\Container\ContainerInterface;
use MSBios\Guard\GuardManager;
use MSBios\Guard\ListenerAggregate;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class ListenerAggregateFactory
 * @package MSBios\Guard\Factory
 */
class ListenerAggregateFactory implements FactoryInterface
{
    /**
     * @inheritdoc
     *
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return ListenerAggregate|object
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new ListenerAggregate($container->get(GuardManager::class));
    }
}
