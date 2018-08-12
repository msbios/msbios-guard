<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace MSBios\Guard\Controller;

use MSBios\Application\Controller\IndexController as DefaultIndexController;
use MSBios\Authentication\AuthenticationServiceAwareTrait;
use MSBios\Form\FormElementManagerAwareTrait;
use MSBios\Guard\Form\LoginForm;
use MSBios\Guard\GuardInterface;
use MSBios\View\Model\ViewModelInterface;
use Zend\Authentication\Adapter\AdapterInterface;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\AuthenticationServiceInterface;
use Zend\Authentication\Result;
use Zend\Form\FormElementManager\FormElementManagerV3Polyfill;
use Zend\Form\FormInterface;
use Zend\View\Model\ViewModel;

/**
 * Class IndexController
 * @package MSBios\Guard\Controller
 */
class IndexController extends DefaultIndexController implements GuardInterface
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
     * @return \Zend\Http\Response|\Zend\View\Model\ViewModel
     */
    public function indexAction()
    {
        return parent::indexAction();
    }

    /**
     * @return \Zend\Http\Response|ViewModel
     */
    public function loginAction()
    {
        /** @var AuthenticationServiceInterface $authenticationService */
        $authenticationService = $this->getAuthenticationService();

        /** @var array $data */
        $data = $this->params()->fromPost();

        /** @var FormInterface $form */
        $form = $this->getFormElementManager()
            ->get(LoginForm::class);

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
                // TODO: flash message
                return $this->redirect()->toRoute('home');
            }
        }

        return (new ViewModel([
            'form' => $form
        ]))->setTemplate('error/403');
    }

    public function joinAction()
    {
        die(__METHOD__);
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
            $this->authenticationService->clearIdentity();
            return $this->redirect()->toRoute('home');
    }
}
