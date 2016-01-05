<?php

use Elixir\MVC\Application;

return [
    
    /************ EXAMPLE SECURE ACCESS ************/
    
    [
        'regex' => '/ADMIN/',
        'options' => ['domains' => 'admin']
    ],
    
    /************ GLOBAL OPTIONS ************/
    
    '_globals' => [
        'identity_not_found_uri' => function()
        {  
            return Application::$registry->get('helper.url')->generate('HOME');
        }
    ]
];