<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace MSBios\Guard;

use MSBios\Guard\Acl\Role;
use MSBios\Guard\Exception\InvalidArgumentException;
use MSBios\Guard\Provider\GuardProviderInterface;
use MSBios\Guard\Provider\IdentityProviderInterface;
use MSBios\Guard\Provider\ProviderInterface;
use MSBios\Guard\Provider\ResourceInterface;
use MSBios\Guard\Provider\ResourceProviderInterface;
use MSBios\Guard\Provider\RoleProviderInterface;
use MSBios\Guard\Provider\RuleProviderInterface;
use Zend\Config\Config;
use Zend\Permissions\Acl\Acl;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\InitializableInterface;

/**
 * Class GuardManager
 * @package MSBios\Guards
 */
class GuardManager implements InitializableInterface
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

    /**
     * Authentication constructor.
     * @param ServiceLocatorInterface $serviceLocator
     */
    public function __construct(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
        $this->init();
    }

    /**
     * Init an object
     *
     * @return void
     */
    public function init()
    {
        $this->setIdentityProvider($this->serviceLocator->get(IdentityProviderInterface::class));

        $this->getAcl()->addRole(
            $this->getIdentity(),
            $this->getIdentityProvider()->getIdentityRoles()
        );
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

        /** @var array $providers */
        $providers = [
            ResourceInterface::class => 'addResourceProvider',
            RoleProviderInterface::class => 'addRoleProvider',
            RuleProviderInterface::class => 'addRuleProvider',
            GuardProviderInterface::class => 'addGuardProvider',
        ];

        /**
         * @var string $id
         * @var string $method
         */
        foreach ($providers as $id => $method) {
            /** @var ProviderInterface $provider */
            foreach ($this->serviceLocator->get($id) as $provider) {
                $this->$method($provider);
            }
        }

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
             * @var string $type
             * @var Config $rules
             */
            foreach ($provider->getRules() as $type => $rules) {
                /** @var Config $rule */
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
        return $this->acl->isAllowed(
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
    protected function addRoles($roles)
    {
        if (! is_array($roles) && ! ($roles instanceof \Traversable)) {
            $roles = [$roles];
        }

        /** @var Role $role */
        foreach ($roles as $role) {
            if ($this->acl->hasRole($role)) {
                continue;
            }

            if ($parent = $role->getParent()) {
                $this->addRoles([$parent]);
                $this->acl->addRole($role, $parent);
            } else {
                $this->acl->addRole($role);
            }
        }

        return $this;
    }

    /**
     * @param $resources
     * @return $this
     */
    protected function addResources($resources)
    {
        if (! is_array($resources) && ! ($resources instanceof \Traversable)) {
            $resources = [$resources];
        }

        /** @var Resource $resource */
        foreach ($resources as $resource) {
            if ($this->acl->hasResource($resource)) {
                continue;
            }

            if ($parent = $resource->getParent()) {
                $this->addResources([$parent]);
                $this->acl->addResource($resource, $parent);
            } else {
                $this->acl->addResource($resource);
            }
        }

        return $this;
    }

    /**
     * @param Config $rule
     * @param $type
     * @return $this
     */
    protected function addRule(Config $rule, $type)
    {

        /** @var null $privileges */
        $privileges = $assertion = null;

        switch ($rule->count()) {
            case 4:
                list($roles, $resources, $privileges, $assertion) = $rule->toArray();
                break;
            case 3:
                list($roles, $resources, $privileges) = $rule->toArray();
                break;
            case 2:
                list($roles, $resources) = $rule->toArray();
                break;
            default:
                throw new InvalidArgumentException("Invalid rule definition: " . print_r($rule, true));
                break;
        }

        $this->acl->$type($roles, $resources, $privileges, $assertion);
        return $this;
    }

    /**
     * @return string
     */
    protected function getIdentity()
    {
        return self::class;
    }
}
