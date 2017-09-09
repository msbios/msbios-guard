<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace MSBios\Guard;

/**
 * Trait GuardManagerAwareTrait
 * @package MSBios\Guard
 */
trait GuardManagerAwareTrait
{
    /** @var GuardInterface */
    protected $guardManager;

    /**
     * @return GuardInterface
     */
    public function getGuardManager()
    {
        return $this->guardManager;
    }

    /**
     * @param GuardInterface $guardManager
     * @return $this
     */
    public function setGuardManager(GuardInterface $guardManager)
    {
        $this->guardManager = $guardManager;
        return $this;
    }
}
