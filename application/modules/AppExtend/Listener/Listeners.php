<?php

namespace AppExtend\Listener;

use Elixir\Dispatcher\DispatcherInterface;
use Elixir\Module\Application\Listener\Listeners as ParentListeners;

class Listeners extends ParentListeners
{
    public function subscribe(DispatcherInterface $pDispatcher)
    {   
        parent::subscribe($pDispatcher);
    }
    
    public function unsubscribe(DispatcherInterface $pDispatcher)
    {
        // Not yet
    }
}
