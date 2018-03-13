<?php

namespace App\Models\Entity;

/**
 * @Entity @Table(name="anuncios")
 **/
class Anuncio {

     /**
     * @var int
     * @Id @Column(type="integer") 
     * @GeneratedValue
     */
    protected $id;

    /**
     * @var string
     * @Column(type="string") 
     */
    protected $descricao;

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
        $this->descricao = $descrico;

        return $this;
    }

     /**
     * @return App\Models\Entity\Anuncio
     */
    public function getValues() {
        return get_object_vars($this);
    }
}