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
     * @param GuardInterface $guardManager
     * @return $this
     */
    public function setGuardManager(GuardInterface $guardManager);
}
