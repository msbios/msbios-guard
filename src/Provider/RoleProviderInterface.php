<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace MSBios\Guard\Provider;

/**
 * Interface RoleProviderInterface
 * @package MSBios\Guard\Provider
 */
interface RoleProviderInterface
{
    /**
     * @return mixed
     */
    public function getRoles();
}
