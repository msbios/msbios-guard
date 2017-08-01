<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace MSBios\Guard\Listener;

use MSBios\Guard\Provider\GuardProviderInterface;
use MSBios\Guard\Provider\ProviderInterface;
use Zend\Config\Config;
use Zend\EventManager\AbstractListenerAggregate as DefaultAbstractListenerAggregate;
use Zend\EventManager\EventInterface;
use Zend\Http\Response as HttpResponse;
use Zend\Http\PhpEnvironment\Response;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\View\Model\ViewModel;

/**
 * Class AbstractListenerAggregate
 * @package MSBios\Guard\Listener
 */
abstract class AbstractListenerAggregate extends DefaultAbstractListenerAggregate implements
    GuardProviderInterface,
    ProviderInterface
{
    /** @var ServiceLocatorInterface */
    protected $serviceLocator;

    /** @var Config */
    protected $options;

    /**
     * AbstractListenerAggregate constructor.
     * @param ServiceLocatorInterface $serviceLocator
     * @param Config $config
     */
    public function __construct(ServiceLocatorInterface $serviceLocator, Config $config)
    {
        $this->serviceLocator = $serviceLocator;
        $this->options = $config;
    }

    /**
     * @param EventInterface $event
     */
    protected function prepareDeniedResponse(EventInterface $event)
    {
        /** @var Response $response */
        $response = $event->getResponse();
        /** @var HttpResponse $response */
        $response = $response ?: new Response();
        $response->setStatusCode(HttpResponse::STATUS_CODE_403);

        $event->getViewModel()
            ->addChild((new ViewModel)->setTemplate('error/403'));

        $event->setResponse($response);
    }
}
