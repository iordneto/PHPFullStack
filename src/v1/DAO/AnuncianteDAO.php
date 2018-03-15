<?php

namespace App\v1\DAO;

use App\v1\DAO\AbstractDAO;

class AnuncianteDAO extends AbstractDAO{

	public function __construct($entityManager) {
		parent::__construct('App\Models\Entity\Anunciante', $entityManager);
	}

	public function listarDividaAnunciantes() {
		$collection = $this->entityManager->getRepository('App\Models\Entity\Anunciante')->findAll();

		$data = array();
		foreach($collection as $anunciante) {
			$anunciosAtivos = $anunciante->getAnuncios()->filter(
				function($anuncio) {
					return in_array($anuncio->getStatus(), array('ATIVO'));
				 }
			);
			$data[] = ["advertiser" => $anunciante->getNome(),
						 "value" => $anunciosAtivos->count() * 10];
		}

		return $data;
	}
}