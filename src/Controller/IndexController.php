<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace MSBios\Guard\Controller;

use MSBios\Guard\GuardInterface;
use Zend\Authentication\Adapter\AdapterInterface;
use Zend\Authentication\AuthenticationServiceInterface;
use Zend\Authentication\Result;
use Zend\Mvc\Controller\AbstractActionController;

/**
 * Class IndexController
 * @package MSBios\Guard\Controller
 */
class IndexController extends AbstractActionController implements GuardInterface
{

    /** @var  AuthenticationServiceInterface */
    protected $authenticationService;

    /**
     * IndexController constructor.
     * @param AuthenticationServiceInterface $authenticationService
     */
    public function __construct(AuthenticationServiceInterface $authenticationService)
    {
        $this->authenticationService = $authenticationService;
    }

    /**
     * @return \Zend\Http\Response|\Zend\View\Model\ViewModel
     */
    public function indexAction()
    {

        // Logout logic
        if ($this->params()->fromQuery('logout', false) && $this->authenticationService->hasIdentity()) {
            $this->authenticationService->clearIdentity();
            return $this->redirect()->toRoute('home');
        }

        // Login logic
        if (!$this->authenticationService->hasIdentity() && $this->getRequest()->isPost()) {

            /** @var array $data */
            $data = $this->params()->fromPost();

            /** @var AdapterInterface $authenticationAdapter */
            $authenticationAdapter = $this->authenticationService->getAdapter();
            $authenticationAdapter->setIdentity($data['username']);
            $authenticationAdapter->setCredential($data['password']);

            /** @var Result $authenticationResult */
            $authenticationResult = $this->authenticationService->authenticate();

            if ($authenticationResult->isValid()) {
                // TODO: flash message
                return $this->redirect()->toRoute('home');
            }
        }

        return parent::indexAction();
    }

}