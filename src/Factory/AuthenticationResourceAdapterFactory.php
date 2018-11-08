<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace MSBios\Guard\Factory;

use Interop\Container\ContainerInterface;
use MSBios\Authentication\Factory\CallbackCheckAdapterFactory;
use MSBios\Guard\Authentication\Adapter\CallbackCheckAdapter;
use Zend\Db\Adapter\AdapterInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class AuthenticationResourceAdapterFactory
 * @package MSBios\Guard\Factory
 */
class AuthenticationResourceAdapterFactory extends CallbackCheckAdapterFactory
{
    // ...
}
