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
use Zend\Mvc\Application;
use Zend\Mvc\MvcEvent;
use Zend\Permissions\Acl\Exception\InvalidArgumentException;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;


/**
 * Class RouteListener
 * @package MSBios\Guard\Listener
 */
class RouteListener
{
    /** @const ERROR_UNAUTHORIZED_ROUTE */
    const ERROR_UNAUTHORIZED_ROUTE = 'error-unauthorized-route';

    /**
     * @param EventInterface $e
     */
    public function onRoute(EventInterface $e)
    {
        /** @var null|RouteMatch $routeMatch */
        $routeMatch = $e->getRouteMatch();

        if (!$routeMatch instanceof GuardAwareInterface) {
            return;
        }

        /** @var string $routeName */
        $routeName = $routeMatch->getMatchedRouteName();

        try {

            /** @var GuardManagerInterface $guardManager */
            $guardManager = $e->getTarget()
                ->getServiceManager()
                ->get(GuardManager::class);

            if ($guardManager->isAllowed("route/{$routeName}")) {
                return;
            }

            $e->setName(MvcEvent::EVENT_DISPATCH_ERROR);
            $e->setError(self::ERROR_UNAUTHORIZED_ROUTE);
            $e->setParam('exception', new ForbiddenExceprion("You are not authorized to access {$routeName}"));
            $e->getTarget()->getEventManager()->triggerEvent($e);

        } catch (InvalidArgumentException $exception) {
            r($exception);
        } catch (ServiceNotCreatedException $exception) {
            $e->setName(MvcEvent::EVENT_DISPATCH_ERROR);
            $e->setError(Application::ERROR_EXCEPTION);
            $e->setParam('exception', $exception);
            $e->getTarget()->getEventManager()->triggerEvent($e);
        }
    }
}
