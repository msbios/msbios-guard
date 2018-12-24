<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace MSBios\Guard\View\Helper;

use MSBios\Guard\GuardManagerAwareInterface;
use MSBios\Guard\GuardManagerAwareTrait;
use MSBios\Guard\GuardManagerInterface;
use Zend\View\Helper\AbstractHelper;

/**
 * Class IsAllowed
 * @package MSBios\Guard\View\Helper
 */
class IsAllowed extends AbstractHelper implements GuardManagerAwareInterface
{
    use GuardManagerAwareTrait;

    /**
     * IsAllowed constructor.
     *
     * @param GuardManagerInterface $guardManager
     */
    public function __construct(GuardManagerInterface $guardManager)
    {
        $this->setGuardManager($guardManager);
    }

    /**
     * @param $resource
     * @param null $privilege
     * @return mixed
     */
    public function __invoke($resource, $privilege = null)
    {
        return $this
            ->getGuardManager()
            ->isAllowed($resource, $privilege);
    }
}
