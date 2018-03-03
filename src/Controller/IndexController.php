<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace MSBios\Guard\Controller;

use MSBios\Application\Controller\IndexController as DefaultIndexController;
use MSBios\Authentication\AuthenticationServiceAwareInterface;
use MSBios\Authentication\AuthenticationServiceAwareTrait;
use MSBios\Guard\GuardInterface;
use Zend\Authentication\Adapter\AdapterInterface;
use Zend\Authentication\Result;

/**
 * Class IndexController
 * @package MSBios\Guard\Controller
 */
class IndexController extends DefaultIndexController implements
    GuardInterface,
    AuthenticationServiceAwareInterface
{
    use AuthenticationServiceAwareTrait;

    /**
     * @return \Zend\Http\Response|\Zend\View\Model\ViewModel
     */
    public function indexAction()
    {
        // Logout logic
        if ($this->params()->fromQuery('logout', false) && $this->getAuthenticationService()->hasIdentity()) {
            $this->getAuthenticationService()->clearIdentity();
            return $this->redirect()->toRoute('home');
        }

        // Login logic
        if (! $this->getAuthenticationService()->hasIdentity() && $this->getRequest()->isPost()) {

            /** @var array $data */
            $data = $this->params()->fromPost();

            /** @var AdapterInterface $authenticationAdapter */
            $authenticationAdapter = $this->getAuthenticationService()->getAdapter();
            $authenticationAdapter->setIdentity($data['username']);
            $authenticationAdapter->setCredential($data['password']);

            /** @var Result $authenticationResult */
            $authenticationResult = $this->getAuthenticationService()->authenticate();

            if ($authenticationResult->isValid()) {
                // TODO: flash message
                return $this->redirect()->toRoute('home');
            }
        }

        return parent::indexAction();
    }
}
