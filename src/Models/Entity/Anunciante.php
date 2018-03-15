<?php

namespace App\Models\Entity;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity @Table(name="anunciantes")
 **/
class Anunciante {

    /**
    *	@var integer 
    *   @Id
    *   @Column(name="id", type="integer")
    *   @GeneratedValue(strategy="AUTO")
    */
    private $id;

    /**
     * @var string
     * @Column(type="string", length=255) 
     */
    private $nome;

    /**
     * @var string
     * @Column(type="string", length=255) 
     */
    private $endereco;

    /**
     * @var string
     * @Column(type="string", length=255) 
     */
    private $telefone;

     /**
     * Um anunciante tem muitos Anuncios.
     * @OneToMany(targetEntity="Anuncio", mappedBy="anunciante", cascade={"persist", "remove"})
     */
    private $anuncios;

    public function __construct($id = 0, $nome = "", $endereco = "", $telefone = "") {
        $this->id = $id;
        $this->nome = $nome;
        $this->endereco = $endereco;
        $this->telefone = $telefone;
        $this->anuncios = new ArrayCollection();
    }

    public function construct($anuncianteJSON){
        $anuncianteArray = json_decode($anuncianteJSON, true);
        $anunciante = new Anunciante();
        $anunciante
            ->setNome($anuncianteArray['nome'])
            ->setEndereco($anuncianteArray['endereco'])
            ->setTelefone($anuncianteArray['telefone']);
        return $anunciante;
    }

     /**
     * @return int id
     */
    public function getId(){
        return $this->id;
    }

     /**
     * @return App\Models\Entity\Anunciante
     */
    public function setId($id){
        $this->id = $id;

        return $this;
    }

     /**
     * @return string nome
     */
    public function getNome(){
        return $this->nome;
    }

     /**
     * @return App\Models\Entity\Anunciante
     */
    public function setNome($nome){
        if (!$nome && !is_string($nome)) {
            throw new \InvalidArgumentException("Nome deve ser informado!", 400);
        }

        $this->nome = $nome;

        return $this;
    }

     /**
     * @return string endereco
     */
    public function getEndereco(){
        return $this->endereco;
    }

     /**
     * @return App\Models\Entity\Anunciante
     */
    public function setEndereco($endereco){
        if (!$endereco && !is_string($endereco)) {
            throw new \InvalidArgumentException("Endereço deve ser informado!", 400);
        }
        $this->endereco = $endereco;

        return $this;
    }

     /**
     * @return string telefone
     */
    public function getTelefone(){
        return $this->telefone;
    }

     /**
     * @return App\Models\Entity\Anunciante
     */
    public function setTelefone($telefone){
        if (!$telefone && !is_string($telefone)) {
            throw new \InvalidArgumentException("Telefone deve ser informado!", 400);
        }
        $this->telefone = $telefone;
        
        return $this;
    }
    
    public function getAnuncios(){
       return $this->anuncios;
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
            "nome" => $this->getNome(),
            "endereco" => $this->getEndereco(),
            "telefone" => $this->getTelefone()];
    }
}