<?php

namespace App\v1\DAO;

use App\v1\DAO\AbstractDAO;

class AnuncioDAO extends AbstractDAO{

	public function __construct($entityManager) {
		parent::__construct('App\Models\Entity\Anuncio', $entityManager);
	}
}