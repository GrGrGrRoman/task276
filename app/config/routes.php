<?php 

return
[
    // MainController
    '' =>
    [
        'controller' => 'main',
        'action' => 'index',
    ],
    
    'secret' =>
    [
        'controller' => 'main',
        'action' => 'secret',
    ],

    // Account Controller    
    'login' =>
    [
        'controller' => 'account',
        'action' => 'login',
    ],
    'logout' =>
    [
        'controller' => 'account',
        'action' => 'logout',
    ],

    'register' =>
    [
        'controller' => 'account',
        'action' => 'register',
    ],
    'register/{id:\d+}' =>
    [
        'controller' => 'account',
        'action' => 'register',
    ],
    'register_google' =>
    [
        'controller' => 'account',
        'action' => 'register_google',
    ],
    'register_google/{code:.+}' =>
    [
        'controller' => 'account',
        'action' => 'register_google',
    ],
    'list' =>
    [
        'controller' => 'account',
        'action' => 'list',
    ],
];