<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace MSBios\Guard\Provider;

/**
 * Interface RuleProviderInterface
 * @package MSBios\Guard\Provider
 */
interface RuleProviderInterface
{
    /**
     * @return mixed
     */
    public function getRules();
}
