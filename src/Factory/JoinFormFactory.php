<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
namespace MSBios\Guard\Factory;

use Interop\Container\ContainerInterface;
use MSBios\Guard\Form\JoinForm;
use MSBios\Guard\Form\LoginForm;
use MSBios\Guard\InputFilter\LoginInputFilter;
use MSBios\Guard\Validator\Db\UsernameExists;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Form\FormInterface;
use Zend\InputFilter\InputFilterPluginManager;
use Zend\ServiceManager\Factory\FactoryInterface;
use Zend\Validator\Db\NoRecordExists;
use Zend\Validator\Db\RecordExists;

/**
 * Class JoinFormFactory
 * @package MSBios\Guard\Factory
 */
class JoinFormFactory extends FormInvokableFactory
{
    /**
     * @inheritdoc
     *
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return JoinForm
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /** @var FormInterface $form */
        $form = parent::__invoke($container, $requestedName, $options);

        $form->getInputFilter()
            ->get('username')
            ->getValidatorChain()
            ->attach(new NoRecordExists([
                'table' => 'acl_t_users',
                'field' => 'username',
                'adapter' => $container->get(AdapterInterface::class)
            ]));

        return $form;
    }
}
