<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
namespace MSBios\Guard\Listener;

use MSBios\Guard\GuardManager;
use Zend\EventManager\EventInterface;

/**
 * Class RenderListener
 * @package MSBios\Guard\Listener
 */
class RenderListener
{
    /**
     * @param EventInterface $e
     */
    public function onDispatchError(EventInterface $e)
    {
        /** @var string $error */
        $error = $e->getError();

        if (empty($error)) {
            return;
        }

        if ($error != DispatchListener::ERROR_UNAUTHORIZED_CONTROLLER) {
            return;
        }

        $e->setError($error);
        $e->setName(GuardManager::EVENT_FORBIDDEN);
        $e->getTarget()->getEventManager()->triggerEvent($e);
    }
}