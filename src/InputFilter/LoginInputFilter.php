<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace MSBios\Guard\InputFilter;

use Zend\InputFilter\InputFilter;

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
                // [
                //     'name' => 'not_empty',
                // ], [
                //     'name' => 'string_length',
                //     'options' => [
                //         'min' => 8
                //     ],
                // ],
            ],
        ])->add([
            'name' => 'password',
            'required' => true,
            'validators' => [
                // ...
            ],
        ]);
    }

}