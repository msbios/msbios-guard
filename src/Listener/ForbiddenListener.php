<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace MSBios\Guard\Listener;

use Zend\EventManager\EventInterface;
use Zend\Http\PhpEnvironment\Response;
use Zend\View\Model\ViewModel;

/**
 * Class ForbiddenListener
 * @package MSBios\Guard\Listener
 */
class ForbiddenListener
{
    /**
     * @param EventInterface $event
     */
    public function onForbidden(EventInterface $event)
    {
        /** @var string $error */
        $error = $event->getError();
        if (empty($error)) {
            return;
        }

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
    }
}