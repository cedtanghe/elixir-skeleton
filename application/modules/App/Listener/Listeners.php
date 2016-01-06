<?php

namespace App\Listener;

use Elixir\Dispatcher\DispatcherInterface;
use Elixir\Module\AppBase\Listener\Listeners as ParentListeners;
use Elixir\MVC\ApplicationEvent;
use Elixir\Security\Firewall\Utils;

class Listeners extends ParentListeners
{
    public function subscribe(DispatcherInterface $dispatcher)
    {   
        parent::subscribe($dispatcher);
        
        /************ SECURITY ************/
        
        $config = $this->_container->get('config');
        
        if ($config->get(['enable', 'security'], false))
        {
            $dispatcher->addListener(ApplicationEvent::FILTER_REQUEST, function(ApplicationEvent $e)
            {
                $resource = Utils::createResource($e->getRequest());
                $e->getRequest()->getAttributes()->set('CURRENT_PAGE', $resource);
                
                $firewall = $this->_container->get('security');
                $firewall->analyze($resource);
            }, 850);
        }
    }
}
