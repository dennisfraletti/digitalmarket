<?php

    require_once "Pessoa.php";
    require_once "Endereco.php";
    require_once "Pessoa_Endereco.php";
    require_once "Dados_Conta.php";


    class Mercado {
        public $pessoa;
        private $razaosocial;
        private $nomefantasia;
        private $cnpj;
        private $logo;
        private $bio;
        public $endereco;
        public $pessoa_endereco;
        // private $plano;
        public $dados_conta;

        public function __construct() {
            $this->pessoa = new Pessoa();
            $this->razaosocial = "";
            $this->nomefantasia = "";
            $this->cnpj = "";
            $this->logo = "";
            $this->bio = "";
            $this->endereco = new Endereco();
            $this->pessoa_endereco = new Pessoa_Endereco();
            // $this->plano = null;
            $this->dados_conta = new Dados_Conta();
        }


        public function getRazaoSocial() { return $this->razaosocial; }
        public function setRazaoSocial($razaosocial) { $this->razaosocial = $razaosocial; }


        public function getNomeFantasia() { return $this->nomefantasia; }
        public function setNomeFantasia($nomefantasia) { $this->nomefantasia = $nomefantasia; }


        public function getCnpj() { return $this->cnpj; }
        public function setCnpj($cnpj) { $this->cnpj = $cnpj; }


        public function getLogo() { return $this->logo; }
        public function setLogo($logo) { $this->logo = $logo; }


        public function getBio() { return $this->bio; }
        public function setBio($bio) { $this->bio = $bio; }


        // public function getPlano() { return $this->plano; }
        // public function setPlano($plano) { $this->plano = $plano; }


        public function cadastrar() {
            $obj = new Conexao();
            $conexao = $obj->conectar();
        
            // cadastrando mercado
            $query = "INSERT INTO mercado VALUES(:pessoa, :razaosocial, :nomefantasia, :cnpj, :logo, :bio, :endereco, :dados_conta, NOW())";
            $comando = $conexao->prepare($query);
    
            $comando->bindParam(":pessoa", strval($this->pessoa->getId()));
            $comando->bindParam(":razaosocial", $this->razaosocial);
            $comando->bindParam(":nomefantasia", $this->nomefantasia);
            $comando->bindParam(":cnpj", $this->cnpj);
            $comando->bindParam(":logo", $this->logo);
            $comando->bindParam(":bio", $this->bio);
            $comando->bindParam(":endereco", strval($this->endereco->getIdEndereco()));
            // $comando->bindParam(":plano", $this->plano);
            $comando->bindParam(":dados_conta", strval($this->dados_conta->getIdDConta()));
    
            $comando->execute();
        }


        public function editar() {
            $obj = new Conexao();
            $conexao = $obj->conectar();
        
            // cadastrando mercado
            $query = "UPDATE mercado SET razaosocial = :razaosocial, nomefantasia = :nomefantasia, cnpj = :cnpj, logo = :logo, bio = :bio WHERE idmercado = :idmercado";
            $comando = $conexao->prepare($query);
    
            $comando->bindParam(":razaosocial", $this->razaosocial);
            $comando->bindParam(":nomefantasia", $this->nomefantasia);
            $comando->bindParam(":cnpj", $this->cnpj);
            $comando->bindParam(":logo", $this->logo);
            $comando->bindParam(":bio", $this->bio);
            // $comando->bindParam(":plano", $this->plano);
            $comando->bindParam(":idmercado", strval($this->pessoa->getId()));
    
            $comando->execute();
        }


        public function selecionar() {
            $obj = new Conexao();
            $conexao = $obj->conectar();
            
            if ($this->pessoa->getId() != 0) {
                $query = "SELECT * FROM mercado WHERE idmercado = :idmercado";
                $comando = $conexao->prepare($query);
                $comando->bindParam(":idmercado", strval($this->pessoa->getId()));
                
                $comando->execute();
                
                return $comando->fetch();
            

            } elseif ($this->nomefantasia != "" || $this->razaosocial != "" || $this->cnpj != "" || $this->bio != "") {
                $query = "SELECT * FROM mercado WHERE nomefantasia LIKE :pesquisa OR razaosocial LIKE :pesquisa OR cnpj LIKE :pesquisa OR bio LIKE :pesquisa";
                $comando = $conexao->prepare($query);
                
                $pesquisa = "%{$this->nomefantasia}%";
                $comando->bindparam(":pesquisa", $pesquisa);
                
                $comando->execute();
                return $comando->fetchAll();  


            } else {
                $query = "SELECT * FROM mercado ORDER BY idmercado DESC";
                $comando = $conexao->prepare($query);
                
                $comando->execute();
                return $comando->fetchAll();
            }
        }

        public function deletar() {
            $obj = new Conexao();
            $conexao = $obj->conectar();
            
            $query = "DELETE FROM mercado WHERE idmercado = :idmercado";
            $comando = $conexao->prepare($query);

            $comando->bindParam(":idmercado", strval($this->pessoa->getId()));

            $comando->execute();
        }

    }

