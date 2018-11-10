<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
namespace MSBios\Guard\Provider;

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
     * @param array|null $rules
     */
    public function __construct(ServiceLocatorInterface $serviceLocator, array $rules = null)
    {
        $this->serviceLocator = $serviceLocator;
        $this->rules = $rules;
    }

    /**
     * @return array
     */
    public function getRules()
    {
        return $this->rules;
    }
}
