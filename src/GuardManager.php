<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace MSBios\Guard;

use MSBios\Guard\Exception\InvalidArgumentException;
use MSBios\Guard\Permission\Role;
use MSBios\Guard\Provider\GuardProviderInterface;
use MSBios\Guard\Provider\IdentityProviderInterface;
use MSBios\Guard\Provider\ResourceProviderInterface;
use MSBios\Guard\Provider\RoleProviderInterface;
use MSBios\Guard\Provider\RuleProviderInterface;
use Zend\Permissions\Acl\Acl;
use Zend\Permissions\Acl\AclInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class GuardManager
 * @package MSBios\Guard
 */
class GuardManager implements GuardManagerInterface
{
    /** @var ServiceLocatorInterface */
    protected $serviceLocator;

    /** @var  IdentityProviderInterface */
    protected $identityProvider;

    /** @var ResourceProviderInterface[] */
    protected $resourceProviders = [];

    /** @var RoleProviderInterface[] */
    protected $roleProviders = [];

    /** @var RuleProviderInterface[] */
    protected $ruleProviders = [];

    /** @var GuardProviderInterface[] */
    protected $guardProviders = [];

    /** @var Acl */
    protected $acl;

    /** @const EVENT_FORBIDDEN */
    const EVENT_FORBIDDEN = 'guard.forbidden';

    /** @var bool */
    protected $isInitialized = false;

    /**
     * GuardManager constructor.
     *
     * @param IdentityProviderInterface $identityProvider
     * @param $resourceProviders
     * @param $roleProviders
     * @param $ruleProviders
     */
    public function __construct(
        IdentityProviderInterface $identityProvider,
        $resourceProviders,
        $roleProviders,
        $ruleProviders
    )
    {
        $this
            ->setIdentityProvider($identityProvider)
            ->addResourceProviders($resourceProviders)
            ->addRoleProviders($roleProviders)
            ->addRuleProviders($ruleProviders);

        $this->initialize();
    }

    /**
     * Init an object
     *
     * @return void
     */
    public function initialize()
    {
        if ($this->isInitialized) {
            return;
        }

        $this->getAcl()->addRole(
            $this->getIdentity(),
            $this->getIdentityProvider()->getIdentityRoles()
        );

        $this->isInitialized = true;
    }

    /**
     * @param array $resourceProviders
     * @return $this
     */
    public function addResourceProviders(array $resourceProviders)
    {
        /** @var ResourceProviderInterface $resourceProvider */
        foreach ($resourceProviders as $resourceProvider) {
            $this->addResourceProvider($resourceProvider);
        }

        return $this;
    }

    /**
     * @param array $roleProviders
     * @return $this
     */
    public function addRoleProviders(array $roleProviders)
    {
        /** @var RoleProviderInterface $provider */
        foreach ($roleProviders as $provider) {
            $this->addRoleProvider($provider);
        }

        return $this;
    }

    /**
     * @param array $ruleProviders
     * @return $this
     */
    public function addRuleProviders(array $ruleProviders)
    {
        foreach ($ruleProviders as $provider) {
            $this->addRuleProvider($provider);
        }

        return $this;
    }

    /**
     * @return Acl
     */
    public function getAcl()
    {
        if ($this->acl instanceof Acl) {
            return $this->acl;
        }

        $this->acl = new Acl;

        /** @var RoleProviderInterface $provider */
        foreach ($this->roleProviders as $provider) {
            $this->addRoles($provider->getRoles());
        }

        /** @var ResourceProviderInterface $provider */
        foreach ($this->resourceProviders as $provider) {
            $this->addResources($provider->getResources());
        }

        /** @var RuleProviderInterface $provider */
        foreach ($this->ruleProviders as $provider) {
            /**
             * @var srting $type
             * @var array $rules
             */
            foreach ($provider->getRules() as $type => $rules) {
                /** @var array $rule */
                foreach ($rules as $rule) {
                    $this->addRule($rule, $type);
                }
            }
        }

        return $this->acl;
    }

    /**
     * @param $resource
     * @param null $privilege
     * @return bool
     */
    public function isAllowed($resource, $privilege = null)
    {
        $this->initialize();

        return $this->getAcl()->isAllowed(
            $this->getIdentity(),
            $resource,
            $privilege
        );
    }

    /**
     * @param IdentityProviderInterface $provider
     * @return $this
     */
    public function setIdentityProvider(IdentityProviderInterface $provider)
    {
        $this->identityProvider = $provider;
        return $this;
    }

    /**
     * @return IdentityProviderInterface
     */
    public function getIdentityProvider()
    {
        return $this->identityProvider;
    }

    /**
     * @param ResourceProviderInterface $provider
     * @return $this
     */
    public function addResourceProvider(ResourceProviderInterface $provider)
    {
        $this->resourceProviders[] = $provider;
        return $this;
    }

    /**
     * @param RoleProviderInterface $provider
     * @return $this
     */
    public function addRoleProvider(RoleProviderInterface $provider)
    {
        $this->roleProviders[] = $provider;
        return $this;
    }

    /**
     * @param RuleProviderInterface $provider
     * @return $this
     */
    public function addRuleProvider(RuleProviderInterface $provider)
    {
        $this->ruleProviders[] = $provider;
        return $this;
    }

    /**
     * @param GuardProviderInterface $provider
     * @return $this
     */
    public function addGuardProvider(GuardProviderInterface $provider)
    {
        $this->guardProviders[] = $provider;

        if ($provider instanceof ResourceProviderInterface) {
            $this->addResourceProvider($provider);
        }

        if ($provider instanceof RuleProviderInterface) {
            $this->addRuleProvider($provider);
        }

        return $this;
    }

    /**
     * @param $roles
     * @return $this
     */
    public function addRoles($roles)
    {
        if (!is_array($roles) && !($roles instanceof \Traversable)) {
            $roles = [$roles];
        }

        /** @var AclInterface $acl */
        $acl = $this->getAcl();

        /** @var Role $role */
        foreach ($roles as $role) {
            if ($acl->hasRole($role)) {
                continue;
            }

            if ($parent = $role->getParent()) {
                $this->addRoles([$parent]);
                $acl->addRole($role, $parent);
                continue;
            }

            $acl->addRole($role);
        }

        return $this;
    }

    /**
     * @param $resources
     * @return $this
     */
    public function addResources($resources)
    {
        if (!is_array($resources) && !($resources instanceof \Traversable)) {
            $resources = [$resources];
        }

        /** @var AclInterface $acl */
        $acl = $this->getAcl();

        /** @var Resource $resource */
        foreach ($resources as $resource) {
            if ($acl->hasResource($resource)) {
                continue;
            }

            if ($parent = $resource->getParent()) {
                $this->addResources([$parent]);
                $acl->addResource($resource, $parent);
                continue;
            }

            $acl->addResource($resource);
        }

        return $this;
    }

    /**
     * @param array $rule
     * @param $type
     * @return $this|GuardManagerInterface
     */
    public function addRule(array $rule, $type)
    {

        /** @var null $privileges */
        $privileges = $assertion = null;

        switch (count($rule)) {
            case 4:
                list($roles, $resources, $privileges, $assertion) = $rule;
                break;
            case 3:
                list($roles, $resources, $privileges) = $rule;
                break;
            case 2:
                list($roles, $resources) = $rule;
                break;
            default:
                throw new InvalidArgumentException("Invalid rule definition: " . print_r($rule, true));
                break;
        }

        $this->getAcl()->$type($roles, $resources, $privileges, $assertion);
        return $this;
    }

    /**
     * @return string
     */
    public function getIdentity()
    {
        return self::class;
    }
}
