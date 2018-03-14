<?php

namespace App\v1\DAO;


class AnuncianteDAO extends AbstractDAO{

public function __construct($entityManager) {
		parent::__construct('App\Models\Entity\Anunciante', $entityManager);
	}
}