<?php
namespace CVManagment\Model\Entity;

class Skill {
    protected $_id;
    protected $_userid;
    protected $_description;
	
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
        return $this->_userid;
    }

    public function setUserId($id) {
        $this->_userid = $id;
        return $this;
    }
	
    public function getDescription() {
        return $this->_description;
    }

    public function setDescription($text) {
        $this->_description = $text;
        return $this;
    }

}

?>