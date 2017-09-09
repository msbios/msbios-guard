<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
namespace MSBios\Guard\Permission;

use Zend\Permissions\Acl\Resource\ResourceInterface;

/**
 * Interface HierarchicalResourceInterface
 * @package MSBios\Guard\Permission
 */
interface HierarchicalResourceInterface extends ResourceInterface
{
    /**
     * @return mixed
     */
    public function getParent();
}
