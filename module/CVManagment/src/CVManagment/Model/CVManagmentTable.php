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
	
    public function fetchAll() {
		$SelectDistEmp = new Select;
		$sql = new Sql($this->adapter);
		
        $SelectDistEmp->from($this->table);
		$SelectDistEmp->order('DATE_FROM DESC');
		$SelectDistEmp->columns(array(
			'eid' => new Expression('DISTINCT EmployerID')
		));
			  
		$statement = $sql->prepareStatementForSqlObject($SelectDistEmp);
		$resultSetDistinct = $statement->execute();

		$entities = array();		
		foreach ($resultSetDistinct as $rowDistinct) 
		{				
			$where_clause = 'EmployerID = ' . $rowDistinct['eid'];

			$resultSet = $this->select($where_clause, function (Select $select) {
						$select->order('DATE_FROM DESC');	
			});	
			
			$SelectEmployer = new Select;
		
			$SelectEmployer->from('Employers');
			$SelectEmployer->where('id = ' . $rowDistinct['eid']);
			
			$statementEmployer = $sql->prepareStatementForSqlObject($SelectEmployer);
			$resultEmployer = $statementEmployer->execute();
			
			$EMP_NAME = '';
			foreach ($resultEmployer as $rowEmployer) 
			{				
				$EMP_NAME = $rowEmployer['NAME'];
			}
			
			$EMPLOYER_ARR = array();
			$EMPLOYER_ARR['ID'] = $rowDistinct['eid'];
			$EMPLOYER_ARR['NAME'] = $EMP_NAME;
			
			$entities['EMPLOYERS'][] = $EMPLOYER_ARR;
				
			foreach ($resultSet as $row) {
				$entity = new Entity\CVManagment();
				$entity->setId($row->id)
						->setEmployerId($row->EmployerID)
						->setDateFrom($row->DATE_FROM)
						->setDateTo($row->DATE_TO)
						->setDescription($row->DESCRIPTION)
						->setTechnologies($row->TECHNOLOGIES)
						->setEmployerName($EMP_NAME);
						
				$entities['CVDATA'][$rowDistinct['eid']][] = $entity;
			}
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