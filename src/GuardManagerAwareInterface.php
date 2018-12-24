<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace MSBios\Guard;

/**
 * Interface GuardManagerAwareInterface
 * @package MSBios\Guard
 */
interface GuardManagerAwareInterface
{
    /**
     * @return GuardInterface
     */
    public function getGuardManager();

    /**
     * @param GuardManagerInterface $guardManager
     * @return mixed
     */
    public function setGuardManager(GuardManagerInterface $guardManager);
}
