<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace MSBios\Guard\Provider;

/**
 * Interface ResourceProviderInterface
 * @package MSBios\Guard\Provider
 */
interface ResourceProviderInterface
{
    /**
     * @return mixed
     */
    public function getResources();
}
