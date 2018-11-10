<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
namespace MSBios\Guard\Provider\Role;

use MSBios\Guard\Provider\RoleProviderInterface;
use Zend\Config\Config;

/**
 * Class StaticProvider
 * @package MSBios\Guard\Provider\Role
 */
class StaticProvider implements RoleProviderInterface
{
    /** @var array */
    protected $roles;

    /**
     * StaticProvider constructor.
     * @param array $roles
     */
    public function __construct(array $roles)
    {
        $this->roles = $roles;
    }

    /**
     * @return array|mixed
     */
    public function getRoles()
    {
        /** @var mixed $role */
        foreach ($this->roles as $role) {
            // ...
        }

        return $this->roles;
    }
}
