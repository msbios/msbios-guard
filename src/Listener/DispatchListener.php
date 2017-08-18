<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace MSBios\Guard\Listener;

use MSBios\Guard\Exception\ForbiddenExceprion;
use MSBios\Guard\GuardAwareInterface;
use MSBios\Guard\GuardManager;
use MSBios\Guard\GuardManagerInterface;
use MSBios\Guard\Router\Http\RouteMatch;
use Zend\EventManager\EventInterface;
use Zend\Mvc\MvcEvent;
use Zend\Permissions\Acl\Exception\InvalidArgumentException;
use Zend\Stdlib\DispatchableInterface;

/**
 * Class DispatchListener
 * @package MSBios\Guard\Listener
 */
class DispatchListener
{
    /** @const ERROR_UNAUTHORIZED_CONTROLLER */
    const ERROR_UNAUTHORIZED_CONTROLLER = 'error-unauthorized-controller';

    /**
     * @param EventInterface $e
     */
    public function onDispatch(MvcEvent $e)
    {
        /** @var DispatchableInterface $target */
        $target = $e->getTarget();

        if (!$target instanceof GuardAwareInterface) {
            return;
        }

        /** @var GuardManagerInterface $guardManager */
        $guardManager = $e->getApplication()
            ->getServiceManager()
            ->get(GuardManager::class);

        /** @var RouteMatch $routeMatch */
        $routeMatch = $e->getRouteMatch();

        /** @var string $controllerName */
        $controllerName = $routeMatch->getParam('controller');
        $actionName = $routeMatch->getParam('action');

        r($guardManager->isAllowed($controllerName, $actionName));

        try {

            if ($guardManager->isAllowed($controllerName, $actionName)) {
                return;
            }

            $e->setError(self::ERROR_UNAUTHORIZED_CONTROLLER);
            $e->setName(GuardManager::EVENT_FORBIDDEN);
            $e->setParam('exception', new ForbiddenExceprion(
                sprintf("You are not authorized to access %s::%s", $controllerName, $actionName)
            ));
            $e->getTarget()->getEventManager()->triggerEvent($e);


        } catch (InvalidArgumentException $exception) {
            // Do SomeThing
        }
    }
}