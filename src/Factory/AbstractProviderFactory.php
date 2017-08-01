<?php
/**
 * @acceess protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace MSBios\Guard\Factory;

use Interop\Container\ContainerInterface;
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
            if (is_string($provider) && $container->has($provider)) {
                $providers[] = $container->get($provider);
                continue;
            } elseif (is_string($options) && $container->has($provider)) {
                $providers[] = $container->get($options);
                continue;
            }

            $providers[] = new $provider($container, $options);
        }

        return $providers;
    }
}
