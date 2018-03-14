<?php

namespace App\v1\Controllers;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

use App\Models\Entity\Anunciante;
use  App\v1\DAO\AnuncianteDAO;

class AnuncianteController {
    
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
     * Undocumented function
     * @param [object] $container
     */
    public function __construct($container) {
        $this->container = $container;
        $this->persistencia = new AnuncianteDAO($this->container->get('em'));
    }

     /**
     * Listagem de Anunciantes
     * @param [type] $request
     * @param [type] $response
     * @param [type] $args
     * @return Response
     */
    public function listarAnunciantes($request, $response, $args) {
        $anunciantes = $this->persistencia->buscarTodos();
        
        $return = $response
            ->withJson($anunciantes, 200)
            ->withHeader('Content-type', 'application/json');
        return $return;      
    }

    /**
     * Inserção de Anunciante
     * @param [type] $request
     * @param [type] $response
     * @param [type] $args
     * @return Response
     */
    public function inserirAnunciante($request, $response, $args) {
        $anuncianteJSON = $request->getBody();

        $anunciante = Anunciante::construct($anuncianteJSON);
        
        $logger = $this->container->get('logger');
        $logger->info('Anunciante Criado!', $anunciante->getValues());
        
        $this->persistencia->inserir($anunciante);

        $return = $response
            ->withJson($anunciante->toArray(), 201)
            ->withHeader('Content-type', 'application/json');
        return $return;
    }

     /**
     * Exibe as informações de um Anunciante
     * @param [type] $request
     * @param [type] $response
     * @param [type] $args
     * @return Response
     */
    public function visualizarAnunciante($request, $response, $args) {
        $id = (int) $args['id'];

        $anunciante =$this->persistencia->visualizarPorId($id);
        $return = $response
            ->withJson($anunciante, 200)
            ->withHeader('Content-type', 'application/json');
        return $return;        
    }

    /**
     * Atualiza um Anunciante
     * @param [type] $request
     * @param [type] $response
     * @param [type] $args
     * @return Response
     */
    public function atualizarAnunciante($request, $response, $args) {
        $id = (int) $args['id'];
        $anuncianteJSON = $request->getBody();

        $identificou = $this->persistencia->identifica($id);
        
        if (!$identificou) {
            $logger = $this->container->get('logger');
            $logger->warning("Anunciante {$id} Not Found");
            throw new \Exception("Anunciante not Found", 404);
        }  
    
        $anunciante = Anunciante::construct($anuncianteJSON)->setId($id);

        $this->persistencia->atualizar($anunciante);
        
        $return = $response
            ->withJson($anunciante->toArray(), 200)
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
    public function deletarAnunciante($request, $response, $args) {
        $id = (int) $args['id'];

        $identificou = $this->persistencia->identifica($id); 

        if (!$identificou) {
            $logger = $this->container->get('logger');
            $logger->warning("Anunciante {$id} Not Found");
            throw new \Exception("Anunciante not Found", 404);
        }  
        
        $this->persistencia->deletar($id); 

        $return = $response
            ->withJson(['msg' => "Deletando o anunciante {$id}"], 204)
            ->withHeader('Content-type', 'application/json');
        return $return;    
    }    
}