<?php
namespace CVManagment;

use CVManagment\Model\CVManagmentTable;
use CVManagment\Model\UserTable;
use CVManagment\Model\PersonalExpTable;
use CVManagment\Model\SkillTable;

class Module
{
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
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
	
	public function getServiceConfig() 
	{
        return array(
            'factories' => array(
                'CVManagment\Model\CVManagmentTable' => function($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $table = new CVManagmentTable($dbAdapter);
                    return $table;
                },
                'CVManagment\Model\UserTable' => function($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $table = new UserTable($dbAdapter);
                    return $table;
                },
                'CVManagment\Model\SkillTable' => function($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $table = new SkillTable($dbAdapter);
                    return $table;
                },
                'CVManagment\Model\PersonalExpTable' => function($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $table = new PersonalExpTable($dbAdapter);
                    return $table;
                },
            ),
        );
    }
}

?>