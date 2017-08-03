<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace MSBios\Guard\Listener;

use MSBios\Guard\Provider\ResourceProviderInterface;
use MSBios\Guard\Provider\RuleProviderInterface;
use MSBios\Guard\Resource;
use MSBios\Guard\Service\AuthenticationService;
use Zend\Config\Config;
use Zend\EventManager\AbstractListenerAggregate;
use Zend\EventManager\EventInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\Mvc\MvcEvent;
use Zend\Permissions\Acl\Exception\InvalidArgumentException;
use Zend\Router\RouteMatch;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class ControllerListener
 * @package MSBios\Guard\Listener
 */
class ControllerListener extends AbstractListenerAggregate implements
    ResourceProviderInterface,
    RuleProviderInterface
{
    /** @var ServiceLocatorInterface */
    protected $serviceManager;

    /** @var Config */
    protected $options;

    /** @const ERROR */
    const ERROR = 'error-unauthorized-route';

    /**
     * ControllerListener constructor.
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
                $config->get('controller')
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
                $rule->get('controller'),
                $rule->get('action')
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
    public function onRoute(EventInterface $event)
    {
        /** @var AuthenticationService $authenticationService */
        $authenticationService = $this->serviceManager->get(AuthenticationService::class);

        /** @var RouteMatch $routeMatch */
        $routeMatch = $event->getRouteMatch();

        try {
            if ($authenticationService->isAllowed(
                $routeMatch->getParam('controller'),
                $routeMatch->getParam('action')
            )
            ) {
                return;
            }
        } catch (InvalidArgumentException $exception) {
            // Do SomeThing
        }

        $event->setError(self::ERROR);
        $event->setName(MvcEvent::EVENT_DISPATCH_ERROR);
        $event->getTarget()->getEventManager()->triggerEvent($event);
    }
}
