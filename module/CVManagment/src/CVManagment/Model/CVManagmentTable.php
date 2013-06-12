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
                   // $select->order('created ASC');
                });
        $entities = array();
        foreach ($resultSet as $row) {
            $entity = new Entity\CVManagment();
            $entity->setId($row->id)
                    ->setEmployerId($row->EmployerID);
                   // ->setCreated($row->created);
            $entities[] = $entity;
        }
        return $entities;
    }
}

?>