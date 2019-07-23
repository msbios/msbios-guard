<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace MSBios\Guard\Router\Http;

use MSBios\Guard\GuardInterface;
use Zend\Router\Http\TreeRouteStack as DefaultTreeRouteStack;
use Zend\Router\RouteInterface;
use Zend\Router\RouteMatch;
use Zend\Stdlib\RequestInterface as Request;

/**
 * Class TreeRouteStack
 * @package MSBios\Guard\Router\Http
 */
class TreeRouteStack extends DefaultTreeRouteStack
{

    protected $protectedRoutes = [
        Literal::class
    ];

    /**
     * @inheritdoc
     *
     * @param Request $request
     * @param null $pathOffset
     * @param array $options
     * @return ProxyRouteMatch|RouteMatch
     */
    public function match(Request $request, $pathOffset = null, array $options = [])
    {
        /** @var RouteMatch $match */
        $match = parent::match($request, $pathOffset, $options);

        if ($match instanceof RouteMatch) {

            /** @var RouteInterface $route */
            $route = $this->routes->get(
                $match->getMatchedRouteName()
            );

            if (in_array(get_class($route), $this->protectedRoutes) ) {
                return new ProxyRouteMatch($match);
            }
        }

        return $match;
    }
}
