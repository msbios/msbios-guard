<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
namespace MSBios\Guard\Provider;

use MSBios\Guard\Acl\Role;
use Zend\Config\Config;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\ArrayUtils;

/**
 * Class RoleProvider
 * @package MSBios\Guard\Provider
 */
class RoleProvider implements RoleProviderInterface, ProviderInterface
{
    /** @var  ServiceLocatorInterface */
    protected $serviceLocator;

    /** @var array */
    protected $roles = [];

    /**
     * RoleProvider constructor.
     * @param ServiceLocatorInterface $serviceLocator
     * @param Config|null $config
     */
    public function __construct(ServiceLocatorInterface $serviceLocator, Config $config = null)
    {
        $this->serviceLocator = $serviceLocator;

        /** @var array $roles */
        $roles = [];

        /**
         * @var mixed $key
         * @var mixed $value
         */
        foreach ($config as $key => $value) {
            if (is_string($value)) {
                $roles = ArrayUtils::merge($roles, $this->loadRole($value));
            }

            if ($value instanceof Config && is_string($key)) {
                $roles = ArrayUtils::merge($roles, $this->loadRole($key, $value));
            }
        }

        $this->roles = $roles;
    }

    /**
     * @param $identifier
     * @param Config|null $children
     * @param null $parent
     * @return array
     */
    protected function loadRole($identifier, Config $children = null, $parent = null)
    {
        /** @var array $roles */
        $roles = [];
        /** @var string $role */
        $role = new Role($identifier, $parent);
        $roles[] = $role;

        if (! is_null($children)) {
            foreach ($children as $key => $value) {
                if (is_string($value)) {
                    $roles = ArrayUtils::merge($roles, $this->loadRole($value, null, $role));
                    continue;
                }

                $roles = ArrayUtils::merge($roles, $this->loadRole($key, $value, $role));
            }
        }

        return $roles;
    }

    /**
     * @return array
     */
    public function getRoles()
    {
        return $this->roles;
    }
}
