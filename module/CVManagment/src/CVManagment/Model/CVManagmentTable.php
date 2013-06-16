<?php
namespace CVManagment\Model;

use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Expression;

class CVManagmentTable extends AbstractTableGateway {

    protected $table = 'cvdata';

    public function __construct(Adapter $adapter) {
        $this->adapter = $adapter;
    }
	
    public function fetchAll($user_id = 1) {
		//echo 'asdasd222fetchAll: '.$user_id;
		$SelectDistEmp = new Select;
		$sql = new Sql($this->adapter);
		
        $SelectDistEmp->from('Employers');
		
		$statement = $sql->prepareStatementForSqlObject($SelectDistEmp);
		$resultSetDistinct = $statement->execute();

		$EMPLOYER_ARR = array();
		$EMPLOYERS_ARR = array();
		$entities = array();		
		
		foreach ($resultSetDistinct as $rowDistinct) 
		{		
			$EMPLOYER_ARR['ID'] = $rowDistinct['id'];
			$EMPLOYER_ARR['NAME'] = $rowDistinct['NAME'];
			$EMPLOYERS_ARR[] = $EMPLOYER_ARR;
		}
		
		$entities['EMPLOYERS'] = $EMPLOYERS_ARR;			
		
		$where_clause = ' user_id = ' . $user_id;
		$resultSet = $this->select($where_clause, function (Select $select) {
			$select->order('DATE_FROM DESC');	
		});	
		
		foreach ($resultSet as $row) 
		{	
			$entities['EMPLOYERS'] = $EMPLOYERS_ARR;
		
			$entity = new Entity\CVManagment();
			$entity->setId($row->id)
					->setEmployerId($row->EmployerID)
					->setDateFrom($row->DATE_FROM)
					->setDateTo($row->DATE_TO)
					->setDescription($row->DESCRIPTION)
					->setTechnologies($row->TECHNOLOGIES);
					//->setEmployerName($EMP_NAME);
					
			$entities['CVDATA'][$row->EmployerID][] = $entity;
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
	
    public function getCVData($id) 
	{
        $row = $this->select(array('id' => (int) $id))->current();
        if (!$row)
            return false;

        $CVData = new Entity\CVManagment(array(
					'id' => $row->id,
                    'EmployerId' => $row->EmployerID,
					'user_id' => $row->user_id,
                    'DATE_FROM' => $row->DATE_FROM,
                    'DATE_TO' => $row->DATE_TO,
                    'DESCRIPTION' => $row->DESCRIPTION,
                    'TECHNOLOGIES' => $row->TECHNOLOGIES,
                ));
        return $CVData;
	}
	
    public function saveCVData(Entity\CVManagment $CVData) 
	{
        $data = array(
			'id' => $CVData->getId(),
			'EmployerId' => $CVData->getEmployerId(),
			'user_id' => $CVData->getUserId(),
			'DATE_FROM' => $CVData->getDateFrom(),
			'DATE_TO' => $CVData->getDateTo(),
			'DESCRIPTION' => $CVData->getDescription(),
			'TECHNOLOGIES' => $CVData->getTechnologies(),
        );

        $id = (int) $CVData->getId();

        if ($id == 0) {
            if (!$this->insert($data))
                return false;
            return $this->getLastInsertValue();
        }
        elseif ($this->getCVData($id)) {
            if (!$this->update($data, array('id' => $id)))
                return false;
            return $id;
        }
        else
            return false;
	}
	
    public function removeCVData($id) 
	{
        return $this->delete(array('id' => (int) $id));
	}
}

?>