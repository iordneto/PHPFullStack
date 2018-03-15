<?php

namespace App\v1\DAO;

use App\v1\DAO\AbstractDAO;
use App\Utils\StatusAnuncio;
use App\Utils\AnuncioConfig;

class AnuncianteDAO extends AbstractDAO{

	public function __construct($entityManager) {
		parent::__construct('App\Models\Entity\Anunciante', $entityManager);
	}

	public function listarDividaAnunciantes() {
		$anunciantes = $this->entityManager->getRepository('App\Models\Entity\Anunciante')->findAll();

		$listaDivida = array();
		foreach($anunciantes as $anunciante) {
			$anunciosAtivos = $anunciante->getAnuncios()->filter(
				function($anuncio) {
					return in_array($anuncio->getStatus(), array(StatusAnuncio::ATIVO));
				 }
			);
			
			$listaDivida[] = [
				"advertiser" => $anunciante->getNome(),
				"value" => $anunciosAtivos->count() * AnuncioConfig::PRECO_FIXO];
		}

		return $listaDivida;
	}
}