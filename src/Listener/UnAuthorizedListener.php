<?php
///**
// * @access protected
// * @author Judzhin Miles <info[woof-woof]msbios.com>
// */
//
//namespace MSBios\Guard\Listener;
//
//use MSBios\Guard\Exception\ForbiddenExceprion;
//use MSBios\Guard\Module;
//use Zend\EventManager\EventInterface;
//use Zend\Http\PhpEnvironment\Response;
//use Zend\Http\Response as HttpResponse;
//use Zend\Mvc\Application;
//use Zend\Mvc\ApplicationInterface;
//use Zend\Stdlib\ResponseInterface;
//use Zend\View\Model\ViewModel;
//
///**
// * Class UnAuthorizedListener
// * @package MSBios\Guard\Listener
// */
//class UnAuthorizedListener
//{
//    /** EVENT_UNAUTHORIZED */
//    const EVENT_UNAUTHORIZED = 'unauthorized';
//
//    /** EVENT_UNAUTHORIZED_ERROR */
//    const EVENT_UNAUTHORIZED_ERROR = 'unauthorized.error';
//
//    /**
//     * @param EventInterface $event
//     */
//    public function onDispatchError(EventInterface $event)
//    {
//        // Do nothing if no error in the event
//        $error = $event->getError();
//
//        if (empty($error)) {
//            return;
//        }
//
//        // var_dump($event->getResult()); die();
//
//        // Do nothing if the rsult is a response object
//        $result = $event->getResult();
//        $response = $event->getResponse();
//        if ($result instanceof ResponseInterface || ($response && !$response instanceof HttpResponse)) {
//            return;
//        }
//
//        switch ($error) {
//            case RouteListener::ERROR:
//            case ControllerListener::ERROR:
//                $event->setName(self::EVENT_UNAUTHORIZED);
//                break;
//
//            case Application::ERROR_ROUTER_NO_MATCH:
//                return;
//                break;
//
//            case Application::ERROR_EXCEPTION:
//                if ($event->getParam('exception') instanceof ForbiddenExceprion) {
//                    $event->setName(self::EVENT_UNAUTHORIZED_ERROR);
//                    $event->getTarget()
//                        ->getEventManager()
//                        ->triggerEvent($event);
//                }
//                return;
//                break;
//        }
//
//        /** @var ApplicationInterface $target */
//        $target = $event->getApplication();
//
//        /** @var ViewModel $viewModel */
//        $viewModel = new ViewModel;
//        $viewModel->setTemplate(
//            $target->getServiceManager()->get(Module::class)->get('template')
//        );
//
//        $event->getViewModel()
//            ->addChild($viewModel);
//
//        /** @var Response $response */
//        $response = $event->getResponse();
//
//        /** @var Response $response */
//        $response = $response ?: new HttpResponse();
//        $response->setStatusCode(Response::STATUS_CODE_403);
//        $event->setResponse($response);
//
//        $target->getEventManager()->triggerEvent($event);
//    }
//}
