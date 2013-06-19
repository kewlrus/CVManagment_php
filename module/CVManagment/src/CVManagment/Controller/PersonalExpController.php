<?php
namespace CVManagment\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class PersonalExpController extends AbstractActionController {
    protected $_PersonalExpTable;
	
    public function indexAction() 
	{
        $selectedUserId = (int) $this->params()->fromRoute('id', 0);
		
		return new ViewModel(array(
			'personalexpdata' => $this->getPersonalExpTable()->fetchAll($selectedUserId),
			'selecteduserid' => $selectedUserId
		));
    }
    public function getpeAction() 
	{
        $selectedUserId = (int) $this->params()->fromRoute('id', 0);
			
		$result = new ViewModel();
		
		$result->setTerminal(true);
		$result->setVariables(array(
			'personalexpdata' => $this->getPersonalExpTable()->fetchAll($selectedUserId),
			'selecteduserid' => $selectedUserId
		));
		
		return $result;
    }

    public function addAction(){
		$request = $this->getRequest();
        $response = $this->getResponse();
        if ($request->isPost()) {
            $new_pe = new \CVManagment\Model\Entity\PersonalExp();
			
            $post_data = $request->getPost();
			$user_id = $post_data['userid'];
			
			$new_pe->setUserId($user_id); 
		//	print_r($new_cv);
            if (!$pe_id = $this->getPersonalExpTable()->savePersonalExp($new_pe))
                $response->setContent(\Zend\Json\Json::encode(array('response' => false)));
            else 
			{
                $response->setContent(\Zend\Json\Json::encode(array('response' => true, 'new_pe_id' => $pe_id)));
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
            $pe_id = $post_data['id'];
            if (!$this->getPersonalExpTable()->removePersonalExp($pe_id))
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
			
			$pe_id = $post_data['id'];
			$user_id = $post_data['userid'];
            $description = $post_data['description'];
            $technologies = $post_data['technologies'];
            $datafrom = $post_data['datafrom'];
            $datato = $post_data['datato'];
			
            $pe = $this->getPersonalExpTable()->getPersonalExp($pe_id);
			$pe->setId($pe_id)
					->setUserId($user_id)
					->setDateFrom($datafrom)
					->setDateTo($datato)
					->setDescription($description)
					->setTechnologies($technologies);
		
            if (!$this->getPersonalExpTable()->savePersonalExp($pe))
                $response->setContent(\Zend\Json\Json::encode(array('response' => false)));
            else {
                $response->setContent(\Zend\Json\Json::encode(array('response' => true)));
            }
        }
        return $response;
    }
		
	public function getPersonalExpTable() 
	{
        if (!$this->_PersonalExpTable) {
            $sm = $this->getServiceLocator();
            $this->_PersonalExpTable = $sm->get('CVManagment\Model\PersonalExpTable');
        }
        return $this->_PersonalExpTable;
    }
}
?>