<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
namespace MSBios\Guard\Permission;

use Zend\Permissions\Acl\Role\RoleInterface;

/**
 * Interface HierarchicalRoleInterface
 * @package MSBios\Guard\Permission
 */
interface HierarchicalRoleInterface extends RoleInterface
{
    /**
     * @return mixed
     */
    public function getParent();
}
