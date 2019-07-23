<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace MSBios\Guard;

use MSBios\Guard\Exception\ForbiddenException;
use MSBios\Guard\Form\LoginForm;
use MSBios\Guard\Router\Http\ProxyRouteMatch;
use Zend\EventManager\AbstractListenerAggregate;
use Zend\EventManager\EventInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ResponseCollection;
use Zend\Http\Request as HttpRequest;
use Zend\Http\Response;
use Zend\Mvc\Application;
use Zend\Mvc\MvcEvent;
use Zend\Permissions\Acl\Exception\InvalidArgumentException;
use Zend\Permissions\Acl\Resource\ResourceInterface;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\DispatchableInterface;
use Zend\Stdlib\RequestInterface;
use Zend\View\Model\ModelInterface;
use Zend\View\Model\ViewModel;

/**
 * Class ListenerAggregate
 *
 * @package MSBios\Guard
 */
class ListenerAggregate extends AbstractListenerAggregate implements GuardManagerAwareInterface
{
    use GuardManagerAwareTrait;

    /** @const ERROR_UNAUTHORIZED_ROUTE */
    const ERROR_UNAUTHORIZED_ROUTE = 'error-unauthorized-route';

    /** @const ERROR_UNAUTHORIZED_CONTROLLER */
    const ERROR_UNAUTHORIZED_CONTROLLER = 'error-unauthorized-controller';

    /**
     * ListenerAggregate constructor.
     *
     * @param GuardManagerInterface $guardManager
     */
    public function __construct(GuardManagerInterface $guardManager)
    {
        $this->setGuardManager($guardManager);
    }

    /**
     * @param EventManagerInterface $events
     * @param int $priority
     */
    public function attach(EventManagerInterface $events, $priority = 1)
    {
        $this->listeners[] = $events
            ->attach(MvcEvent::EVENT_ROUTE, [$this, 'onRoute'], $priority);
        $this->listeners[] = $events
            ->attach(MvcEvent::EVENT_DISPATCH, [$this, 'onDispatch'], $priority);
        $this->listeners[] = $events
            ->attach(MvcEvent::EVENT_DISPATCH_ERROR, [$this, 'onDispatchError'], -100900);
    }

    /**
     * @param EventInterface|MvcEvent $e
     */
    public function onRoute(EventInterface $e)
    {
        /** @var null|ProxyRouteMatch $routeMatch */
        $routeMatch = $e->getRouteMatch();

        if (! $routeMatch instanceof ResourceInterface) {
            return;
        }

        /** @var string $matchedRouteName */
        $matchedRouteName = $routeMatch
            ->getResourceId();

        try {
            if ($this->getGuardManager()->isAllowed($matchedRouteName)) {
                return;
            }

            /** @var ForbiddenException $exception */
            $exception = new ForbiddenException(
                "You are not authorized to access {$matchedRouteName}"
            );

            $e->setName(MvcEvent::EVENT_DISPATCH_ERROR);
            $e->setError(self::ERROR_UNAUTHORIZED_ROUTE);
            $e->setParam('exception', $exception);

            $e
                ->getTarget()
                ->getEventManager()
                ->triggerEvent($e);

        } catch (InvalidArgumentException $exception) {

            $e->setName(MvcEvent::EVENT_DISPATCH_ERROR);
            $e->setError(Application::ERROR_EXCEPTION);
            $e->setParam('exception', $exception);

            $e
                ->getTarget()
                ->getEventManager()
                ->triggerEvent($e);

        } catch (ServiceNotCreatedException $exception) {
            $e->setName(MvcEvent::EVENT_DISPATCH_ERROR);
            $e->setError(Application::ERROR_EXCEPTION);
            $e->setParam('exception', $exception);

            $e
                ->getTarget()
                ->getEventManager()
                ->triggerEvent($e);
        }
    }

    /**
     * @param EventInterface $e
     * @return mixed|void
     */
    public function onDispatch(EventInterface $e)
    {
        /** @var DispatchableInterface $target */
        $target = $e->getTarget();

        if (! $target instanceof ResourceInterface) {
            return;
        }

        try {

            /** @var ProxyRouteMatch $routeMatch */
            $routeMatch = $e->getRouteMatch();

            /** @var string $controllerName */
            $controllerName = $target->getResourceId();

            /** @var string $actionName */
            $actionName = $routeMatch->getParam('action');

            if ($this->guardManager->isAllowed($controllerName, $actionName)) {
                return;
            }

            $e->setName(MvcEvent::EVENT_DISPATCH_ERROR);
            $e->setError(self::ERROR_UNAUTHORIZED_CONTROLLER);
            $e->setParam('exception', new ForbiddenException(
                sprintf("You are not authorized to access %s::%s", $controllerName, $actionName)
            ));

            /** @var ResponseCollection $results */
            $results = $e
                ->getApplication()
                ->getEventManager()
                ->triggerEvent($e);

            if (! empty($results)) {
                return $results->last();
            }

        } catch (InvalidArgumentException $exception) {
            $e->setName(MvcEvent::EVENT_DISPATCH_ERROR);
            $e->setError(Application::ERROR_EXCEPTION);
            $e->setParam('exception', $exception);

            /** @var ResponseCollection $results */
            $results = $e
                ->getApplication()
                ->getEventManager()
                ->triggerEvent($e);

            if (! empty($results)) {
                return $results->last();
            }

        } catch (ServiceNotCreatedException $exception) {

            $e->setName(MvcEvent::EVENT_DISPATCH_ERROR);
            $e->setError(Application::ERROR_EXCEPTION);
            $e->setParam('exception', $exception);

            $e
                ->getTarget()
                ->getEventManager()
                ->triggerEvent($e);
        }
    }

    /**
     * @param EventInterface $e
     */
    public function onDispatchError(EventInterface $e)
    {
        /** @var string $error */
        $error = $e->getError();

        if (empty($error)) {
            return;
        }

        if (! $e->getParam('exception') instanceof ForbiddenException) {
            return;
        }

        /** @var ServiceLocatorInterface $serviceManager */
        $serviceManager = $e->getApplication()
            ->getServiceManager();

        /** @var ViewModel $viewModel */
        $viewModel = new ViewModel;
        $viewModel->setTemplate('error/403');

        /** @var LoginForm $form */
        $form = $serviceManager->get('FormElementManager')->get(LoginForm::class);

        /** @var RequestInterface $request */
        $request = $e->getRequest();

        if ($request instanceof HttpRequest) {
            $form->setData([
                'redirect' => base64_encode($request->getRequestUri())
            ]);

            /** @var ModelInterface $child */
            foreach ($viewModel->getChildren() as $child) {
                $child->setVariable('form', $form);
            }
        }

        $viewModel->setVariable('form', $form);

        $e->getViewModel()
            ->addChild($viewModel);

        /** @var Response $response */
        $response = $e->getResponse();

        /** @var Response $response */
        $response = $response ?: new Response;
        $response->setStatusCode(Response::STATUS_CODE_403);
        $e->setResponse($response);
    }
}
