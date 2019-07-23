<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
namespace MSBios\Guard\Factory;

use Interop\Container\ContainerInterface;
use Zend\Form\FormInterface;
use Zend\InputFilter\InputFilterPluginManager;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class FormInvokableFactory
 * @package MSBios\Guard\Factory
 */
class FormInvokableFactory implements FactoryInterface
{
    /**
     * @inheritdoc
     *
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return FormInterface
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /** @var FormInterface $form */
        $form = new $requestedName;

        /** @var InputFilterPluginManager $inputFilterManager */
        $inputFilterManager = $container
            ->get('InputFilterManager');

        if ($inputFilterManager->has($requestedName)) {
            $form->setInputFilter(
                $inputFilterManager->get($requestedName)
            );
        }

        return $form;
    }
}
