<?php

namespace App\v1\DAO;

abstract class AbstractDAO {

	public $entityManager;
	private $entityPath;

	public function __construct($entityPath, $entityManager) {
			$this->entityPath = $entityPath;
			$this->entityManager = $entityManager;
	}
	
	public function inserir($obj){
		$this->entityManager->persist($obj);
		$this->entityManager->flush();
	}

	public function atualizar($obj){
		$this->entityManager->merge($obj);
		$this->entityManager->flush();
	}

	public function deletar($id){
		$obj = $this->entityManager ->find($this->entityPath, $id);

		if(!is_null(obj)){
			$this->entityManager->remove($obj);
			$this->entityManager->flush();
		}
	}

	public function buscarPorId($id){
		$obj = $this->entityManager ->find($this->entityPath, $id);

		return is_null($obj) ? [] : $obj;
	}

	public function visualizarPorId($id){
		$obj = $this->entityManager ->find($this->entityPath, $id);

		return is_null($obj) ? [] : $obj->toArray();
	}

	public function identifica($id){
		$obj = $this->entityManager ->find($this->entityPath, $id);

		return !is_null($obj);
	}

	public function buscarTodos(){
		$collection = $this->entityManager->getRepository($this->entityPath)->findAll();

		$data = array();
		foreach($collection as $obj) {
			$data [] = $obj->toArray();
		}

		return $data;
	}

	public function buscarPor($array){
		$collection = $this->entityManager->getRepository($this->entityPath)->findBy($array);

		$data = array();
		foreach($collection as $obj) {
			$data [] = $obj->toArray();
		}

		return $data;
	}

	public function buscarUmPor($array){
		$obj = $this->entityManager->getRepository($this->entityPath)->findOneBy($array);

		return is_null($obj) ? [] : $obj;
	}

	public function visualizarUmPor($array){
		$obj = $this->entityManager->getRepository($this->entityPath)->findOneBy($array);

		return is_null($obj) ? [] : $obj->toArray();
	}
}
