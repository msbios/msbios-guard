<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace MSBios\Guard\Router\Http;

use MSBios\Guard\GuardInterface;
use Zend\Router\Http\TreeRouteStack as DefaultTreeRouteStack;
use Zend\Router\RouteInterface;
use Zend\Router\RouteMatch as DefaultRouteMatch;
use Zend\Stdlib\RequestInterface as Request;

/**
 * Class TreeRouteStack
 * @package MSBios\Guard\Router\Http
 */
class TreeRouteStack extends DefaultTreeRouteStack
{
    /**
     * @param Request $request
     * @param null $pathOffset
     * @param array $options
     * @return RouteMatch|DefaultRouteMatch
     */
    public function match(Request $request, $pathOffset = null, array $options = [])
    {
        /** @var DefaultRouteMatch $match */
        $match = parent::match($request, $pathOffset, $options);

        if ($match instanceof DefaultRouteMatch) {

            /** @var RouteInterface $route */
            $route = $this->routes->get(
                $match->getMatchedRouteName()
            );

            if ($route instanceof GuardInterface) {
                return new RouteMatch($match);
            }
        }

        return $match;
    }
}
