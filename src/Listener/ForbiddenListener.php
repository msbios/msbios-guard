<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace MSBios\Guard\Listener;

use MSBios\Guard\Exception\ForbiddenExceprion;
use MSBios\Guard\Module;
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
     * @param EventInterface $e
     */
    public function onDispatchError(EventInterface $e)
    {
        /** @var string $error */
        $error = $e->getError();

        if (empty($error)) {
            return;
        }

        if (!$e->getParam('exception') instanceof ForbiddenExceprion) {
            return;
        }

        /** @var ViewModel $viewModel */
        $viewModel = new ViewModel;
        $viewModel->setTemplate(
            $e->getApplication()
                ->getServiceManager()
                ->get(Module::class)
                ->get('template')
        );

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