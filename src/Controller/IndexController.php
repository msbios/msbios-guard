<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace MSBios\Guard\Controller;

use MSBios\Application\Controller\IndexController as DefaultIndexController;
use Zend\Permissions\Acl\Resource\ResourceInterface;

/**
 * Class IndexController
 * @package MSBios\Guard\Controller
 */
class IndexController extends DefaultIndexController implements ResourceInterface
{
    /**
     * @inheritdoc
     *
     * @return mixed
     */
    public function getResourceId()
    {
        return DefaultIndexController::class;
    }
}
