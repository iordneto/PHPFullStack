<?php

namespace App\Models\Entity;

use JMS\Serializer\Annotation\Exclude;

/**
 * @Entity @Table(name="anuncios")
 **/
class Anuncio {

     /**
     * @var int
     * @Id @Column(type="integer") 
     * @GeneratedValue
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
     * @ManyToOne(targetEntity="Anunciante", inversedBy="anuncio", fetch="LAZY")
     * @JoinColumn(name="anunciante_id", referencedColumnName="id")
     */
    private $anunciante;

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
    * @return App\Models\Entity\Anuncio
    */
   public function setStatus($status)
   {
       if (!in_array($status, array(StatusAnuncio::ATIVO, StatusAnuncio::INATIVO))) {
           throw new \InvalidArgumentException("Status inválido!");
       }
       $this->status = $status;

       return $this;
   }

     /**
     * @return App\Models\Entity\Anuncio
     */
    public function getValues() {
        return get_object_vars($this);
    }

     /**
     * @return App\Models\Entity\Anuncio
     */
    public static function criar($descricao){
        $anuncio = new Anuncio();
        $anuncio->setDescricao($descricao);
        return $anuncio;
    }
}