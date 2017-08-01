<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace MSBios\Guard\View\Helper;

use MSBios\Guard\Service\AuthenticationService;
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
     * @param AuthenticationService $authenticationService
     */
    public function __construct(AuthenticationService $authenticationService)
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
