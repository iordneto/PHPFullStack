<?php

namespace App\v1\DAO;

use App\v1\DAO\AbstractDAO;

class AnuncianteDAO extends AbstractDAO{

	public function __construct($entityManager) {
		parent::__construct('App\Models\Entity\Anunciante', $entityManager);
	}

	public function deletar($obj){
		if(!is_null(obj)){
			$this->entityManager->remove($obj);
			$this->entityManager->flush();
		}
	}
}