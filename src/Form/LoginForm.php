<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
namespace MSBios\Guard\Form;

use Zend\Form\Element;
use Zend\Form\Form;

/**
 * Class LoginForm
 * @package MSBios\Guard\Form
 */
class LoginForm extends Form
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->add([
            'type' => Element\Text::class,
            'name' => 'username'
        ])->add([
            'type' => Element\Password::class,
            'name' => 'password'
        ])->add([
            'type' => Element\Hidden::class,
            'name' => 'redirect'
        ]);
    }
}
