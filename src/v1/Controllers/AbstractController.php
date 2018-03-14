<?php

namespace App\v1\Controllers;

use LojaAgua\persistencia\AbstractDAO;
use Exception;

abstract class AbstractController {
	
	private $abstractDAO;
	
	public function __construct($abstractDAO) {
        if(!$abstractDAO instanceof AbstractDAO){
            throw new Exception("Erro!");
        }
        $this->abstractDAO = $abstractDAO;
	}
	
	public function getAbstractDAO() {
		return $this->abstractDAO;
	}
	public function setAbstractDAO($abstractDAO) {
		$this->abstractDAO = $abstractDAO;
	}
	
	public function get($id) {
		$data = [];

		if ($id === null) {
			$result = $this->getAbstractDAO()->findAll();

			foreach($result as $obj) {
				$data [] = $obj->toArray();
			}
		} else {
			$obj = $this->getAbstractDAO()->findById($id);
			
			if ($obj != null) {
				$data = $obj->toArray();
			}
		}

		return $data;
	}

    abstract public function insert($json);
	
	abstract public function update($id, $json);
	
	abstract public function delete($id);
}