<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace MSBios\Guard\Collector;

use MSBios\Guard\Provider\IdentityProviderInterface;
use Zend\Mvc\MvcEvent;
use ZendDeveloperTools\Collector\CollectorInterface;

/**
 * Class RoleCollector
 * @package MSBios\Guard\Collector
 */
class RoleCollector implements CollectorInterface, \Serializable
{
    const NAME = 'msbios_guard_authorize_role_collector';
    const PRIORITY = 150;

    /** @var  IdentityProviderInterface */
    protected $identityProvider;

    /**
     * @var array|string[] collected role ids
     */
    protected $collectedRoles = [];

    /**
     * RoleCollector constructor.
     * @param IdentityProviderInterface $identityProvider
     */
    public function __construct(IdentityProviderInterface $identityProvider)
    {
        $this->identityProvider = $identityProvider;
    }

    /**
     * Collector Name.
     *
     * @return string
     */
    public function getName()
    {
        return self::NAME;
    }

    /**
     * Collector Priority.
     *
     * @return integer
     */
    public function getPriority()
    {
        return self::PRIORITY;
    }

    /**
     * Collects data.
     *
     * @param MvcEvent $mvcEvent
     */
    public function collect(MvcEvent $mvcEvent)
    {
        $this->collectedRoles = $this->identityProvider->getIdentityRoles();
    }

    /**
     * String representation of object
     * @link http://php.net/manual/en/serializable.serialize.php
     * @return string the string representation of the object or null
     * @since 5.1.0
     */
    public function serialize()
    {
        // TODO: JSON?
        return serialize($this->collectedRoles);
    }

    /**
     * Constructs the object
     * @link http://php.net/manual/en/serializable.unserialize.php
     * @param string $serialized <p>
     * The string representation of the object.
     * </p>
     * @return void
     * @since 5.1.0
     */
    public function unserialize($serialized)
    {
        // TODO: JSON?
        $this->collectedRoles = unserialize($serialized);
    }

    /**
     * @return array|\string[]
     */
    public function getCollectedRoles()
    {
        return $this->collectedRoles;
    }
}
