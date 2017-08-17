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
use Zend\Permissions\Acl\Exception\InvalidArgumentException;


/**
 * Class RouteListener
 * @package MSBios\Guard\Listener
 */
class RouteListener
{
    /** @const ERROR */
    const ERROR = 'error-unauthorized-route';

    /**
     * @param EventInterface $e
     */
    public function onRoute(EventInterface $e)
    {

        /** @var string $error */
        $error = $e->getError();

        if (!empty($error)) {
            return;
        }

        /** @var RouteMatch $routeMatch */
        if (!$routeMatch = $e->getRouteMatch()) {
            return;
        };

        if ($routeMatch instanceof GuardAwareInterface) {

            /** @var string $routeName */
            $routeName = $routeMatch->getMatchedRouteName();

            /** @var GuardManagerInterface $guardManager */
            $guardManager = $e->getTarget()
                ->getServiceManager()
                ->get(GuardManager::class);

            try {
                if ($guardManager->isAllowed("route/{$routeName}")) {
                    return;
                }
            } catch (InvalidArgumentException $exception) {
                // Do SomeThing
            }

            $e->setError(self::ERROR);
            $e->setName(GuardManager::EVENT_FORBIDDEN);
            $e->setParam('exception', new ForbiddenExceprion("You are not authorized to access {$routeName}"));
            $e->getTarget()->getEventManager()->triggerEvent($e);
        }
    }
}
