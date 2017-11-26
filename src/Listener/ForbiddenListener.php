<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace MSBios\Guard\Listener;

use MSBios\Guard\Exception\ForbiddenExceprion;
use MSBios\Guard\Form\LoginForm;
use MSBios\Guard\Module;
use Zend\EventManager\EventInterface;
use Zend\Form\FormInterface;
use Zend\Http\PhpEnvironment\Response;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\RequestInterface;
use Zend\View\Model\ModelInterface;
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

        if (! $e->getParam('exception') instanceof ForbiddenExceprion) {
            return;
        }

        /** @var ServiceLocatorInterface $serviceManager */
        $serviceManager = $e->getApplication()
            ->getServiceManager();

        /** @var ViewModel $viewModel */
        $viewModel = new ViewModel;
        $viewModel->setTemplate(
            $serviceManager->get(Module::class)->get('template')
        );

        // r($this); die();

        //

        /** @var FormInterface $form */
        $form = $serviceManager->get('FormElementManager')->get(LoginForm::class);
        $viewModel->setVariable('form', $form);

        // /** @var RequestInterface $request */
        // $request = $e->getRequest();

        // TODO: Need doing redirect to lock page
        //$form->setData([
        //    'redirect' => (!$request->isPost()) ?
        //        base64_encode($request->getRequestUri()) : $request->fromPost('redirect')
        //]);

        // /** @var ModelInterface $child */
        // foreach ($viewModel->getChildren() as $child) {
        //     $child->setVariable('form', $form);
        // }

        //

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
