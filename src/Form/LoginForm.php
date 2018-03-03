<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace MSBios\Guard\Form;

use Zend\Form\Element\Hidden;
use Zend\Form\Element\Password;
use Zend\Form\Element\Text;
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
            'type' => Text::class,
            'name' => 'username'
        ]);

        $this->add([
            'type' => Password::class,
            'name' => 'password'
        ]);

        $this->add([
            'type' => Hidden::class,
            'name' => 'redirect'
        ]);
    }
}
