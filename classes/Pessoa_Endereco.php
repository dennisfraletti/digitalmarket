<?php

    require_once "Conexao.php";

    class Pessoa_Endereco {
        public $fkpessoa;
        public $fkendereco;

        public function __construct() {
            $this->fkpessoa = 0;
            $this->fkendereco = 0;
        }

        public function getFkPessoa() { return $this->fkpessoa; }
        public function setFkPessoa($fkpessoa) { $this->fkpessoa = $fkpessoa; }


        public function getFkEndereco() { return $this->fkendereco; }
        public function setFkEndereco($fkendereco) { $this->fkendereco = $fkendereco; }

        
        public function cadastrar() {
            $obj = new Conexao();
            $conexao = $obj->conectar();
            
            $query = "INSERT INTO pessoa_endereco VALUES(:fkpessoa, :fkendereco)";
            $comando = $conexao->prepare($query);

            $comando->bindParam(":fkpessoa", $this->fkpessoa);
            $comando->bindParam(":fkendereco", $this->fkendereco);

            $comando->execute();
        }
    

        public function deletar() {
            $obj = new Conexao();
            $conexao = $obj->conectar();

            if ($this->fkpessoa != 0) {
                $query = "DELETE FROM pessoa_endereco WHERE fkpessoa = :id";
                $comando = $conexao->prepare($query);

                $comando->bindParam(":id", $this->fkpessoa);
    
                $comando->execute();    
            }
        }

        
    }

