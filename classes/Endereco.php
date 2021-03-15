<?php

    require_once "Conexao.php";

    class Endereco {
        private $idendereco;
        private $cep;
        private $rua;
        private $numero;
        private $bairro;
        private $cidade;
        private $estado;
        private $complemento;
            
    
        public function __construct() {
            $this->idendereco = 0;
            $this->cep = "";
            $this->rua = "";
            $this->numero = "";
            $this->bairro = "";
            $this->cidade = "";
            $this->estado = "";
            $this->complemento = "";
        }
    
        public function getIdEndereco() { return $this->idendereco; }
        public function setIdEndereco($idendereco) { $this->idendereco = $idendereco; }


        public function getCep() { return $this->cep; }
        public function setCep($cep) { $this->cep = $cep; }
    
    
        public function getRua() { return $this->rua; }
        public function setRua($rua) { $this->rua = $rua; }
    
    
        public function getNumero() { return $this->numero; }
        public function setNumero($numero) { $this->numero = $numero; }
    
    
        public function getBairro() { return $this->bairro; }
        public function setBairro($bairro) { $this->bairro = $bairro; }
    
    
        public function getCidade() { return $this->cidade; }
        public function setCidade($cidade) { $this->cidade = $cidade; }
    
    
        public function getEstado() { return $this->estado; }
        public function setEstado($estado) { $this->estado = $estado; }
    
    
        public function getComplemento() { return $this->complemento; }
        public function setComplemento($complemento) { $this->complemento = $complemento; }
    

        public function cadastrar() {
            $obj = new Conexao();
            $conexao = $obj->conectar();
            
            $query = "INSERT INTO endereco VALUES(0, :cep, :rua, :numero, :bairro, :cidade, :estado, :complemento)";
            $comando = $conexao->prepare($query);

            $comando->bindParam(":cep", $this->cep);
            $comando->bindParam(":rua", $this->rua);
            $comando->bindParam(":numero", $this->numero);
            $comando->bindParam(":bairro", $this->bairro);
            $comando->bindParam(":cidade", $this->cidade);
            $comando->bindParam(":estado", $this->estado);
            $comando->bindParam(":complemento", $this->complemento);

            $comando->execute();

            $query = "SELECT MAX(idendereco) AS 'idendereco' FROM endereco";
            $comando = $conexao->prepare($query);
            $comando->execute();
            
            return $comando->fetch();
        }


        public function selecionar($pesquisa) {
            $obj = new Conexao();
            $conexao = $obj->conectar();

            $query = "SELECT * FROM endereco e INNER JOIN pessoa_endereco pe ON e.idendereco = pe.fkendereco WHERE pe.fkpessoa = :id";
            $comando = $conexao->prepare($query);
            $comando->bindParam(":id", $pesquisa);

            $comando->execute();

            return $comando->fetch();
        }

        
        public function editar() {
            $obj = new Conexao();
            $conexao = $obj->conectar();

            $query = "UPDATE endereco SET cep = :cep, rua = :rua, numero = :numero, bairro = :bairro, cidade = :cidade, estado = :estado, complemento = :complemento WHERE idendereco = :idendereco";
            $comando = $conexao->prepare($query);

            $comando->bindParam(":cep", $this->cep);
            $comando->bindParam(":rua", $this->rua);
            $comando->bindParam(":numero", $this->numero);
            $comando->bindParam(":bairro", $this->bairro);
            $comando->bindParam(":cidade", $this->cidade);
            $comando->bindParam(":estado", $this->estado);
            $comando->bindParam(":complemento", $this->complemento);
            $comando->bindParam(":idendereco", $this->idendereco);
            
            $comando->execute();
        }


        public function deletar() {
            $obj = new Conexao();
            $conexao = $obj->conectar();

            $query = "DELETE FROM endereco WHERE idendereco = :idendereco";
            $comando = $conexao->prepare($query);

            $comando->bindParam(":idendereco", $this->idendereco);
            
            $comando->execute();        
        }
        
    }