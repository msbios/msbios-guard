<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
namespace MSBios\Guard\Provider\Identity;

use MSBios\Guard\Provider\IdentityProviderInterface;

/**
 * Class AuthenticationProvider
 * @package MSBios\Guard\Provider\Identity
 */
class AuthenticationProvider implements IdentityProviderInterface
{
    /** @var string */
    protected $defaultRole = 'GUEST';

    /** @var string */
    protected $authenticatedRole = 'USER';

    /**
     * @return string
     */
    public function getDefaultRole()
    {
        return $this->defaultRole;
    }

    /**
     * @param $defaultRole
     * @return $this
     */
    public function setDefaultRole($defaultRole)
    {
        $this->defaultRole = $defaultRole;
        return $this;
    }

    /**
     * @return array
     */
    public function getAuthenticatedRole()
    {
        return $this->authenticatedRole;
    }

    /**
     * @param $authenticatedRole
     * @return $this
     */
    public function setAuthenticatedRole($authenticatedRole)
    {
        $this->authenticatedRole = $authenticatedRole;
        return $this;
    }

    /**
     * @return array
     */
    public function getIdentityRoles()
    {
        return [$this->defaultRole];
    }
}
