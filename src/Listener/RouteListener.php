<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace MSBios\Guard\Listener;

use MSBios\Guard\Acl\Resource;
use MSBios\Guard\Exception\UnAuthorizedException;
use MSBios\Guard\GuardManager;
use MSBios\Guard\Provider\GuardProviderInterface;
use MSBios\Guard\Provider\ResourceProviderInterface;
use MSBios\Guard\Provider\RuleProviderInterface;
use Zend\Config\Config;
use Zend\EventManager\AbstractListenerAggregate;
use Zend\EventManager\EventInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\Mvc\MvcEvent;
use Zend\Permissions\Acl\Exception\InvalidArgumentException;
use Zend\Router\Http\RouteMatch;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class RouteListener
 * @package MSBios\Guard\Listener
 */
class RouteListener extends AbstractListenerAggregate implements
    ResourceProviderInterface,
    RuleProviderInterface,
    GuardProviderInterface
{

    /** @var ServiceLocatorInterface */
    protected $serviceManager;

    /** @var Config */
    protected $options;

    /** @const ERROR */
    const ERROR = 'error-unauthorized-route';

    /**
     * RouteListenerAggregate constructor.
     * @param ServiceLocatorInterface $serviceManager
     * @param Config $options
     */
    public function __construct(ServiceLocatorInterface $serviceManager, Config $options)
    {
        $this->serviceManager = $serviceManager;
        $this->options = $options;
    }

    /**
     * @return mixed
     */
    public function getResources()
    {
        /** @var array $resources */
        $resources = [];

        /** @var Config $config */
        foreach ($this->options as $config) {
            $resources[] = new Resource(
                "route/{$config->get('route')}"
            );
        }

        return $resources;
    }

    /**
     * @return array
     */
    public function getRules()
    {
        /** @var array $rules */
        $rules = [];

        foreach ($this->options as $rule) {
            $rules[] = new Config([
                $rule->get('roles')->toArray(),
                "route/{$rule->get('route')}",
            ]);
        }

        return ['allow' => $rules];
    }

    /**
     * Attach one or more listeners
     *
     * Implementors may add an optional $priority argument; the EventManager
     * implementation will pass this to the aggregate.
     *
     * @param EventManagerInterface $events
     * @param int $priority
     * @return void
     */
    public function attach(EventManagerInterface $events, $priority = 1)
    {
        $this->listeners[] = $events->attach(MvcEvent::EVENT_ROUTE, [$this, 'onRoute'], $priority);
    }

    /**
     * @param EventInterface $event
     */
    public function onRoute(MvcEvent $event)
    {
        /** @var GuardManager $authenticationService */
        $authenticationService = $this->serviceManager
            ->get(GuardManager::class);

        /** @var RouteMatch $routeMatch */
        $routeMatch = $event->getRouteMatch();

        /** @var string $routeName */
        $routeName = $routeMatch->getMatchedRouteName();

        try {
            if ($authenticationService->isAllowed("route/{$routeName}")) {
                return;
            }
        } catch (InvalidArgumentException $exception) {
            // Do SomeThing
        }

        $event->setError(self::ERROR);
        $event->setName(MvcEvent::EVENT_DISPATCH_ERROR);
        $event->setParam('exception', new UnAuthorizedException("You are not authorized to access {$routeName}"));
        $event->getTarget()->getEventManager()->triggerEvent($event);
    }
}
