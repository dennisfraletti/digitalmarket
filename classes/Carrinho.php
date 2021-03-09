<?php

    require_once "Conexao.php";

    class Carrinho {
        private $idcarrinho;
        private $fkproduto;
        private $fkcliente;
        private $qtd;


        public function __construct() {
            $this->idcarrinho = 0;
            $this->fkproduto = 0;
            $this->fkcliente = 0;
            $this->qtd = 0;
        }


        public function getIdCarrinho() { return $this->idcarrinho; }
        public function setIdCarrinho($idcarrinho) { $this->idcarrinho = $idcarrinho; }


        public function getFkProduto() { return $this->fkproduto; }
        public function setFkProduto($fkproduto) { $this->fkproduto = $fkproduto; }


        public function getFkCliente() { return $this->fkcliente; }
        public function setFkCliente($fkcliente) { $this->fkcliente = $fkcliente; }


        public function getQtd() { return $this->qtd; }
        public function setQtd($qtd) { $this->qtd = $qtd; }


        public function selecionar($soma) {
            $obj = new Conexao();
            $conexao = $obj->conectar();

            if (!$soma && $this->fkcliente != 0) {
                $query = "SELECT * FROM carrinho WHERE fkcliente = :fkcliente ORDER BY idcarrinho DESC";
                $comando = $conexao->prepare($query);
                $comando->bindparam(":fkcliente", $this->fkcliente);
            
                $comando->execute();
                return $comando->fetchAll();

            
            } elseif (!$soma && $this->idcarrinho != 0) {
                $query = "SELECT * FROM carrinho WHERE idcarrinho = :idcarrinho";
                $comando = $conexao->prepare($query);
                $comando->bindparam(":idcarrinho", $this->idcarrinho);
            
                $comando->execute();
                return $comando->fetch();
            

            } elseif ($soma && $this->fkcliente != 0) {
                $query = "SELECT SUM(p.precodesconto * c.qtd) AS 'soma' FROM carrinho c INNER JOIN produto p ON c.fkproduto = p.idproduto WHERE c.fkcliente = :fkcliente";
                $comando = $conexao->prepare($query);
                $comando->bindparam(":fkcliente", $this->fkcliente);
            
                $comando->execute();
                return $comando->fetch();
            } 

        }

        public function adicionar() {
            $obj = new Conexao();
            $conexao = $obj->conectar();

            $query = "INSERT INTO carrinho VALUES(0, :fkproduto, :fkcliente, :qtd)";
            $comando = $conexao->prepare($query);
            $comando->bindparam(":fkproduto", $this->fkproduto);
            $comando->bindparam(":fkcliente", $this->fkcliente);
            $comando->bindparam(":qtd", $this->qtd);
        
            $comando->execute();
        }

        public function remover() {
            $obj = new Conexao();
            $conexao = $obj->conectar();

            if ($this->idcarrinho != 0) {
            
                $query = "DELETE FROM carrinho WHERE idcarrinho = :idcarrinho";
                $comando = $conexao->prepare($query);
                $comando->bindparam(":idcarrinho", $this->idcarrinho);
                $comando->execute();
            
            } else if ($this->fkproduto != 0) {
            
                $query = "DELETE FROM carrinho WHERE fkproduto = :fkproduto";
                $comando = $conexao->prepare($query);
                $comando->bindparam(":fkproduto", $this->fkproduto);
                $comando->execute();
            }

        }

        public function alterar($qtd) {
            $obj = new Conexao();
            $conexao = $obj->conectar();

            $query = ($qtd == 1) ? 
                "UPDATE carrinho SET qtd = qtd + 1 WHERE idcarrinho = :idcarrinho" : 
                "UPDATE carrinho SET qtd = qtd - 1 WHERE idcarrinho = :idcarrinho";

            $comando = $conexao->prepare($query);
            $comando->bindparam(":idcarrinho", $this->idcarrinho);
            $comando->execute();
        }
    }