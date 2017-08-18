<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace MSBios\Guard\Listener;

use MSBios\Guard\GuardManager;
use Zend\EventManager\EventInterface;
use Zend\Mvc\MvcEvent;

/**
 * Class RenderListener
 * @package MSBios\Guard\Listener
 */
class RenderListener
{
    /**
     * @param EventInterface $e
     */
    public function onRender(MvcEvent $e)
    {
        /** @var string $error */
        $error = $e->getError();

        if (empty($error)) {
            return;
        }

        switch ($error) {
            case DispatchListener::ERROR_UNAUTHORIZED_CONTROLLER:
                $e->setError($error);
                $e->setName(GuardManager::EVENT_FORBIDDEN);
                $e->getTarget()->getEventManager()->triggerEvent($e);
                break;
        }
    }
}