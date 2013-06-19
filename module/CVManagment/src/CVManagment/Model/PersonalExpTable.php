<?php
namespace CVManagment\Model;

use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Expression;

class PersonalExpTable extends AbstractTableGateway {

    protected $table = 'PersonalExp';

    public function __construct(Adapter $adapter) {
        $this->adapter = $adapter;
    }
	
    public function fetchAll($user_id = 1) {
		$entities = array();		
		
		$where_clause = ' user_id = ' . $user_id;
		$resultSet = $this->select($where_clause, function (Select $select) {
			$select->order('DATE_FROM DESC');	
		});	
		
		foreach ($resultSet as $row) 
		{			
			$entity = new Entity\PersonalExp();
			$entity->setId($row->ID)
					->setUserId($row->USER_ID)
					->setDateFrom($row->DATE_FROM)
					->setDateTo($row->DATE_TO)
					->setDescription($row->DESCRIPTION)
					->setTechnologies($row->TECHNOLOGIES);
					
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
	
    public function getPersonalExp($id) 
	{
        $row = $this->select(array('id' => (int) $id))->current();
        if (!$row)
            return false;

        $PEData = new Entity\PersonalExp(array(
					'id' => $row->id,
					'USER_ID' => $row->USER_ID,
                    'DATE_FROM' => $row->DATE_FROM,
                    'DATE_TO' => $row->DATE_TO,
                    'DESCRIPTION' => $row->DESCRIPTION,
                    'TECHNOLOGIES' => $row->TECHNOLOGIES,
                ));
        return $PEData;
	}
	
    public function savePersonalExp(Entity\PersonalExp $PEData) 
	{
        $data = array(
			'id' => $PEData->getId(),
			'USER_ID' => $PEData->getUserId(),
			'DATE_FROM' => $PEData->getDateFrom(),
			'DATE_TO' => $PEData->getDateTo(),
			'DESCRIPTION' => $PEData->getDescription(),
			'TECHNOLOGIES' => $PEData->getTechnologies(),
        );

        $id = (int) $PEData->getId();

        if ($id == 0) {
            if (!$this->insert($data))
                return false;
            return $this->getLastInsertValue();
        }
        elseif ($this->getPersonalExp($id)) {
            if (!$this->update($data, array('id' => $id)))
                return false;
            return $id;
        }
        else
            return false;
	}
	
    public function removePersonalExp($id) 
	{
        return $this->delete(array('id' => (int) $id));
	}
}

?>