<?php
return array(
     'controllers' => array(
         'invokables' => array(
             'DoneGenericModule\Controller\DoneGenericModule' => 'DoneGenericModule\Controller\DoneGenericModuleController',
         ),
     ),
     // The following section is new and should be added to your file
     'router' => array(
         'routes' => array(
                'zfcadmin' =>  //#This is the route //#REPLACE-ROUTE
                array(
                        'options' => array(
                        'route'    => '/backend', //#REPLACE-ALIAS
                        'constraints' => array(
                            'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                            'id'     => '[0-9]+',
                        ),
                        'defaults' => array(
                            'controller' => 'DoneGenericModule\Controller\DoneGenericModule',
                            'action'     => 'index',
                        ),
                    ),
                     'child_routes' => array( 
                        //#REPLACE-CHILD
                        'edit' => array( 
                            'type' => 'segment', 
                            'options' => array( 
                                'route' => '/edit[/:action][/:id]', 
                                'constraints' => array( 
                                    'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
                                    'id'     => '[0-9]+', 
                                ), 
                                'defaults' => array( 
                                    'controller' => 'DoneGenericModule\Controller\DoneGenericModule',
                                    'action'     => 'edit', 
                                    'id'=>0 
                                ), 
                            ), 
                        ),
                        //#REPLACE-CHILD
                        'add' => array( 
                            'type' => 'Literal', 
                            'options' => array( 
                                'route' => '/add', 
                                'defaults' => array( 
                                    'controller' => 'DoneGenericModule\Controller\DoneGenericModule',
                                    'action'     => 'add', 
                                ), 
                            ), 
                        ),
                        //#REPLACE-CHILD
                        'delete' => array( 
                            'type' => 'segment', 
                            'options' => array( 
                                'route' => '/delete[/:action][/:id]', 
                                'constraints' => array( 
                                    'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
                                    'id'     => '[0-9]+', 
                                ), 
                                'defaults' => array( 
                                    'controller' => 'DoneGenericModule\Controller\DoneGenericModule',
                                    'action'     => 'delete', 
                                    'id'=>0 
                                ), 
                            ), 
                        ), 

                    ), 
                ),
         ),
     ),

     'view_manager' => array(
         'template_path_stack' => array(
             'zfcadmin' => __DIR__ . '/../view',
         ),
     ),
 );
