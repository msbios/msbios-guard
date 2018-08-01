<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
namespace MSBios\Guard\Factory;

use Interop\Container\ContainerInterface;
use MSBios\Guard\Authentication\Adapter\ResourceAdapter;
use MSBios\Guard\Authentication\Storage\ResourceStorage;
use MSBios\Guard\Form\LoginForm;
use MSBios\Guard\InputFilter\LoginInputFilter;
use Zend\Authentication\AuthenticationService;
use Zend\Form\FormInterface;
use Zend\InputFilter\InputFilterInterface;
use Zend\InputFilter\InputFilterPluginManager;
use Zend\ServiceManager\Factory\FactoryInterface;
use Zend\ServiceManager\PluginManagerInterface;

/**
 * Class LoginFormFactory
 * @package MSBios\Guard\Factory
 */
class LoginFormFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return LoginForm|FormInterface
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /** @var FormInterface $form */
        $form = new LoginForm;

        /** @var PluginManagerInterface|InputFilterPluginManager $inputFilterManager */
        $inputFilterManager = $container->get('InputFilterManager');

        if ($inputFilterManager->has($requestedName)) {
            $form->setInputFilter(
                $inputFilterManager->get($requestedName)
            );
        } else if ($inputFilterManager->has(LoginInputFilter::class)) {
            $form->setInputFilter(
                $inputFilterManager->get($requestedName)
            );
        }

        return $form;
    }
}
