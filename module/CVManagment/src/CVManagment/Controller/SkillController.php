<?php
namespace CVManagment\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class SkillController extends AbstractActionController {
    protected $_SkillTable;
	
    public function indexAction() 
	{
        $selectedUserId = (int) $this->params()->fromRoute('id', 0);
			
		return new ViewModel(array(
			'skilldata' => $this->getSkillTable()->fetchAll($selectedUserId),
			'selecteduserid' => $selectedUserId
		));
    }
    public function getskillsAction() 
	{
        $selectedUserId = (int) $this->params()->fromRoute('id', 0);
			
		$result = new ViewModel();
		
		$result->setTerminal(true);
		$result->setVariables(array(
			'skilldata' => $this->getSkillTable()->fetchAll($selectedUserId),
			'selecteduserid' => $selectedUserId
		));
		
		return $result;
    }

    public function addAction(){
		$request = $this->getRequest();
        $response = $this->getResponse();
        if ($request->isPost()) {
            $new_skill = new \CVManagment\Model\Entity\Skill();
			
            $post_data = $request->getPost();
			$user_id = $post_data['userid'];
			
			$new_skill->setUserId($user_id); 
		//	print_r($new_cv);
            if (!$skill_id = $this->getSkillTable()->saveSkill($new_skill))
                $response->setContent(\Zend\Json\Json::encode(array('response' => false)));
            else 
			{
                $response->setContent(\Zend\Json\Json::encode(array('response' => true, 'new_sk_id' => $skill_id)));
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
            $skill_id = $post_data['id'];
            if (!$this->getSkillTable()->removeSkill($skill_id))
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
			
			$skill_id = $post_data['id'];
			$user_id = $post_data['userid'];
            $description = $post_data['description'];
			
            $skill = $this->getSkillTable()->getSkill($skill_id);
			$skill->setId($skill_id)
					->setUserId($user_id)
					->setDescription($description);
		
            if (!$this->getSkillTable()->saveSkill($skill))
                $response->setContent(\Zend\Json\Json::encode(array('response' => false)));
            else {
                $response->setContent(\Zend\Json\Json::encode(array('response' => true)));
            }
        }
        return $response;
    }
	
	public function getSkillTable() 
	{
        if (!$this->_SkillTable) {
            $sm = $this->getServiceLocator();
            $this->_SkillTable = $sm->get('CVManagment\Model\SkillTable');
        }
        return $this->_SkillTable;
    }
}
?>