<?php

    require_once "Conexao.php";

    class Pedido {
        private $idpedido;
        private $fkcliente;
        private $troco;
        private $pagamento;
        private $precototal;


        public function __construct() {
            $this->idpedido = 0;
            $this->fkcliente = 0;
            $this->troco = 0;
            $this->pagamento = null;
            $this->precototal = 0;
        }


        public function getIdPedido() { return $this->idpedido; }
        public function setIdPedido($idpedido) { $this->idpedido = $idpedido; }


        public function getFkCliente() { return $this->fkcliente; }
        public function setFkCliente($fkcliente) { $this->fkcliente = $fkcliente; }


        public function getTroco() { return $this->troco; }
        public function setTroco($troco) { $this->troco = $troco; }


        public function getPagamento() { return $this->pagamento; }
        public function setPagamento($pagamento) { $this->pagamento = $pagamento; }


        public function getPrecoTotal() { return $this->precototal; }
        public function setPrecoTotal($precototal) { $this->precototal = $precototal; }


        public function cadastrar() {
            $obj = new Conexao();
            $conexao = $obj->conectar();

            $query = "INSERT INTO pedido VALUES(0, :fkcliente, :troco, :pagamento, :precototal, NOW())";
            $comando = $conexao->prepare($query);

            $comando->bindparam(":fkcliente", $this->fkcliente);
            $comando->bindparam(":troco", $this->troco);
            $comando->bindparam(":pagamento", $this->pagamento);
            $comando->bindparam(":precototal", $this->precototal);

            $comando->execute();

            $query = "SELECT MAX(idpedido) AS 'idpedido' FROM pedido";
            $comando = $conexao->prepare($query);
            $comando->execute();
            
            return $comando->fetch();
        }


        public function selecionar() {
            $obj = new Conexao();
            $conexao = $obj->conectar();

            if ($this->fkcliente != 0) {
                $query = "SELECT * FROM pedido WHERE fkcliente = :fkcliente ORDER BY idpedido DESC";
                $comando = $conexao->prepare($query);
                
                $comando->bindparam(":fkcliente", $this->fkcliente);
                $comando->execute();
                return $comando->fetchAll();
            }
        }
    }

    