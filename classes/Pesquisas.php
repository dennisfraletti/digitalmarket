<?php

    require_once "Conexao.php";


    class Pesquisas {
        private $idpesquisa;
        private $pesquisa;
        private $fkcliente;


        public function __conscruct() {
            $this->idpesquisa = 0;
            $this->pesquisa = "";
            $this->fkcliente = 0;
        }


        public function getIdPesquisa() { return $this->idpesquisa; }
        public function setIdPesquisa($idpesquisa) { $this->idpesquisa = $idpesquisa; }


        public function getPesquisa() { return $this->pesquisa; }
        public function setPesquisa($pesquisa) { $this->pesquisa = $pesquisa; }


        public function getFkCliente() { return $this->fkcliente; }
        public function setFkCliente($fkcliente) { $this->fkcliente = $fkcliente; }


        public function cadastrar() {
            $obj = new Conexao();
            $conexao = $obj->conectar();

            $query = "INSERT INTO pesquisas VALUES(0, :pesquisa, :fkcliente)";
            $comando = $conexao->prepare($query);

            $comando->bindparam(":pesquisa", $this->pesquisa);
            $comando->bindparam(":fkcliente", $this->fkcliente);

            $comando->execute();
        }

        
        public function selecionar() {
            $obj = new Conexao();
            $conexao = $obj->conectar();

            if ($this->pesquisa != "" && $this->fkcliente != 0) {
                $query = "SELECT *, COUNT(*) AS 'qtd' FROM pesquisas WHERE pesquisa = :pesquisa AND fkcliente = :fkcliente AND idpesquisa = (SELECT MAX(idpesquisa) FROM pesquisas)";
                $comando = $conexao->prepare($query);
                $comando->bindparam(":pesquisa", $this->pesquisa);
                $comando->bindparam(":fkcliente", $this->fkcliente);
    
                $comando->execute();
                return $comando->fetch();


            } elseif ($this->fkcliente != 0 && $this->pesquisa == "") {
                $query = "SELECT * FROM pesquisas WHERE fkcliente = :fkcliente ORDER BY idpesquisa DESC LIMIT 1";
                $comando = $conexao->prepare($query);
                $comando->bindparam(":fkcliente", $this->fkcliente);
    
                $comando->execute();
                return $comando->fetch();
            } 
        }

        // public function historico() {
        //     $obj = new Conexao();
        //     $conexao = $obj->conectar();

        //     if ($this->fkcliente != 0 && $this->pesquisa == "") {
        //         $query = "SELECT * FROM pesquisas WHERE fkcliente = :fkcliente ORDER BY idpesquisa DESC LIMIT 8";
        //         $comando = $conexao->prepare($query);
        //         $comando->bindparam(":fkcliente", $this->fkcliente);
    
        //         $comando->execute();
                
        //         return $comando->fetchAll();
         
         
        //     } elseif ($this->fkcliente != 0 && $this->pesquisa != "") {
        //         $query = "SELECT * FROM pesquisas WHERE fkcliente = :fkcliente AND pesquisa LIKE :pesquisa ORDER BY idpesquisa DESC";
        //         $comando = $conexao->prepare($query);
        //         $comando->bindparam(":fkcliente", $this->fkcliente);
                
        //         $search = $this->pesquisa."%";

        //         $comando->bindparam(":pesquisa", $search);
    
        //         $comando->execute();

        //         return $comando->fetchAll();

        //     } elseif (!isset($_SESSION["id"]) || (isset($_SESSION["tipo"]) && $_SESSION["tipo"] == 1)) {
        //         $query = "SELECT *, COUNT(*) AS 'qtd' FROM pesquisas GROUP BY pesquisa ORDER BY qtd DESC LIMIT 8";
        //         $comando = $conexao->prepare($query);    
                
        //         $comando->execute();
                
        //         return $comando->fetchAll();
        //     }
        // }
    }
