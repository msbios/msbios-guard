<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace MSBios\Guard\View\Helper;

use MSBios\Guard\GuardManager;
use Zend\View\Helper\AbstractHelper;

/**
 * Class IsAllowed
 * @package MSBios\Guard\View\Helper
 */
class IsAllowed extends AbstractHelper
{
    /** @var GuardManager */
    protected $guardManager;

    /**
     * IsAllowed constructor.
     * @param GuardManager $guardManager
     */
    public function __construct(GuardManager $guardManager)
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
