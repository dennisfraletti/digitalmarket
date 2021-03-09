<?php

    require_once "Conexao.php";

    class Categoria_Imagem {
        private $idimagem;
        private $fkcategoria;
        private $imagem;
    
    
        public function __construct() {
            $this->idimagem = 0;
            $this->fkcategoria = 0;
            $this->imagem = "";
        }
    
        public function getIdimagem() { return $this->idimagem; }
        public function setIdimagem($idimagem) { $this->idimagem = $idimagem; }
    
    
        public function getFkcategoria() { return $this->fkcategoria; }
        public function setFkcategoria($fkcategoria) { $this->fkcategoria = $fkcategoria; }
    
    
        public function getImagem() { return $this->imagem; }
        public function setImagem($imagem) { $this->imagem = $imagem; }
    

        public function selecionar() {
            $obj = new Conexao();
            $conexao = $obj->conectar();
            
            if ($this->fkcategoria != 0) {
                $query = "SELECT * FROM categoria_imagem WHERE fkcategoria = :fkcategoria";
                $comando = $conexao->prepare($query);
                $comando->bindparam(":fkcategoria", $this->fkcategoria);

                $comando->execute();
                return $comando->fetchAll();
            }
        }
    
    }