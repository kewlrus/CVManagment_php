<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'CVManagment\Controller\CVManagment' => 'CVManagment\Controller\CVManagmentController',
            'CVManagment\Controller\User' => 'CVManagment\Controller\UserController',
        ),
    ),
    'router' => array(
        'routes' => array(
            'cvmanagment' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/cvmanagment[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'CVManagment\Controller\CVManagment',
                        'action' => 'index',
                    ),
                ),
            ),
            'user' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/user[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'CVManagment\Controller\User',
                        'action' => 'index',
                    ),
                ),
            ),
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'cvmanagment' => __DIR__ . '/../view',
        ),
    ),
);
?>