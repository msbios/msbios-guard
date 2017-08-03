<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
namespace MSBios\Guard\Acl;

use Zend\Permissions\Acl\Resource\ResourceInterface;

/**
 * Interface HierarchicalResourceInterface
 * @package MSBios\Guard\Acl
 */
interface HierarchicalResourceInterface extends ResourceInterface
{
    /**
     * @return mixed
     */
    public function getParent();
}
