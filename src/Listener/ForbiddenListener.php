<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
namespace MSBios\Guard\Listener;

use MSBios\Guard\Exception\ForbiddenExceprion;
use Zend\EventManager\EventInterface;
use Zend\Http\PhpEnvironment\Response;
use Zend\Mvc\ApplicationInterface;
use Zend\Mvc\MvcEvent;
use Zend\View\Model\ViewModel;

/**
 * Class ForbiddenListener
 * @package MSBios\Guard\Listener
 */
class ForbiddenListener
{
    /**
     * @param EventInterface $e
     */
    public function onForbidden(EventInterface $e)
    {
        /** @var string $error */
        $error = $e->getError();

        if (empty($error)) {
            return;
        }

        /** @var ViewModel $viewModel */
        $viewModel = new ViewModel;
        $viewModel->setTemplate('error/403');

        $e->getViewModel()->addChild($viewModel);

        /** @var Response $response */
        $response = $e->getResponse();

        /** @var Response $response */
        $response = $response ?: new Response;
        $response->setStatusCode(Response::STATUS_CODE_403);
        $e->setResponse($response);
    }
}