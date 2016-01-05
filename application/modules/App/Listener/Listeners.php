<?php

namespace App\Listener;

use Elixir\Dispatcher\DispatcherInterface;
use Elixir\Module\AppBase\Listener\Listeners as ParentListeners;
use Elixir\MVC\ApplicationEvent;
use Elixir\Security\Firewall\Utils;

class Listeners extends ParentListeners
{
    public function subscribe(DispatcherInterface $pDispatcher)
    {   
        parent::subscribe($pDispatcher);
        
        /************ SECURITY ************/
        
        $pDispatcher->addListener(ApplicationEvent::FILTER_REQUEST, function(ApplicationEvent $e) use($container)
        {
            $resource = Utils::createResource($e->getRequest());
            $e->getRequest()->getAttributes()->set('CURRENT_PAGE', $resource);
            
            $firewall = $container->get('security');
            $firewall->analyze($resource);
        }, 850);
    }
}
