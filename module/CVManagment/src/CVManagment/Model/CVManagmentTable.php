<?php
namespace CVManagment\Model;

use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Sql\Select;

class CVManagmentTable extends AbstractTableGateway {

    protected $table = 'cvdata';

    public function __construct(Adapter $adapter) {
        $this->adapter = $adapter;
    }
	
    public function fetchAll() {
        $resultSet = $this->select(function (Select $select) {
                    $select->order('DATE_FROM ASC');
                });
        $entities = array();
        foreach ($resultSet as $row) {
            $entity = new Entity\CVManagment();
            $entity->setId($row->id)
                    ->setEmployerId($row->EmployerID)
                    ->setDateFrom($row->DATE_FROM)
                    ->setDateTo($row->DATE_TO)
                    ->setDescription($row->DESCRIPTION)
                    ->setTechnologies($row->TECHNOLOGIES);
					
            $entities[] = $entity;
        }
        return $entities;
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