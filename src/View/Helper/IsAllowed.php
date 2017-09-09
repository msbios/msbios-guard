<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace MSBios\Guard\View\Helper;

use MSBios\Guard\GuardManagerInterface;
use Zend\View\Helper\AbstractHelper;

/**
 * Class IsAllowed
 * @package MSBios\Guard\View\Helper
 */
class IsAllowed extends AbstractHelper
{
    /** @var GuardManagerInterface */
    protected $guardManager;

    /**
     * IsAllowed constructor.
     * @param GuardManagerInterface $guardManager
     */
    public function __construct(GuardManagerInterface $guardManager)
    {
        $this->guardManager = $guardManager;
    }

    /**
     * @param $resource
     * @param null $privilege
     * @return bool
     */
    public function __invoke($resource, $privilege = null)
    {
        return $this->guardManager->isAllowed($resource, $privilege);
    }
}
