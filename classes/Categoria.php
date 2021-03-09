<?php

    require_once "Conexao.php";

    class Categoria {
        private $idcategoria;
        private $categoria;

        public function __construct() {
            $this->idcategoria = 0;
            $this->categoria = "";
        }


        public function getIdCategoria() { return $this->idcategoria; }
        public function setIdCategoria($idcategoria) { $this->idcategoria = $idcategoria; }
    
    
        public function getCategoria() { return $this->categoria; }
        public function setCategoria($categoria) { $this->categoria = $categoria; }
    

        public function selecionar() {
            $obj = new Conexao();
            $conexao = $obj->conectar();

            if ($this->categoria != "") {
                $query = "SELECT * FROM categoria WHERE categoria LIKE :pesquisa";
                $comando = $conexao->prepare($query);

                $pesquisa = "%{$this->categoria}%";
                $comando->bindparam(":pesquisa", $pesquisa);

                $comando->execute();
                return $comando->fetchAll();
            
            } else {
                $query = "SELECT * FROM categoria";
                $comando = $conexao->prepare($query);
                $comando->execute();

                return $comando->fetchAll();
            }
        }

    }

    