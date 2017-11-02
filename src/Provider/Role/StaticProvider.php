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
    /** @var Config */
    protected $config;

    /**
     * StaticProvider constructor.
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * @return mixed
     */
    public function getRoles()
    {
        /** @var array $data */
        $data = $this->config->toArray();

        foreach ($data as ) {

        }

        // TODO: Implement getRoles() method.
    }
}