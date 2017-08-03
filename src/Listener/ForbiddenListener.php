<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace MSBios\Guard\Listener;

use Zend\EventManager\AbstractListenerAggregate;
use Zend\EventManager\EventInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\Http\PhpEnvironment\Response;
use Zend\Mvc\MvcEvent;
use Zend\View\Model\ViewModel;

/**
 * Class ForbiddenListener
 * @package MSBios\Guard\Listener
 */
class ForbiddenListener extends AbstractListenerAggregate
{

    /** EVENT_UNAUTHORIZED */
    const EVENT_UNAUTHORIZED = 'unauthorized.post';

    /**
     * @param EventManagerInterface $events
     * @param int $priority
     */
    public function attach(EventManagerInterface $events, $priority = -100500)
    {
        $this->listeners[] = $events->attach(MvcEvent::EVENT_DISPATCH_ERROR, [$this, 'onDispatchError'], $priority);
    }

    /**
     * @param EventInterface $event
     */
    public function onDispatchError(EventInterface $event)
    {
        // Do nothing if no error in the event
        $error = $event->getError();
        if (empty($error)) {
            return;
        }

        // Do nothing if the result is a response object
        $result = $event->getResult();
        if ($result instanceof Response) {
            return;
        }

        switch ($error) {
            case RouteListener::ERROR:
                /** @var ViewModel $viewModel */
                $viewModel = new ViewModel;
                $viewModel->setTemplate('error/403');
                $event->getViewModel()->addChild($viewModel);

                /** @var Response $response */
                $response = $event->getResponse();

                /** @var Response $response */
                $response = $response ?: new Response;
                $response->setStatusCode(Response::STATUS_CODE_403);
                $event->setResponse($response);

                $event->setName(self::EVENT_UNAUTHORIZED);
                $event->getTarget()->getEventManager()->triggerEvent($event);
                break;
        }

    }
}