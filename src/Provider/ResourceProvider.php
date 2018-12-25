<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
namespace MSBios\Guard\Provider;

use MSBios\Permissions\Acl\Resource\Resource;
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
     * @param array|null $options
     */
    public function __construct(ServiceLocatorInterface $serviceLocator, array $options = null)
    {
        $this->serviceLocator = $serviceLocator;
        $this->resources = $options;

        /** @var array $resources */
        $resources = [];

        /**
         * @var mixed $key
         * @var mixed $value
         */
        foreach ($options as $key => $value) {
            if (is_string($value)) {
                $resources = ArrayUtils::merge($resources, $this->loadResource($value));
            }

            if (is_array($value)) {
                $resources = ArrayUtils::merge($resources, $this->loadResource($key, $value));
            }
        }
        $this->resources = $resources;
    }

    /**
     * @param $resourceId
     * @param array|null $children
     * @param Resource|null $parent
     * @return array
     */
    protected function loadResource($resourceId, array $children = null, Resource $parent = null)
    {
        /** @var array $resources */
        $resources = [];

        /** @var string $resource */
        $resource = new Resource($resourceId, $parent);
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
