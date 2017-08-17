<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
namespace MSBios\Guard\Provider;

use MSBios\Guard\Acl\Resource;
use Zend\Config\Config;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\ArrayUtils;

/**
 * Class ResourceProvider
 * @package MSBios\Guard\Provider
 */
class ResourceProvider implements ResourceProviderInterface, ProviderInterface
{
    /** @var  ServiceLocatorInterface */
    protected $serviceLocator;

    /** @var array */
    protected $resources = [];

    /**
     * ResourceProvider constructor.
     * @param ServiceLocatorInterface $serviceLocator
     * @param Config|null $config
     */
    public function __construct(ServiceLocatorInterface $serviceLocator, Config $config = null)
    {
        $this->serviceLocator = $serviceLocator;
        $this->resources = $config;

        /** @var array $resources */
        $resources = [];

        /**
         * @var mixed $key
         * @var mixed $value
         */
        foreach ($config as $key => $value) {
            if (is_string($value)) {
                $resources = ArrayUtils::merge($resources, $this->loadResource($value));
            }

            if ($value instanceof Config && is_string($key)) {
                $resources = ArrayUtils::merge($resources, $this->loadResource($key, $value));
            }
        }

        $this->resources = $resources;
    }

    /**
     * @param $identifier
     * @param Config|null $children
     * @param Resource|null $parent
     * @return array
     */
    protected function loadResource($identifier, Config $children = null, Resource $parent = null)
    {
        /** @var array $resources */
        $resources = [];

        /** @var string $resource */
        $resource = new Resource($identifier, $parent);
        $resources[] = $resource;

        if (! is_null($children)) {
            foreach ($children as $key => $value) {
                if (is_string($value)) {
                    $resources = ArrayUtils::merge(
                        $resources,
                        $this->loadResource($value, null, $resource)
                    );
                    continue;
                }

                $resources = ArrayUtils::merge(
                    $resources,
                    $this->loadResource($key, $value, $resource)
                );
            }
        }

        return $resources;
    }

    /**
     * @return mixed
     */
    public function getResources()
    {
        return $this->resources;
    }
}
