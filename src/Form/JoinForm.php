<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
namespace MSBios\Guard\Form;

use Zend\Form\Element;
use Zend\Form\Form;
use Zend\InputFilter\InputFilterAwareInterface;

/**
 * Class JoinForm
 * @package MSBios\Guard\Form
 */
class JoinForm extends Form
{
    /**
     * @inheritdoc
     *
     * @return JoinForm
     */
    public function init(): self
    {
        parent::init();

        $this->add([
            'type' => Element\Text::class,
            'name' => 'username'
        ])->add([
            'type' => Element\Password::class,
            'name' => 'password'
        ])->add([
            'type' => Element\Password::class,
            'name' => 'confirm'
        ])->add([
            'type' => Element\Hidden::class,
            'name' => 'redirect'
        ]);

        return $this;
    }
}
