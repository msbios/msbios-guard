<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace MSBios\Guard;

use MSBios\Guard\Provider\GuardProviderInterface;
use MSBios\Guard\Provider\IdentityProviderInterface;
use MSBios\Guard\Provider\ResourceProviderInterface;
use MSBios\Guard\Provider\RoleProviderInterface;
use MSBios\Guard\Provider\RuleProviderInterface;
use Zend\Config\Config;
use Zend\Permissions\Acl\Acl;
use Zend\Stdlib\InitializableInterface;

/**
 * Interface GuardManagerInterface
 * @package MSBios\Guard
 */
interface GuardManagerInterface extends InitializableInterface
{
    /**
     * Init an object
     *
     * @return void
     */
    public function init();

    /**
     * @return Acl
     */
    public function getAcl();

    /**
     * @param $resource
     * @param null $privilege
     * @return bool
     */
    public function isAllowed($resource, $privilege = null);

    /**
     * @param IdentityProviderInterface $provider
     * @return $this
     */
    public function setIdentityProvider(IdentityProviderInterface $provider);

    /**
     * @return IdentityProviderInterface
     */
    public function getIdentityProvider();

    /**
     * @param ResourceProviderInterface $provider
     * @return $this
     */
    public function addResourceProvider(ResourceProviderInterface $provider);

    /**
     * @param RoleProviderInterface $provider
     * @return $this
     */
    public function addRoleProvider(RoleProviderInterface $provider);

    /**
     * @param RuleProviderInterface $provider
     * @return $this
     */
    public function addRuleProvider(RuleProviderInterface $provider);

    /**
     * @param GuardProviderInterface $provider
     * @return $this
     */
    public function addGuardProvider(GuardProviderInterface $provider);

    /**
     * @param $roles
     * @return $this
     */
    public function addRoles($roles);

    /**
     * @param $resources
     * @return $this
     */
    public function addResources($resources);

    /**
     * @param Config $rule
     * @param $type
     * @return $this
     */
    public function addRule(Config $rule, $type);

    /**
     * @return string
     */
    public function getIdentity();
}
