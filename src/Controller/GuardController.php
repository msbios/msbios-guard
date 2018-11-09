<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace MSBios\Guard\Controller;

use MSBios\Application\Controller\IndexController as DefaultIndexController;
use MSBios\Authentication\AuthenticationService;
use MSBios\Authentication\AuthenticationServiceAwareInterface;
use MSBios\Authentication\AuthenticationServiceAwareTrait;
use MSBios\Form\FormElementManagerAwareInterface;
use MSBios\Form\FormElementManagerAwareTrait;
use MSBios\Guard\Form\LoginForm;
use Zend\Authentication\Adapter\AdapterInterface;
use Zend\Authentication\AuthenticationServiceInterface;
use Zend\Authentication\Result;
use Zend\Form\FormElementManager\FormElementManagerV3Polyfill;
use Zend\Form\FormInterface;
use Zend\View\Model\ViewModel;

/**
 * Class GuardController
 * @package MSBios\Guard\Controller
 */
class GuardController extends DefaultIndexController implements
    AuthenticationServiceAwareInterface,
    FormElementManagerAwareInterface
{
    use AuthenticationServiceAwareTrait;
    use FormElementManagerAwareTrait;

    /**
     * IndexController constructor.
     * @param AuthenticationServiceInterface $authenticationService
     * @param FormElementManagerV3Polyfill $formElementManager
     */
    public function __construct(
        AuthenticationServiceInterface $authenticationService,
        FormElementManagerV3Polyfill $formElementManager
    ) {
        $this->setAuthenticationService($authenticationService);
        $this->setFormElementManager($formElementManager);
    }

    /**
     * @return \Zend\Http\Response|ViewModel
     */
    public function loginAction()
    {
        /** @var AuthenticationServiceInterface|AuthenticationService $authenticationService */
        $authenticationService = $this->getAuthenticationService();

        /** @var FormInterface $form */
        $form = $this->getFormElementManager()
            ->get(LoginForm::class);

        if ($this->getRequest()->isPost()) {

            /** @var array $data */
            $data = $this->params()
                ->fromPost();

            if ($form->setData($data)->isValid()) {

                /** @var array $values */
                $values = $form->getData();

                /** @var AdapterInterface $authenticationAdapter */
                $authenticationAdapter = $authenticationService->getAdapter();
                $authenticationAdapter->setIdentity($values['username']);
                $authenticationAdapter->setCredential($values['password']);

                /** @var Result $authenticationResult */
                $authenticationResult = $authenticationService->authenticate();

                if ($authenticationResult->isValid()) {
                    return $this->redirect()->toRoute('home');
                } else {
                    var_dump($authenticationResult->getMessages());
                    die();
                }
            } else {
                var_dump($form->getMessages());
                die();
            }
        }

        return (new ViewModel([
            'form' => $form
        ]))->setTemplate('error/403');
    }

    /**
     * @return ViewModel
     */
    public function joinAction()
    {
        return new ViewModel;
    }

    /**
     * @return ViewModel
     */
    public function resetAction()
    {
        return new ViewModel;
    }

    /**
     * @return \Zend\Http\Response
     */
    public function logoutAction()
    {
        $this->getAuthenticationService()
            ->clearIdentity();
        return $this->redirect()
            ->toRoute('home');
    }
}
