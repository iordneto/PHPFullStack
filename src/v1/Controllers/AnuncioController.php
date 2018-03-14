<?php

namespace App\v1\Controllers;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

use App\Models\Entity\Anunciante;
use App\Models\Entity\Anuncio;

class AnuncioController {
     /**
     * Container Class
     * @var [object]
     */
    private $container;

    /**
     * Undocumented function
     * @param [object] $container
     */
    public function __construct($container) {
        $this->container = $container;
    }

     /**
     * Listagem de Anunciantes
     * @param [type] $request
     * @param [type] $response
     * @param [type] $args
     * @return Response
     */
    public function listarAnuncios($request, $response, $args) {
        $idAnunciante = (int) $args['idAnunciante'];

        $entityManager = $this->container->get('em');
        $anuncioRepository = $entityManager->getRepository('App\Models\Entity\Anuncio');
        $anuncios = $anuncioRepository->findBy(array('anunciante' => $idAnunciante));
        
        $return = $response
            ->withJson($anuncios, 200)
            ->withHeader('Content-type', 'application/json');
        return $return;    
    }

    /**
     * Inserção de Anuncio
     * @param [type] $request
     * @param [type] $response
     * @param [type] $args
     * @return Response
     */
    public function inserirAnuncio($request, $response, $args) {
        $idAnunciante = (int) $args['idAnunciante'];
        $params = (object) $request->getParams();

        $entityManager = $this->container->get('em');
        $anunciantesRepository = $entityManager->getRepository('App\Models\Entity\Anunciante');
        $anunciante = $anunciantesRepository->find($idAnunciante);

        if($anunciante) {
            $anunciante->adicionaAnuncio($params->descricao);
            
            $entityManager->persist($anunciante);
            $entityManager->flush(); 

            $return = $response
                ->withJson($anunciante->getUltimoAnuncio(), 200)
                ->withHeader('Content-type', 'application/json');

            return $return;
        } else {
            return 'erro';
        }        
    }
}