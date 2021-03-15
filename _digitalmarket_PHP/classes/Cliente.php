<?php

    require_once "Pessoa.php";
    require_once "Endereco.php";
    require_once "Pessoa_Endereco.php";
    require_once "Dados_Cartao.php";


    class Cliente {
        public $pessoa;
        public $endereco;
        public $pessoa_endereco;
        public $dados_cartao;

        public function __construct() {
            $this->pessoa = new Pessoa();
            $this->endereco = new Endereco();
            $this->pessoa_endereco = new Pessoa_Endereco();
            $this->dados_cartao = new Dados_Cartao();
        }


        public function selecionar() {
            $obj = new Conexao();
            $conexao = $obj->conectar();

            // selecionando cliente
            $query = "SELECT * FROM cliente c INNER JOIN pessoa p ON c.idcliente = p.id INNER JOIN endereco e ON c.fkendereco = e.idendereco WHERE p.id = :id";
            $comando = $conexao->prepare($query);
            
            $comando->bindParam(":id", strval($this->pessoa->getId()));
            
            $comando->execute();

            return $comando->fetch();
        }

        public function cadastrar() {
            $obj = new Conexao();
            $conexao = $obj->conectar();
    
            // cadastrando cliente 
            $query = "INSERT INTO cliente VALUES(:pessoa, :endereco, null)";
            $comando = $conexao->prepare($query);

            $comando->bindParam(":pessoa", strval($this->pessoa->getId()));
            $comando->bindParam(":endereco", strval($this->endereco->getIdEndereco()));

            $comando->execute();
        }

        public function editar() {
            $obj = new Conexao();
            $conexao = $obj->conectar();
    
            // cadastrando cliente 
            $query = "UPDATE cliente SET fkendereco = :fkendereco, fkdcartao = :fkdcartao WHERE idcliente = :idcliente";
            $comando = $conexao->prepare($query);

            $fkdcartao = (empty($this->dados_cartao->getIdDCartao())) ? NULL : $this->dados_cartao->getIdDCartao();

            $comando->bindParam(":fkendereco", strval($this->endereco->getIdEndereco()));
            $comando->bindParam(":fkdcartao", $fkdcartao);
            $comando->bindParam(":idcliente", strval($this->pessoa->getId()));

            $comando->execute();
        }
    }

