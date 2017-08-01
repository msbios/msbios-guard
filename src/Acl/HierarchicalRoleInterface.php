<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
namespace MSBios\Guard\Acl;

use Zend\Permissions\Acl\Role\RoleInterface;

/**
 * Interface HierarchicalRoleInterface
 * @package MSBios\Guard\Acl
 */
interface HierarchicalRoleInterface extends RoleInterface
{
    /**
     * @return mixed
     */
    public function getParent();
}
