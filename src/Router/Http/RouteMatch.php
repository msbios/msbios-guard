<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
namespace MSBios\Guard\Router\Http;

use MSBios\Guard\GuardInterface;
use Zend\Router\Http\RouteMatch as DefaultRouteMatch;

/**
 * Class RouteMatch
 * @package MSBios\Guard\Router
 */
class RouteMatch extends DefaultRouteMatch implements GuardInterface
{
    /**
     * RouteMatch constructor.
     * @param DefaultRouteMatch $routeMatch
     */
    public function __construct(DefaultRouteMatch $routeMatch)
    {
        $this->setMatchedRouteName($routeMatch->getMatchedRouteName());
        parent::__construct(
            $routeMatch->getParams(),
            $routeMatch->getLength()
        );
    }
}
