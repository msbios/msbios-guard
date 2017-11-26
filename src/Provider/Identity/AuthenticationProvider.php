<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace MSBios\Guard\Provider\Identity;

use MSBios\Authentication\IdentityInterface;
use MSBios\Db\TableManagerAwareInterface;
use MSBios\Db\TableManagerAwareTrait;
use MSBios\Guard\Provider\IdentityProviderInterface;
use MSBios\Guard\Provider\RoleProviderInterface;
use MSBios\Guard\Resource\Table\RoleTableGateway;
use Zend\Authentication\AuthenticationServiceInterface;
use Zend\Db\ResultSet\ResultSetInterface;

/**
 * Class AuthenticationProvider
 * @package MSBios\Guard\Provider\Identity
 */
class AuthenticationProvider implements IdentityProviderInterface, TableManagerAwareInterface
{
    use TableManagerAwareTrait;

    /** @var string */
    protected $defaultRole = 'GUEST';

    /** @var string */
    protected $authenticatedRole = 'USER';

    /** @var AuthenticationServiceInterface */
    protected $authenticationService;

    /**
     * AuthenticationProvider constructor.
     * @param AuthenticationServiceInterface $authenticationService
     */
    public function __construct(AuthenticationServiceInterface $authenticationService)
    {
        $this->authenticationService = $authenticationService;
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
        if ($this->authenticationService->hasIdentity()) {

            /** @var array $roles */
            $roles = [];

            /** @var IdentityInterface $identity */
            $identity = $this->authenticationService->getIdentity();

            if ($identity instanceof RoleProviderInterface) {

                /** @var RoleTableGateway $table */
                $table = $this->getTableManager()->get(get_class($this));

                /** @var ResultSetInterface $resultSet */
                $resultSet = $table->fetchByUser($identity);

                foreach ($resultSet as $role) {
                    $roles[] = $role->getCode();
                }
            }

            if (empty($roles)) {
                $roles[] = $this->authenticatedRole;
            }

            return $roles;
        }

        return [$this->defaultRole];
    }
}
