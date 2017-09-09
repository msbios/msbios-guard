<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
namespace MSBios\Guard\Permission;

use MSBios\Guard\Exception\InvalidResourceException;
use Zend\Permissions\Acl\Resource\ResourceInterface;
use Zend\Permissions\Acl\Role\RoleInterface;

/**
 * Class Resource
 * @package MSBios\Guard\Permission
 */
class Resource implements HierarchicalResourceInterface
{
    /** @var ResourceInterface */
    protected $parent;

    /** @var string */
    protected $identifier;

    /**
     * Resource constructor.
     * @param $identifier
     * @param null $parent
     */
    public function __construct($identifier, $parent = null)
    {
        $this->identifier = $identifier;
        if (! is_null($parent)) {
            $this->setParent($parent);
        }
    }

    /**
     * @return RoleInterface
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param $parent
     * @return $this
     */
    public function setParent($parent)
    {
        if (is_string($parent)) {
            $this->parent = new Resource($parent);
        } elseif ($parent instanceof ResourceInterface) {
            $this->parent = $parent;
        } else {
            throw new InvalidResourceException($parent);
        }

        return $this;
    }

    /**
     * Returns the string identifier of the Resource
     *
     * @return string
     */
    public function getResourceId()
    {
        return $this->identifier;
    }
}
