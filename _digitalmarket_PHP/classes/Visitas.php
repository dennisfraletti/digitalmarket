<?php

    require_once "Conexao.php";


    class Visitas {
        private $idvisita;
        private $fkcliente;
        private $fkproduto;


        public function __construct() {
            $this->idvisita = 0;
            $this->fkcliente = 0;
            $this->fkproduto = 0;
        }


        public function getIdVisita() { return $this->idvisita; }
        public function setIdVisita($idvisita) { $this->idvisita = $idvisita; }


        public function getFkCliente() { return $this->fkcliente; }
        public function setFkCliente($fkcliente) { $this->fkcliente = $fkcliente; }


        public function getFkProduto() { return $this->fkproduto; }
        public function setFkProduto($fkproduto) { $this->fkproduto = $fkproduto; }


        public function cadastrar() {
            $obj = new Conexao();
            $conexao = $obj->conectar();

            $query = "INSERT INTO visitas VALUES(0, :fkcliente, :fkproduto)";
            $comando = $conexao->prepare($query);

            $comando->bindparam(":fkcliente", $this->fkcliente);
            $comando->bindparam(":fkproduto", $this->fkproduto);

            $comando->execute();
        }

        
        public function selecionar() {
            $obj = new Conexao();
            $conexao = $obj->conectar();

            if ($this->fkcliente != 0 && $this->fkproduto != 0) {
                $query = "SELECT *, COUNT(*) AS 'qtd' FROM visitas WHERE fkcliente = :fkcliente AND fkproduto = :fkproduto";
                $comando = $conexao->prepare($query);
                $comando->bindparam(":fkcliente", $this->fkcliente);
                $comando->bindparam(":fkproduto", $this->fkproduto);
    
                $comando->execute();
                return $comando->fetch();


            } else {
                $query = "SELECT * FROM visitas WHERE fkcliente = :fkcliente ORDER BY idvisita DESC LIMIT 8";
                $comando = $conexao->prepare($query);
                $comando->bindparam(":fkcliente", $this->fkcliente);
    
                $comando->execute();
                return $comando->fetchAll();
            }

        }

        public function deletar() {
            $obj = new Conexao();
            $conexao = $obj->conectar();

            if ($this->fkproduto != 0) {
                $query = "DELETE FROM visitas WHERE fkproduto = :fkproduto";
                $comando = $conexao->prepare($query);
    
                $comando->bindparam(":fkproduto", $this->fkproduto);
                $comando->execute();
            } 
        
        }
    }
