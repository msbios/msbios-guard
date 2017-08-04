<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace MSBios\Guard\View\Helper;

use MSBios\Guard\Service\GuardManager;
use Zend\View\Helper\AbstractHelper;

/**
 * Class IsAllowed
 * @package MSBios\Guard\View\Helper
 */
class IsAllowed extends AbstractHelper
{
    /** @var GuardManager */
    protected $authenticationService;

    /**
     * IsAllowed constructor.
     * @param GuardManager $authenticationService
     */
    public function __construct(GuardManager $authenticationService)
    {
        $this->authenticationService = $authenticationService;
    }

    /**
     * @param $resource
     * @param null $privilege
     * @return bool
     */
    public function __invoke($resource, $privilege = null)
    {
        return $this->authenticationService->isAllowed($resource, $privilege);
    }
}
