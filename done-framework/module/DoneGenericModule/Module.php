<?php
namespace DoneGenericModule;
use DoneGenericModule\Model\DoneGenericModule;

use DoneGenericModule\Model\DoneGenericModuleTable;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;

use Zend\Mvc\MvcEvent;
use Zend\Mvc\Router\RouteMatch;

class Module implements AutoloaderProviderInterface, ConfigProviderInterface
{
    
    protected $whitelist = array('zfcuser/login', 'zfcuser/register');

    public function onBootstrap($e)
    {
        $app = $e->getApplication();
        $em  = $app->getEventManager();
        $sm  = $app->getServiceManager();

        $whitelist = $this->whitelist;
        $zfcuser_auth = $sm->get('zfcuser_auth_service');

        $em->attach(MvcEvent::EVENT_ROUTE, function($e) use ($whitelist, $zfcuser_auth) {
            
            $match = $e->getRouteMatch();
            if (!$match instanceof RouteMatch) {
                return;
            }

            $name = $match->getMatchedRouteName();
            if (in_array($name, $whitelist)) {
                return;
            }

            if ($zfcuser_auth->hasIdentity()) {
                //#retreive class metod id
                $ary_requested_controller = $match->getParams()['controller'];
                $ary_requested_module = explode('\\',$ary_requested_controller);
                $requested_module = $ary_requested_module[0];
                if(isset($ary_requested_module[2])){
                    $requested_controller = $ary_requested_module[2];
                }else{
                    $requested_controller = '';
                }
                
                //#Set permission
                $app = $e->getApplication();
                $sm  = $app->getServiceManager();
                $permission = $this->getPermission($sm, $zfcuser_auth);
                $zfcuser_auth->getIdentity()->setPermission($permission);
                return;
            }

            $router   = $e->getRouter();
            $url      = $router->assemble(array(), array(
                'name' => 'zfcuser/login'
            ));

            $response = $e->getResponse();
            $response->getHeaders()->addHeaderLine('Location', $url);
            $response->setStatusCode(302);

            return $response;
        }, -100);
    }
    //-
    
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php', //#REPLACE-MODULE
                '/var/www/html/doneframework/vendor/done-framework/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    // Add this method:
    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'DoneGenericModule\Model\DoneGenericModuleTable' =>  function($sm) {
                    $tableGateway = $sm->get('DoneGenericModuleTableGateway');
                    $table = new DoneGenericModuleTable($tableGateway);
                    return $table;
                },
                'DoneGenericModuleTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new DoneGenericModule());
                    return new TableGateway('album', $dbAdapter, null, $resultSetPrototype);//#REPLACE-TABLE
                },
            ),
        );
    }
    
    public function getPermission($sm, $zfcuser_auth)
    {
        $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');

        $form_table = new TableGateway('view__form_field_user_db', $dbAdapter);
        $forms_rowset = $form_table->select(array('user_id' => $zfcuser_auth->getIdentity()->getId() ) ) ;

        $result_table = new TableGateway('view__result_field_user_db', $dbAdapter);
        $tables_rowset = $result_table->select(array('user_id' => $zfcuser_auth->getIdentity()->getId() ) ) ;

        return array(
            'forms' => $forms_rowset,
            'results' => $tables_rowset
            );
    }
}
