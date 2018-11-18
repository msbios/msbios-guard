<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace MSBios\Guard\Provider\Identity;

use MSBios\Authentication\AuthenticationServiceAwareInterface;
use MSBios\Authentication\AuthenticationServiceAwareTrait;
use MSBios\Authentication\AuthenticationServiceInterface;
use MSBios\Authentication\IdentityInterface;
use MSBios\Guard\Provider\IdentityProviderInterface;
use MSBios\Guard\Provider\RoleProviderInterface;
use MSBios\Resource\RecordInterface;

/**
 * Class AuthenticationProvider
 * @package MSBios\Guard\Provider\Identity
 */
class AuthenticationProvider implements IdentityProviderInterface, AuthenticationServiceAwareInterface
{
    /** @var string */
    protected $defaultRole = 'GUEST';

    /** @var string */
    protected $authenticatedRole = 'USER';

    use AuthenticationServiceAwareTrait;

    /**
     * AuthenticationProvider constructor.
     * @param AuthenticationServiceInterface $authenticationService
     */
    public function __construct(AuthenticationServiceInterface $authenticationService)
    {
        $this->setAuthenticationService($authenticationService);
    }

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
     * @return string
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
        /** @var AuthenticationServiceInterface $authenticationService */
        $authenticationService = $this->getAuthenticationService();

        if ($authenticationService->hasIdentity()) {

            /** @var array $roles */
            $roles = [];

            /** @var IdentityInterface $identity */
            $identity = $authenticationService->getIdentity();

            if ($identity instanceof RoleProviderInterface) {

                /** @var mixed $role */
                foreach ($identity->getRoles() as $role) {
                    if ($role instanceof RecordInterface) {
                        $roles[] = $role->getCode();
                        continue;
                    }

                    $roles[] = $role;
                }
            }

            if (empty($roles)) {
                return [$this->authenticatedRole];
            }

            return $roles;
        }

        return [$this->defaultRole];
    }
}
