<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace MSBios\Guard\Listener;

use MSBios\Guard\Acl\Resource;
use MSBios\Guard\Provider\ResourceProviderInterface;
use MSBios\Guard\Provider\RuleProviderInterface;
use MSBios\Guard\Service\AuthenticationService;
use Zend\Config\Config;
use Zend\EventManager\EventInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\Mvc\MvcEvent;
use Zend\Permissions\Acl\Exception\InvalidArgumentException;
use Zend\Router\RouteMatch;

/**
 * Class ControllerListenerAggregate
 * @package MSBios\Guard\Listener
 */
class ControllerListenerAggregate extends AbstractListenerAggregate implements
    ResourceProviderInterface,
    RuleProviderInterface
{
    /** @const ERROR */
    const UNAUTHORIZED = 'unauthorized';

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
        $this->listeners[] = $events->attach(MvcEvent::EVENT_RENDER, [$this, 'onRender'], $priority);
    }

    /**
     * @param EventInterface $event
     */
    public function onRender(EventInterface $event)
    {
        /** @var AuthenticationService $authenticationService */
        $authenticationService = $this->serviceLocator->get(AuthenticationService::class);

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

        $this->prepareDeniedResponse($event);
    }
}
