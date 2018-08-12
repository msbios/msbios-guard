<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace MSBios\Guard\InputFilter;

use Zend\InputFilter\InputFilter;
use Zend\Validator\NotEmpty;
use Zend\Validator\StringLength;

/**
 * Class LoginInputFilter
 * @package MSBios\Guard\InputFilter\LoginInputFilter
 */
class LoginInputFilter extends InputFilter
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->add([
            'name' => 'username',
            'required' => true,
            'validators' => [
                 [
                     'name' => NotEmpty::class,
                 ], [
                     'name' => StringLength::class,
                     'options' => [
                         'min' => 8
                     ],
                 ],
            ],
        ])->add([
            'name' => 'password',
            'required' => true,
            'validators' => [
                // ...
            ],
        ])->add([
            'name' => 'redirect',
            'required' => false,
            'validators' => [
                // ...
            ],
        ]);
    }
}
