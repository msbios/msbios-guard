<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
namespace MSBios\Guard\Factory;

use Interop\Container\ContainerInterface;
use MSBios\Guard\Form\LoginForm;
use MSBios\Guard\InputFilter\LoginInputFilter;
use Zend\InputFilter\InputFilterPluginManager;
use Zend\ServiceManager\Factory\FactoryInterface;

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
     * @return LoginForm|object
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /** @var LoginForm $form */
        $form = new LoginForm;

        /** @var InputFilterPluginManager $inputFilterManager */
        $inputFilterManager = $container
            ->get('InputFilterManager');

        $form->setInputFilter(
            $inputFilterManager->get(LoginInputFilter::class)
        );

        return $form;
    }
}
