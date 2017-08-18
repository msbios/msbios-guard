<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
namespace MSBios\Guard\Router\Http;

use MSBios\Guard\GuardAwareInterface;
use Zend\Router\Http\Literal as DefaultLiteral;

/**
 * Class Literal
 * @package MSBios\Guard\Router\Http
 */
class Literal extends DefaultLiteral implements GuardAwareInterface
{

}
