<?php
namespace CVManagment\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class CVManagmentController extends AbstractActionController {
    protected $_CVManagmentTable;
    protected $_UserTable;
	
    public function indexAction() 
	{
        $selectedUserId = (int) $this->params()->fromRoute('id', 0);
			
		return new ViewModel(array(
			'cvdata' => $this->getCVManagmentTable()->fetchAll($selectedUserId),
			'userdata' => $this->getUserTable()->fetchAll(),
			'selecteduserid' => $selectedUserId
		));
    }
    public function getjobsAction() 
	{
        $selectedUserId = (int) $this->params()->fromRoute('id', 0);
			
		$result = new ViewModel();
		
		$result->setTerminal(true);
		$result->setVariables(array(
			'cvdata' => $this->getCVManagmentTable()->fetchAll($selectedUserId),
			'userdata' => $this->getUserTable()->fetchAll(),
			'selecteduserid' => $selectedUserId
		));
		
		return $result;
    }

    public function addAction(){
		$request = $this->getRequest();
        $response = $this->getResponse();
        if ($request->isPost()) {
            $new_cv = new \CVManagment\Model\Entity\CVManagment();
			
            $post_data = $request->getPost();
			$user_id = $post_data['userid'];
			
			$new_cv->setUserId($user_id); 
			$new_cv->setEmployerId(1);
		//	print_r($new_cv);
            if (!$cv_id = $this->getCVManagmentTable()->saveCVData($new_cv))
                $response->setContent(\Zend\Json\Json::encode(array('response' => false)));
            else 
			{
                $response->setContent(\Zend\Json\Json::encode(array('response' => true, 'new_cv_id' => $cv_id)));
            }
        }
        return $response;
    }

    public function removeAction() 
	{
        $request = $this->getRequest();
        $response = $this->getResponse();
        if ($request->isPost()) {
            $post_data = $request->getPost();
            $cv_id = $post_data['id'];
            if (!$this->getCVManagmentTable()->removeCVData($cv_id))
                $response->setContent(\Zend\Json\Json::encode(array('response' => false)));
            else {
                $response->setContent(\Zend\Json\Json::encode(array('response' => true)));
            }
        }
        return $response;
    }

    public function updateAction()
	{
		// update post
        $request = $this->getRequest();
        $response = $this->getResponse();
        if ($request->isPost()) {
            $post_data = $request->getPost();
			
			$cv_id = $post_data['id'];
			$user_id = $post_data['userid'];
            $employerid = $post_data['employerid'];
            $description = $post_data['description'];
            $technologies = $post_data['technologies'];
            $datafrom = $post_data['datafrom'];
            $datato = $post_data['datato'];
			
            $cv = $this->getCVManagmentTable()->getCVData($cv_id);
			$cv->setId($cv_id)
					->setEmployerId($employerid)
					->setUserId($user_id)
					->setDateFrom($datafrom)
					->setDateTo($datato)
					->setDescription($description)
					->setTechnologies($technologies);
		
            if (!$this->getCVManagmentTable()->saveCVData($cv))
                $response->setContent(\Zend\Json\Json::encode(array('response' => false)));
            else {
                $response->setContent(\Zend\Json\Json::encode(array('response' => true)));
            }
        }
        return $response;
    }
	
	public function getCVManagmentTable() 
	{
        if (!$this->_CVManagmentTable) {
            $sm = $this->getServiceLocator();
            $this->_CVManagmentTable = $sm->get('CVManagment\Model\CVManagmentTable');
        }
        return $this->_CVManagmentTable;
    }
	
	public function getUserTable() 
	{
        if (!$this->_UserTable) {
            $sm = $this->getServiceLocator();
            $this->_UserTable = $sm->get('CVManagment\Model\UserTable');
        }
        return $this->_UserTable;
    }
}
?>