<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
namespace MSBios\Guard\Router\Http;

use Zend\Permissions\Acl\Resource\ResourceInterface;
use Zend\Router\Http\RouteMatch;

/**
 * Class ProxyRouteMatch
 * @package MSBios\Guard\Router\Http
 */
class ProxyRouteMatch extends RouteMatch implements ResourceInterface
{
    /**
     * ProxyRouteMatch constructor.
     *
     * @param RouteMatch $routeMatch
     */
    public function __construct(RouteMatch $routeMatch)
    {
        $this->setMatchedRouteName($routeMatch->getMatchedRouteName());

        parent::__construct(
            $routeMatch->getParams(),
            $routeMatch->getLength()
        );
    }

    /**
     * @inheritdoc
     *
     * @return string
     */
    public function getResourceId()
    {
        return $this->getMatchedRouteName();
    }
}
