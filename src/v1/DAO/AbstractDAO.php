<?php

namespace App\v1\DAO;

abstract class AbstractDAO {

	public $entityManager;
	private $entityPath;

	public function __construct($entityPath, $entityManager) {
			$this->entityPath = $entityPath;
			$this->entityManager = $entityManager;
	}
	
	public function insert($obj){
		$this->entityManager->persist($obj);
		$this->entityManager->flush();
	}

	public function update($obj){
		$this->entityManager->merge($obj);
		$this->entityManager->flush();
	}

	public function delete($obj){
		$this->entityManager->remove($obj);
		$this->entityManager->flush();
	}

	public function findById($id){
		return $this->entityManager ->find($this->entityPath, $id);
	}

	public function findAll(){
		$collection = $this->entityManager->getRepository($this->entityPath)->findAll();

		$data = array();
		foreach($collection as $obj) {
			$data [] = $obj;
		}

		return $data;
	}
}
