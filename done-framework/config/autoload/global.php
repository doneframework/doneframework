<?php
return array(
    'db' => array(
        'driver'         => 'Pdo',
        'dsn'            => 'mysql:dbname=doneframework;host=localhost',
        'driver_options' => array(
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''
        ),
    ),
    'service_manager' => array(
        'factories' => array(
            'Zend\Db\Adapter\Adapter'
                    => 'Zend\Db\Adapter\AdapterServiceFactory',
        ),
    ),
    'navigation' => array( //#REPLACE-NAVIGATION
        'admin' => array(
            'zfcadmin' => array(
                'label' => 'Album',
                'route' => 'zfcadmin',
                'pages' => array(
                     array(
                         'label' => 'Add album',
                         'route' => 'zfcadmin/add'
                     )
                 )
            ),/*
             * STANDARD ROUTE
            'zfcadmin_add' => array(
                'label' => 'Add Album',
                'route' => 'zfcadmin/add',
            ),*/
        ),
    ),
);