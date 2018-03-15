<?php

namespace App\Models\Entity;

use JMS\Serializer\Annotation\Exclude;
use App\Utils\StatusAnuncio;

/**
 * @Entity @Table(name="anuncios")
 **/
class Anuncio {

    /**
    *	@var integer 
    *   @Id
    *   @Column(name="id", type="integer")
    *   @GeneratedValue(strategy="AUTO")
    */
    private $id;

    /**
     * @var string
     * @Column(type="string") 
     */
    private $descricao;

    /**
     * @var date
     * @Column(type="date") 
     */
    private $dataPublicacao;

     /**
     * @var string
     * @Column(type="string") 
     */
    private $status;
    
    /**
     * Muitos Anúncios tem um Anunciante.
     * @ManyToOne(targetEntity="Anunciante", inversedBy="anuncios")
     * @JoinColumn(name="anunciante_id", referencedColumnName="id")
     */
    private $anunciante;

    public function __construct($descricao = "") {
        $this->descricao = $descricao;
        $this->dataPublicacao = new \DateTime("now");
        $this->status = StatusAnuncio::ATIVO;
    }

    public function construct($anuncioJSON){
        $anuncioArray = json_decode($anuncioJSON, true);
        $anuncio = (new Anuncio())->setDescricao($anuncioArray['descricao']);

        
        return $anuncio;
    }

     /**
     * @return int id
     */
    public function getId(){
        return $this->id;
    }

     /**
     * @return string descricao
     */
    public function getDescricao(){
        return $this->descricao;
    }

     /**
     * @return App\Models\Entity\Anuncio
     */
    public function setDescricao($descricao){
        if (!$descricao && !is_string($descricao)) {
            throw new \InvalidArgumentException("Descrição deve ser informada!", 400);
        }
        $this->descricao = $descricao;

        return $this;
    }

    /**
    * @return App\Models\Entity\Anunciante
    */
   public function getAnunciante(){
       return $this->anunciante;
   }

    /**
    * @return App\Models\Entity\Anuncio
    */
   public function setAnunciante($anunciante){
       $this->anunciante = $anunciante;
       return $this;
   }

    /**
    * @return date dataPublicacao
    */
    public function getDataPublicacao(){
        return $this->dataPublicacao;
    }
 
     /**
     * @return App\Models\Entity\Anuncio
     */
    public function setDataPublicacao($dataPublicacao){
        $this->dataPublicacao = $dataPublicacao;
        
        return $this;
    }

    /**
    * @return string status
    */
    public function getStatus(){
        return $this->status;
    }

   /**
    * @return App\Models\Entity\Anuncio
    */
    private function setStatus($status) {
        if (!in_array($status, array(StatusAnuncio::ATIVO, StatusAnuncio::INATIVO))) {
            throw new \InvalidArgumentException("Status inválido!");
        }

        $this->status = $status;
        return $this;
    }

    public function ativar() {
       $this->setStatus(StatusAnuncio::ATIVO);
    }   

    public function desativar() {
       $this->setStatus(StatusAnuncio::INATIVO);
    }

    public function atualizaAtributos($anuncioJSON) {
        $anuncioArray = json_decode($anuncioJSON, true);
        foreach ($anuncioArray as $key => $value){
            if (property_exists( $this , $key )){
                $this->{$key} = $value;
            }
        }   
    }
   
    /**
     * @return App\Models\Entity\Anunciante
     */
    public function getValues() {
        return get_object_vars($this);
    }
    
    public function toArray() {
        return [
            "id" => $this->getId(),
            "descricao" => $this->getDescricao(),
            "dataPublicacao" => $this->getDataPublicacao()->format('d/m/Y'),
            "status" => $this->getStatus()];
    }
}