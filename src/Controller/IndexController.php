<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace MSBios\Guard\Controller;

use MSBios\Application\Controller\IndexController as DefaultIndexController;
use MSBios\Guard\GuardInterface;

/**
 * Class IndexController
 * @package MSBios\Guard\Controller
 */
class IndexController extends DefaultIndexController implements GuardInterface
{
    // ...
}
