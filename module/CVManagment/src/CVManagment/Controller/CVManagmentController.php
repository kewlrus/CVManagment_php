<?php
namespace CVManagment\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class CVManagmentController extends AbstractActionController {
    protected $_CVManagmentTable;
	
    public function indexAction() 
	{
        return new ViewModel(array(
			'cvdata' => $this->getCVManagmentTable()->fetchAll(),
		));
    }

    public function addAction(){
    }

    public function removeAction() {
    }

    public function updateAction(){
    }
	
	public function getCVManagmentTable() 
	{
        if (!$this->_CVManagmentTable) {
            $sm = $this->getServiceLocator();
            $this->_CVManagmentTable = $sm->get('CVManagment\Model\CVManagmentTable');
        }
        return $this->_CVManagmentTable;
    }

}
?>