<?php
/**
 * @acceess protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace MSBios\Guard\Factory;

use Interop\Container\ContainerInterface;
use MSBios\Guard\Exception\InvalidProviderException;
use MSBios\Guard\Module;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class AbstractProviderFactory
 * @package MSBios\Guard\Factory
 */
abstract class AbstractProviderFactory implements FactoryInterface
{
    /** @const PROVIDER_KEY */
    const PROVIDER_KEY = '';

    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return array
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /** @var array $config */
        $config = $container->get(Module::class)[static::PROVIDER_KEY];

        /** @var array $providers */
        $providers = [];

        foreach ($config as $provider => $options) {
            if (is_string($options) && $container->has($options)) {
                $providers[] = $container->get($options);
            } elseif (is_string($provider) && $container->has($provider)) {
                $providers[] = $container->get($provider);
            } elseif (is_string($options) && class_exists($options)) {
                $providers[] = new $options($container);
            } elseif (class_exists($provider)) {
                $providers[] = new $provider($container, $options);
            } else {
                throw new InvalidProviderException('Incorrect registered provider');
            }
        }

        return $providers;
    }
}
