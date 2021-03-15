<?php

    require_once "Conexao.php"; 

    class Celular {
        public $idcelular;
        public $fkpessoa;
        public $celular;
    

        public function __construct() {
            $this->idcelular = 0;
            $this->fkpessoa = 0;
            $this->celular = "";
        }


        public function getIdCelular() { return $this->idcelular; }
        public function setIdCelular($idcelular) { $this->idcelular = $idcelular; }


        public function getFkPessoa() { return $this->fkpessoa; }
        public function setFkPessoa($fkpessoa) { $this->fkpessoa = $fkpessoa; }


        public function getCelular() { return $this->celular; }
        public function setCelular($celular) { $this->celular = $celular; }
        
        
        public function cadastrar() {
            $obj = new Conexao();
            $conexao = $obj->conectar();
            
            $query = "INSERT INTO celular VALUES(0, :fkpessoa, :celular)";
            $comando = $conexao->prepare($query);

            $comando->bindParam(":fkpessoa", $this->fkpessoa);
            $comando->bindParam(":celular", $this->celular);

            $comando->execute();
        }

        
        public function editar() {
            $obj = new Conexao();
            $conexao = $obj->conectar();
            
            $query = "UPDATE celular SET celular = :celular WHERE idcelular = :idcelular";
            $comando = $conexao->prepare($query);

            $comando->bindParam(":celular", $this->celular);
            $comando->bindParam(":idcelular", $this->idcelular);

            $comando->execute();
        }


        public function deletar() {
            $obj = new Conexao();
            $conexao = $obj->conectar();
            
            if ($this->idcelular != 0) {
                $query = "DELETE FROM celular WHERE idcelular = :idcelular";
                $comando = $conexao->prepare($query);

                $comando->bindParam(":idcelular", $this->idcelular);
            
            } elseif ($this->fkpessoa != 0) {

                $query = "DELETE FROM celular WHERE fkpessoa = :fkpessoa";
                $comando = $conexao->prepare($query);
    
                $comando->bindParam(":fkpessoa", $this->fkpessoa);
    
            }

            $comando->execute();        
        }


        public function selecionar($pesquisa) {
            $obj = new Conexao();
            $conexao = $obj->conectar();
            
            if (!isset($pesquisa)) {
                $query = "SELECT * FROM celular";
                $comando = $conexao->prepare($query);
                
                $comando->execute();
                
                return $comando->fetchAll();
            
            } else {
                $query = "SELECT * FROM celular WHERE fkpessoa = :id";
                $comando = $conexao->prepare($query);
                $comando->bindParam(":id", $pesquisa);
                
                $comando->execute();

                return $comando->fetchAll();
            }
        }

    }
