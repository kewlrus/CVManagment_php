<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'CVManagment\Controller\CVManagment' => 'CVManagment\Controller\CVManagmentController',
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
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'cvmanagment' => __DIR__ . '/../view',
        ),
    ),
);
?>