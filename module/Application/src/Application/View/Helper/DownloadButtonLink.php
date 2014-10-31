<?php

namespace Application\View\Helper;

use Zend\Mvc\MvcEvent;
use Zend\View\Helper\AbstractHelper;

final class DownloadButtonLink extends AbstractHelper
{
    /**
     * @var MvcEvent
     */
    private $mvcEvent;

    /**
     * Construct mvcEvent property
     */
    public function __construct(MvcEvent $mvcEvent)
    {
        $this->mvcEvent = $mvcEvent;
    }

    /**
     * Download link
     * @param string $compress 'zip' | 'tgz' | other to make option
     * @return string
     */
    public function __invoke($compress = 'zip')
    {
        $controller = $this->mvcEvent->getRouteMatch()->getParam('controller');
        $moduleNamespace = substr($controller, 0, strpos($controller, '\\'));

        return $this->view->url('download', array(
            'module'   => $moduleNamespace,
            'compress' => $compress
        ));
    }
}
