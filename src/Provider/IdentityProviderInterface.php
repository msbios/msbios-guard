<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace MSBios\Guard\Provider;

/**
 * Interface IdentityProviderInterface
 * @package MSBios\Guard\Provider
 */
interface IdentityProviderInterface
{
    /**
     * @return mixed
     */
    public function getIdentityRoles();
}
