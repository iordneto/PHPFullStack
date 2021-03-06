<?php

namespace App\v1\Controllers;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

use App\Models\Entity\Anuncio;
use App\Models\Entity\Anunciante;

use  App\v1\DAO\AnuncioDAO;
use  App\v1\DAO\AnuncianteDAO;

use App\Utils\StatusAnuncio;

class AnuncioController {
    
     /**
     * Container Class
     * @var [object]
     */
    private $container;

    /**
     * @var [object]
     */
    private $persistencia;

    /**
     * @var [object]
     */
    private $persistenciaAnunciante;

    /**
     * Undocumented function
     * @param [object] $container
     */
    public function __construct($container) {
        $this->container = $container;
        $this->persistencia = new AnuncioDAO($this->container->get('em'));
        $this->persistenciaAnunciante = new AnuncianteDAO($this->container->get('em'));
    }

     /**
     * Listagem de Anuncios
     * @param [type] $request
     * @param [type] $response
     * @param [type] $args
     * @return Response
     */
    public function listarTodosAnuncios($request, $response, $args) {
        $anuncios = $this->persistencia->buscarTodos();
        
        $return = $response
            ->withJson($anuncios, 200)
            ->withHeader('Content-type', 'application/json');
        return $return;      
    }

    /**
     * Listagem de Anuncios de um anunciante
     * @param [type] $request
     * @param [type] $response
     * @param [type] $args
     * @return Response
     */
    public function listarAnuncios($request, $response, $args) {
        $idAnunciante = (int) $args['idAnunciante'];
        
        $identificou = $this->persistenciaAnunciante->identifica($idAnunciante);

        if (!$identificou) {
            $logger = $this->container->get('logger');
            $logger->warning("Anunciante {$idAnunciante} não encontrado");
            throw new \Exception("Anunciante {$idAnunciante} não encontrado", 404);
        }

        $anuncios = $this->persistencia->buscarPor(array('anunciante' => $idAnunciante));

        if (!$anuncios) {
            $logger = $this->container->get('logger');
            $logger->warning("Nenhum anuncio encontrado para o Anunciante {$idAnunciante}");
            throw new \Exception("Nenhum anuncio encontrado para o Anunciante {$idAnunciante}", 404);
        }
        
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

        $anunciante =$this->persistenciaAnunciante->buscarPorId($idAnunciante);
    
        if(!$anunciante){
            $logger = $this->container->get('logger');
            $logger->warning("Anunciante {$idAnunciante} não encontrado");
            throw new \Exception("Anunciante {$idAnunciante} não encontrado", 404);
        }

        $anuncioJSON = $request->getBody();

        $anuncio = Anuncio::construct($anuncioJSON);
        $anunciante->getAnuncios()->add($anuncio);
        
        $anuncio->setAnunciante($anunciante);
        
        $this->persistencia->inserir($anuncio);

        $return = $response
            ->withJson($anuncio->toArray(), 201)
            ->withHeader('Content-type', 'application/json');
        return $return;
    }

     /**
     * Exibe as informações de um Anuncio
     * @param [type] $request
     * @param [type] $response
     * @param [type] $args
     * @return Response
     */
    public function visualizarAnuncio($request, $response, $args) {
        $idAnunciante = (int) $args['idAnunciante'];
        $id = (int) $args['id'];

        $identificou = $this->persistenciaAnunciante->identifica($idAnunciante);

        if (!$identificou) {
            $logger = $this->container->get('logger');
            $logger->warning("Anunciante {$idAnunciante} não encontrado");
            throw new \Exception("Anunciante {$idAnunciante} não encontrado", 404);
        }

        $anuncio = $this->persistencia->visualizarUmPor(
            array(
                'anunciante' => $idAnunciante,
                'id' => $id));
        
        if (!$anuncio) {
            $logger = $this->container->get('logger');
            $logger->warning("Anuncio {$id} não encontrado para o anunciante {$idAnunciante}");
            throw new \Exception("Anuncio {$id} não encontrado  não encontrado para o anunciante {$idAnunciante}", 404);
        } 
       
        $return = $response
            ->withJson($anuncio, 200)
            ->withHeader('Content-type', 'application/json');
        return $return;        
    }

    /**
     * Atualiza um Anuncio
     * @param [type] $request
     * @param [type] $response
     * @param [type] $args
     * @return Response
     */
    public function atualizarAnuncio($request, $response, $args) {
        $idAnunciante = (int) $args['idAnunciante'];
        $id = (int) $args['id'];

        $anuncioJSON = $request->getBody();

        $anunciante =$this->persistenciaAnunciante->buscarPorId($idAnunciante);
    
        if(!$anunciante){
            $logger = $this->container->get('logger');
            $logger->warning("Anunciante {$idAnunciante} não encontrado");
            throw new \Exception("Anunciante {$idAnunciante} não encontrado", 404);
        }

        $anuncio = $this->persistencia->buscarUmPor(
            array(
                'anunciante' => $idAnunciante,
                'id' => $id));
        
        if(!$anuncio){
            $logger = $this->container->get('logger');
            $logger->warning("Anuncio {$id} não encontrado para o anunciante {$idAnunciante}");
            throw new \Exception("Anuncio {$id} não encontrado  não encontrado para o anunciante {$idAnunciante}", 404);
        }
        
        $anuncio->atualizaAtributos($anuncioJSON);

        $anunciante->getAnuncios()->set($id, $anuncio);        
        
        $this->persistencia->atualizar($anuncio);

        $return = $response
            ->withJson($anuncio->toArray(), 200)
            ->withHeader('Content-type', 'application/json');
        return $return;    
    }

     /**
     * Deleta um Anunciante
     * @param [type] $request
     * @param [type] $response
     * @param [type] $args
     * @return Response
     */
    public function deletarAnuncio($request, $response, $args) {
        $idAnunciante = (int) $args['idAnunciante'];
        $id = (int) $args['id'];

        $anunciante =$this->persistenciaAnunciante->buscarPorId($idAnunciante);
    
        if(!$anunciante){
            $logger = $this->container->get('logger');
            $logger->warning("Anunciante {$idAnunciante} não encontrado");
            throw new \Exception("Anunciante {$idAnunciante} não encontrado", 404);
        }

        $anuncio = $this->persistencia->buscarUmPor(
            array(
                'anunciante' => $idAnunciante,
                'id' => $id));
        
        if(!$anuncio){
            $logger = $this->container->get('logger');
            $logger->warning("Anuncio {$id} não encontrado para o anunciante {$idAnunciante}");
            throw new \Exception("Anuncio {$id} não encontrado  não encontrado para o anunciante {$idAnunciante}", 404);
        }

        $anunciante->getAnuncios()->remove($id);        
        $anuncio->setAnunciante(null);
        
        $this->persistencia->deletar($anuncio);

        $return = $response
            ->withJson(['msg' => "Deletando o anuncio {$id}"], 204)
            ->withHeader('Content-type', 'application/json');
        return $return;    
    }
    
    /**
     * Ativar um Anuncio
     * @param [type] $request
     * @param [type] $response
     * @param [type] $args
     * @return Response
     */
    public function ativarAnuncio($request, $response, $args) {
        $idAnunciante = (int) $args['idAnunciante'];
        $id = (int) $args['id'];

        $anuncioJSON = $request->getBody();

        $anunciante =$this->persistenciaAnunciante->buscarPorId($idAnunciante);
    
        if(!$anunciante){
            $logger = $this->container->get('logger');
            $logger->warning("Anunciante {$idAnunciante} não encontrado");
            throw new \Exception("Anunciante {$idAnunciante} não encontrado", 404);
        }

        $anuncio = $this->persistencia->buscarUmPor(
            array(
                'anunciante' => $idAnunciante,
                'id' => $id));
        
        if(!$anuncio){
            $logger = $this->container->get('logger');
            $logger->warning("Anuncio {$id} não encontrado para o anunciante {$idAnunciante}");
            throw new \Exception("Anuncio {$id} não encontrado  não encontrado para o anunciante {$idAnunciante}", 404);
        }
        
        $anuncio->ativar();

        $anunciante->getAnuncios()->set($id, $anuncio);        
        
        $this->persistencia->atualizar($anuncio);

        $return = $response
            ->withJson($anuncio->toArray(), 200)
            ->withHeader('Content-type', 'application/json');
        return $return;    
    }
      
    /**
     * Desativar um Anuncio
     * @param [type] $request
     * @param [type] $response
     * @param [type] $args
     * @return Response
     */
    public function desativarAnuncio($request, $response, $args) {
        $idAnunciante = (int) $args['idAnunciante'];
        $id = (int) $args['id'];

        $anuncioJSON = $request->getBody();

        $anunciante =$this->persistenciaAnunciante->buscarPorId($idAnunciante);
    
        if(!$anunciante){
            $logger = $this->container->get('logger');
            $logger->warning("Anunciante {$idAnunciante} não encontrado");
            throw new \Exception("Anunciante {$idAnunciante} não encontrado", 404);
        }

        $anuncio = $this->persistencia->buscarUmPor(
            array(
                'anunciante' => $idAnunciante,
                'id' => $id));
        
        if(!$anuncio){
            $logger = $this->container->get('logger');
            $logger->warning("Anuncio {$id} não encontrado para o anunciante {$idAnunciante}");
            throw new \Exception("Anuncio {$id} não encontrado  não encontrado para o anunciante {$idAnunciante}", 404);
        }
        
        $anuncio->desativar();

        $anunciante->getAnuncios()->set($id, $anuncio);        
        
        $this->persistencia->atualizar($anuncio);

        $return = $response
            ->withJson($anuncio->toArray(), 200)
            ->withHeader('Content-type', 'application/json');
        return $return;    
    }
}