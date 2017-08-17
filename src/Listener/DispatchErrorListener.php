<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
namespace MSBios\Guard\Listener;

use Zend\EventManager\EventInterface;

/**
 * Class DispatchErrorListener
 * @package MSBios\Guard\Listener
 */
class DispatchErrorListener
{
    /**
     * @param EventInterface $e
     */
    public function onDispatchError(EventInterface $e)
    {
        // Do nothing if no error in the event
        $error = $e->getError();

        if (empty($error)) {
            return;
        }

        // Do nothing if the rsult is a response object
        $result = $e->getResult();
        $response = $e->getResponse();
        if ($result instanceof ResponseInterface || ($response && !$response instanceof HttpResponse)) {
            return;
        }

        switch ($error) {
            case RouteListener::ERROR:
            case ControllerListener::ERROR:
                $event->setName(self::EVENT_UNAUTHORIZED);
                break;

            case Application::ERROR_ROUTER_NO_MATCH:
                return;
                break;

            case Application::ERROR_EXCEPTION:
                if ($event->getParam('exception') instanceof ForbiddenExceprion) {
                    $event->setName(self::EVENT_UNAUTHORIZED_ERROR);
                    $event->getTarget()
                        ->getEventManager()
                        ->triggerEvent($event);
                }
                return;
                break;
        }

        /** @var ApplicationInterface $target */
        $target = $event->getApplication();

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

        $target->getEventManager()->triggerEvent($event);
    }
}