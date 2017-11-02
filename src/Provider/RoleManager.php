<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
namespace MSBios\Guard\Provider;

/**
 * Class RoleManager
 * @package MSBios\Guard\Provider
 */
class RoleManager implements RoleProviderInterface
{
    /** @var RoleProviderInterface[] */
    protected $providers = [];

    /**
     * RoleManager constructor.
     * @param array $providers
     */
    public function __construct(array $providers = [])
    {
        /** @var RoleProviderInterface $provider */
        foreach ($providers as $provider) {
            $this->add($provider);
        }
    }

    /**
     * @param RoleProviderInterface $provider
     * @return $this
     */
    public function add(RoleProviderInterface $provider)
    {
        $this->providers[] = $provider;
        return $this;
    }

    /**
     * @return array
     */
    public function getRoles()
    {
        /** @var array $roles */
        $roles = [];

        /** @var RoleProviderInterface $provider */
        foreach ($this->providers as $provider) {
            foreach ($provider->getRoles() as $role) {
                $roles[] = $role;
            }
        }

        return $roles;
    }
}