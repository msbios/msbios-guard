<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
namespace MSBios\Guard\Listener;

use Zend\EventManager\EventInterface;

/**
 * Class DispatchListener
 * @package MSBios\Guard\Listener
 */
class DispatchListener
{
    /**
     * @param EventInterface $e
     */
    public function onDispatch(EventInterface $e)
    {

        r($e->getTarget());
        // echo __METHOD__; die();
    }
}