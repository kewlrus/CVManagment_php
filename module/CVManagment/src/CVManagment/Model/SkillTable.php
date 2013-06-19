<?php
namespace CVManagment\Model;

use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Expression;

class SkillTable extends AbstractTableGateway {

    protected $table = 'PersonalSkills';

    public function __construct(Adapter $adapter) {
        $this->adapter = $adapter;
    }
	
    public function fetchAll($user_id = 1) {
		$where_clause = ' USER_ID = ' . $user_id;
		$resultSet = $this->select($where_clause, function (Select $select) {
		});	
        $entities = array();
        foreach ($resultSet as $row) {
            $entity = new Entity\Skill();
            $entity->setId($row->ID)
                    ->setUserId($row->USER_ID)
                    ->setDescription($row->DESCRIPTION);
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
	
    public function getSkill($id) 
	{
        $row = $this->select(array('id' => (int) $id))->current();
		
        if (!$row)
            return false;

        $SkillData = new Entity\Skill(array(
					'ID' => $row->ID,
                    'USER_ID' => $row->USER_ID,
					'DESCRIPTION' => $row->DESCRIPTION,
                ));
        return $SkillData;
	}
	
    public function saveSkill(Entity\Skill $Skill) 
	{
       $data = array(
			'ID' => $Skill->getId(),
			'USER_ID' => $Skill->getUserId(),
			'DESCRIPTION' => $Skill->getDescription(),
        );

        $id = (int) $Skill->getId();

        if ($id == 0) {
            if (!$this->insert($data))
                return false;
            return $this->getLastInsertValue();
        }
        elseif ($this->getSkill($id)) {
            if (!$this->update($data, array('id' => $id)))
                return false;
            return $id;
        }
        else
            return false;
	}
	
    public function removeSkill($id) 
	{
        return $this->delete(array('id' => (int) $id));
	}
}

?>