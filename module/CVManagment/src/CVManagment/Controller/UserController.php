<?php
namespace CVManagment\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class UserController extends AbstractActionController {
    protected $_UserTable;
	
    public function indexAction() 
	{
      //  $id = (int) $this->params()->fromRoute('id', 0);
		//echo 'asdasd: '.$id;
		return new ViewModel(array(
			'userdata' => $this->getUserTable()->fetchAll(),
			'selecteduserid' = > $id
		));
    }

    public function addAction(){
		/*$request = $this->getRequest();
        $response = $this->getResponse();
        if ($request->isPost()) {
            $new_cv = new \CVManagment\Model\Entity\User();
			$new_cv->setUserId(1); 
			$new_cv->setEmployerId(1);
			print_r($new_cv);
            if (!$cv_id = $this->getUserTable()->saveCVData($new_cv))
                $response->setContent(\Zend\Json\Json::encode(array('response' => false)));
            else 
			{
                $response->setContent(\Zend\Json\Json::encode(array('response' => true, 'new_cv_id' => $cv_id)));
            }
        }
        return $response;*/
    }

    public function removeAction() 
	{
      /*  $request = $this->getRequest();
        $response = $this->getResponse();
        if ($request->isPost()) {
            $post_data = $request->getPost();
            $cv_id = $post_data['id'];
            if (!$this->getUserTable()->removeCVData($cv_id))
                $response->setContent(\Zend\Json\Json::encode(array('response' => false)));
            else {
                $response->setContent(\Zend\Json\Json::encode(array('response' => true)));
            }
        }
        return $response;*/
    }

    public function updateAction()
	{
	/*	// update post
        $request = $this->getRequest();
        $response = $this->getResponse();
        if ($request->isPost()) {
            $post_data = $request->getPost();
			
			$cv_id = $post_data['id'];
            $employerid = $post_data['employerid'];
            $description = $post_data['description'];
            $technologies = $post_data['technologies'];
            $datafrom = $post_data['datafrom'];
            $datato = $post_data['datato'];
			
            $cv = $this->getUserTable()->getCVData($cv_id);
			$cv->setId($cv_id)
					->setEmployerId($employerid)
					->setDateFrom($datafrom)
					->setDateTo($datato)
					->setDescription($description)
					->setTechnologies($technologies);
		
            if (!$this->getUserTable()->saveCVData($cv))
                $response->setContent(\Zend\Json\Json::encode(array('response' => false)));
            else {
                $response->setContent(\Zend\Json\Json::encode(array('response' => true)));
            }
        }
        return $response;*/
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