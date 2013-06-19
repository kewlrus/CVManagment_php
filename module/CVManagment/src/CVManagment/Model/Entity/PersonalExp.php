<?php
namespace CVManagment\Model\Entity;

class PersonalExp {

    protected $_id;
    protected $_user_id;
    protected $_date_from;
    protected $_date_to;
    protected $_description;
    protected $_technologies;
	
    public function __construct(array $options = null) {
        if (is_array($options)) {
            $this->setOptions($options);
        }
    }

    public function __set($name, $value) {
        $method = 'set' . $name;
        if (!method_exists($this, $method)) {
            throw new Exception('Invalid Method');
        }
        $this->$method($value);
    }

    public function __get($name) {
        $method = 'get' . $name;
        if (!method_exists($this, $method)) {
            throw new Exception('Invalid Method');
        }
        return $this->$method();
    }

    public function setOptions(array $options) {
        $methods = get_class_methods($this);
        foreach ($options as $key => $value) {
            $method = 'set' . ucfirst($key);
            if (in_array($method, $methods)) {
                $this->$method($value);
            }
        }
        return $this;
    }

    public function getId() {
        return $this->_id;
    }

    public function setId($id) {
        $this->_id = $id;
        return $this;
    }
	
    public function getUserId() {
        return $this->_user_id;
    }

    public function setUserId($id) {
        $this->_user_id = $id;
        return $this;
    }
	
    public function getDateFrom() {
        return $this->_date_from;
    }

    public function setDateFrom($df) {
        $this->_date_from = $df;
        return $this;
    }
	
    public function getDateTo() {
        return $this->_date_to;
    }

    public function setDateTo($dt) {
        $this->_date_to = $dt;
        return $this;
    }
	
    public function getDescription() {
        return $this->_description;
    }

    public function setDescription($descr) {
        $this->_description = $descr;
        return $this;
    }
	
    public function getTechnologies() {
        return $this->_technologies;
    }

    public function setTechnologies($tech) {
        $this->_technologies = $tech;
        return $this;
    }
}

?>