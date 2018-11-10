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
use Zend\Stdlib\ArrayUtils;

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
     * @return array|object
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /** @var array $defaultOptions */
        $defaultOptions = $container->get(Module::class)[static::PROVIDER_KEY];

        /** @var array $options */
        $options = is_null($options)
            ? $defaultOptions : ArrayUtils::merge($options, $defaultOptions);

        /** @var array $providers */
        $providers = [];

        foreach ($options as $provider => $config) {
            if (is_string($config) && $container->has($config)) {
                $providers[] = $container->get($config);
            } elseif (is_string($provider) && $container->has($provider)) {
                $providers[] = $container->get($provider);
            } elseif (is_string($config) && class_exists($config)) {
                $providers[] = new $config($container);
            } elseif (class_exists($provider)) {
                $providers[] = new $provider($container, $config);
            } else {
                throw new InvalidProviderException('Incorrect registered provider');
            }
        }

        return $providers;
    }
}
