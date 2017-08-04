<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace MSBios\Guard\Listener;

use MSBios\Guard\Exception\UnAuthorizedException;
use MSBios\Guard\Module;
use Zend\EventManager\EventInterface;
use Zend\Http\PhpEnvironment\Response;
use Zend\Http\Response as HttpResponse;
use Zend\Mvc\Application;
use Zend\Mvc\ApplicationInterface;
use Zend\Stdlib\ResponseInterface;
use Zend\View\Model\ViewModel;

/**
 * Class UnAuthorizedListener
 * @package MSBios\Guard\Listener
 */
class UnAuthorizedListener
{
    /** EVENT_UNAUTHORIZED */
    const EVENT_UNAUTHORIZED = 'unauthorized';

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
        $response = $event->getResponse();
        if ($result instanceof ResponseInterface || ($response && ! $response instanceof HttpResponse)) {
            return;
        }

        if (Application::ERROR_EXCEPTION == $error
            && !($event->getParam('exception') instanceof UnAuthorizedException)) {
            return;
        }

        switch ($error) {
            case RouteListener::ERROR:
            case ControllerListener::ERROR:
                break;
            case Application::ERROR_EXCEPTION:

                break;
        }

        /** @var ApplicationInterface $target */
        $target = $event->getTarget();

        /** @var ViewModel $viewModel */
        $viewModel = new ViewModel;
        $viewModel->setTemplate(
            $target->getServiceManager()->get(Module::class)->get('template')
        );

        $event->getViewModel()
            ->addChild($viewModel);

        /** @var Response $response */
        $response = $event->getResponse();

        /** @var Response $response */
        $response = $response ?: new HttpResponse();
        $response->setStatusCode(Response::STATUS_CODE_403);
        $event->setResponse($response);

        $event->setName(self::EVENT_UNAUTHORIZED);
        $target->getEventManager()->triggerEvent($event);
    }
}
