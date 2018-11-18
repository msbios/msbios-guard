<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
namespace MSBios\Guard\Permission;

use MSBios\Guard\Exception\InvalidRoleException;
use Zend\Permissions\Acl\Role\RoleInterface;

/**
 * Class Role
 * @package MSBios\Guard\Permission
 */
class Role implements RoleInterface, HierarchicalRoleInterface
{
    /** @var RoleInterface */
    protected $parent;

    /** @var string */
    protected $identifier;

    /**
     * Role constructor.
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
            $this->parent = new Role($parent);
        } elseif ($parent instanceof RoleInterface) {
            $this->parent = $parent;
        } else {
            throw new InvalidRoleException($parent);
        }

        return $this;
    }

    /**
     * Returns the string identifier of the Role
     *
     * @return string
     */
    public function getRoleId()
    {
        return $this->identifier;
    }
}
