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
		$request = $this->getRequest();
        $response = $this->getResponse();
        if ($request->isPost()) {
		echo "AAAAAAAAAAAAs";
            $new_cv = new \CVManagment\Model\Entity\CVManagment();
			$new_cv->setEmployerId(1);
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
            $employerid = $post_data['employerid'];
            $description = $post_data['description'];
            $technologies = $post_data['technologies'];
            $datafrom = $post_data['datafrom'];
            $datato = $post_data['datato'];
			
            $cv = $this->getCVManagmentTable()->getCVData($cv_id);
			$cv->setId($cv_id)
					->setEmployerId($employerid)
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

}
?>