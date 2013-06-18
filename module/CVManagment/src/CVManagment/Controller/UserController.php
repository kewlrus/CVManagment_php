<?php
namespace CVManagment\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class UserController extends AbstractActionController {
    protected $_UserTable;
	
    public function indexAction() 
	{
        $selectedUserId = (int) $this->params()->fromRoute('id', 0);
		
		return new ViewModel(array(
			'userdata' => $this->getUserTable()->fetchAll(),
			'selecteduserid' => $selectedUserId
		));
    }

    public function addAction(){
		$request = $this->getRequest();
        $response = $this->getResponse();
        if ($request->isPost()) {
            $new_user = new \CVManagment\Model\Entity\User();
			if (!$user_id = $this->getUserTable()->saveUser($new_user))
                $response->setContent(\Zend\Json\Json::encode(array('response' => false)));
            else 
			{
                $response->setContent(\Zend\Json\Json::encode(array('response' => true, 'new_user_id' => $user_id)));
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
            $user_id = $post_data['userid'];
            if (!$this->getUserTable()->removeUser($user_id))
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
			
			$user_id = $post_data['userid'];
            $name = $post_data['name'];
            $email = $post_data['email'];
            $skype = $post_data['skype'];
            $phone = $post_data['phone'];
            $url = $post_data['url'];
			
            $userentity = $this->getUserTable()->getUser($user_id);
			
            $userentity->setId($user_id)
                    ->setName($name)
                    ->setEmail($email)
                    ->setSkype($skype)
                    ->setUrl($phone)
                    ->setPhone($url);
		
            if (!$this->getUserTable()->saveUser($userentity))
                $response->setContent(\Zend\Json\Json::encode(array('response' => false)));
            else {
                $response->setContent(\Zend\Json\Json::encode(array('response' => true)));
            }
        }
        return $response;
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