<?php
namespace CVManagment\Model;

use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Expression;

class UserTable extends AbstractTableGateway {

    protected $table = 'users';

    public function __construct(Adapter $adapter) {
        $this->adapter = $adapter;
    }
	
    public function fetchAll() {
		$resultSet = $this->select();
        $entities = array();
        foreach ($resultSet as $row) {
            $entity = new Entity\User();
            $entity->setId($row->ID)
                    ->setName($row->NAME)
                    ->setEmail($row->EMAIL)
                    ->setSkype($row->SKYPE)
                    ->setUrl($row->URL)
                    ->setPhone($row->PHONE);
            $entities[] = $entity;
        }
        return $entities;
    }
	
    /**
     * Select
     *
     * @param Where|\Closure|string|array $where
     * @return ResultSet
     */
    public function select($where = '', $closure = null)
    {
        if (!$this->isInitialized) {
            $this->initialize();
        }

        $select = $this->sql->select();

        if ($closure instanceof \Closure) {
            $closure($select);
        } 
		
		if ($where !== '') {
            $select->where($where);
        }

        return $this->selectWith($select);
    }
	
    public function getUser($id) 
	{
        $row = $this->select(array('id' => (int) $id))->current();
		
        if (!$row)
            return false;

        $UserData = new Entity\User(array(
					'ID' => $row->ID,
                    'NAME' => $row->NAME,
					'EMAIL' => $row->EMAIL,
                    'SKYPE' => $row->SKYPE,
                    'PHONE' => $row->PHONE,
                    'URL' => $row->URL,
                ));
        return $UserData;
	}
	
    public function saveUser(Entity\User $User) 
	{
       $data = array(
			'ID' => $User->getId(),
			'NAME' => $User->getName(),
			'EMAIL' => $User->getEmail(),
			'SKYPE' => $User->getSkype(),
			'PHONE' => $User->getPhone(),
			'URL' => $User->getUrl(),
        );

        $id = (int) $User->getId();

        if ($id == 0) {
            if (!$this->insert($data))
                return false;
            return $this->getLastInsertValue();
        }
        elseif ($this->getUser($id)) {
            if (!$this->update($data, array('id' => $id)))
                return false;
            return $id;
        }
        else
            return false;
	}
	
    public function removeUser($id) 
	{
        return $this->delete(array('id' => (int) $id));
	}
}

?>