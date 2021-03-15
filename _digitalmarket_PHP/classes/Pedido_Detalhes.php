<?php
    
    require_once "Conexao.php";

    class Pedido_Detalhes {
        private $iddetalhe;
        private $fkpedido;
        private $fkproduto;
        private $entregue;
        private $qtd;
    
    
        public function __construct() {
            $this->iddetalhe = 0;
            $this->fkpedido = 0;
            $this->fkproduto = 0;
            $this->entregue = 0;
            $this->qtd = 0;
        }

     
        public function getIdDetalhe() { return $this->iddetalhe; }
        public function setIdDetalhe($iddetalhe) { $this->iddetalhe = $iddetalhe; }
    
    
        public function getFkPedido() { return $this->fkpedido; }
        public function setFkPedido($fkpedido) { $this->fkpedido = $fkpedido; }
    
    
        public function getFkProduto() { return $this->fkproduto; }
        public function setFkProduto($fkproduto) { $this->fkproduto = $fkproduto; }


        public function getEntregue() { return $this->entregue; }
        public function setEntregue($entregue) { $this->entregue = $entregue; }
    

        public function getQtd() { return $this->qtd; }
        public function setQtd($qtd) { $this->qtd = $qtd; }
    

        public function cadastrar() {
            $obj = new Conexao();
            $conexao = $obj->conectar();

            $query = "INSERT INTO pedido_detalhes VALUES (0, :fkpedido, :fkproduto, 0, :qtd)";
            $comando = $conexao->prepare($query);
            $comando->bindparam(":fkpedido", $this->fkpedido);
            $comando->bindparam(":fkproduto", $this->fkproduto);
            $comando->bindparam(":qtd", $this->qtd);
        
            $comando->execute();
        }

        public function selecionar() {
            $obj = new Conexao();
            $conexao = $obj->conectar();

            if ($this->fkpedido != 0) {
                
                $query = "SELECT * FROM pedido_detalhes WHERE fkpedido = :fkpedido";
                $comando = $conexao->prepare($query);
                $comando->bindparam(":fkpedido", $this->fkpedido);
                
                $comando->execute();
                return $comando->fetchAll();
            

            } elseif ($this->fkproduto != 0) {
                
                $query = "SELECT COUNT(*) AS 'qtd' FROM pedido_detalhes WHERE fkproduto = :fkproduto";
                $comando = $conexao->prepare($query);
                $comando->bindparam(":fkproduto", $this->fkproduto);
                
                $comando->execute();
                return $comando->fetch();
            }
        }

        public function editar() {
            $obj = new Conexao();
            $conexao = $obj->conectar();

            $query = "UPDATE pedido_detalhes SET entregue = :entregue WHERE iddetalhe = :iddetalhe";
            $comando = $conexao->prepare($query);
                
            $comando->bindparam(":entregue", $this->entregue);
            $comando->bindparam(":iddetalhe", $this->iddetalhe);

            $comando->execute();
        }
    }