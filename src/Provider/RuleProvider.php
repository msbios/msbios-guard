<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
namespace MSBios\Guard\Provider;

use Zend\Config\Config;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class RuleProvider
 * @package MSBios\Guard\Provider
 */
class RuleProvider implements RuleProviderInterface, ProviderInterface
{
    /** @var  ServiceLocatorInterface */
    protected $serviceLocator;

    /** @var array */
    protected $rules = [];

    /**
     * RuleProvider constructor.
     * @param ServiceLocatorInterface $serviceLocator
     * @param Config|null $config
     */
    public function __construct(ServiceLocatorInterface $serviceLocator, Config $config = null)
    {
        $this->serviceLocator = $serviceLocator;
        $this->rules = $config;
    }

    /**
     * @return array
     */
    public function getRules()
    {
        return $this->rules;
    }
}
