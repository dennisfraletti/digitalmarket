<?php

    require_once "Conexao.php";

    class Produto {
        private $idproduto;
        private $titulo;
        private $descricao;
        private $preco;
        private $precodesconto;
        private $fkcategoria;
        private $estoque;
        private $fkmercado;


        public function __construct() {
            $this->idproduto = "";
            $this->titulo = "";
            $this->descricao = "";
            $this->preco = "";
            $this->precodesconto = "";
            $this->fkcategoria = "";
            $this->estoque = "";
            $this->fkmercado = "";
        }

        public function getIdProduto() { return $this->idproduto; }
        public function setIdProduto($idproduto) { $this->idproduto = $idproduto; }


        public function getTitulo() { return $this->titulo; }
        public function setTitulo($titulo) { $this->titulo = $titulo; }


        public function getDescricao() { return $this->descricao; }
        public function setDescricao($descricao) { $this->descricao = $descricao; }


        public function getPreco() { return $this->preco; }
        public function setPreco($preco) { $this->preco = $preco; }


        public function getPrecoDesconto() { return $this->precodesconto; }
        public function setPrecoDesconto($precodesconto) { $this->precodesconto = $precodesconto; }


        public function getFkCategoria() { return $this->fkcategoria; }
        public function setFkCategoria($fkcategoria) { $this->fkcategoria = $fkcategoria; }

        
        public function getEstoque() { return $this->estoque; }
        public function setEstoque($estoque) { $this->estoque = $estoque; }


        public function getFkMercado() { return $this->fkmercado; }
        public function setFkMercado($fkmercado) { $this->fkmercado = $fkmercado; }


        public function cadastrar() {
            $obj = new Conexao();
            $conexao = $obj->conectar();
        
            // cadastrando mercado
            $query = "INSERT INTO produto VALUES(0, :titulo, null, :preco, :precodesconto, :fkcategoria, :estoque, :fkmercado, NOW())";
            $comando = $conexao->prepare($query);
    
            $comando->bindParam(":titulo", $this->titulo);
            $comando->bindParam(":preco", $this->preco);
            $comando->bindParam(":precodesconto", $this->precodesconto);
            $comando->bindParam(":fkcategoria", $this->fkcategoria);
            $comando->bindParam(":estoque", $this->estoque);
            $comando->bindParam(":fkmercado", $this->fkmercado);
    
            $comando->execute();

            $query = "SELECT MAX(idproduto) AS 'idproduto' FROM produto";
            $comando = $conexao->prepare($query);
            $comando->execute();
            
            return $comando->fetch();
        }


        public function selecionar() {
            $obj = new Conexao();
            $conexao = $obj->conectar();

            if ($this->fkmercado != 0) {
                $query = "SELECT * FROM produto WHERE fkmercado = :fkmercado ORDER BY idproduto DESC";
                $comando = $conexao->prepare($query);
                $comando->bindparam(":fkmercado", $this->fkmercado);

                $comando->execute();
                return $comando->fetchAll();
            

            } elseif ($this->idproduto != 0) {
                $query = "SELECT * FROM produto WHERE idproduto = :idproduto";
                $comando = $conexao->prepare($query);
                $comando->bindparam(":idproduto", $this->idproduto);

                $comando->execute();
                return $comando->fetch();
            

            } elseif ($this->fkcategoria != 0) {
                $query = "SELECT * FROM produto WHERE fkcategoria = :fkcategoria ORDER BY idproduto DESC";
                $comando = $conexao->prepare($query);
                $comando->bindparam(":fkcategoria", $this->fkcategoria);

                $comando->execute();
                return $comando->fetchAll();


            } elseif ($this->titulo != "" || $this->descricao != "") {
                $query = "SELECT * FROM produto WHERE titulo LIKE :pesquisa OR descricao LIKE :pesquisa";
                $comando = $conexao->prepare($query);
                $pesquisa = "%{$this->titulo}%";
                $comando->bindparam(":pesquisa", $pesquisa);

                $comando->execute();
                return $comando->fetchAll();


            } else {
                $query = "SELECT * FROM produto WHERE estoque > 0 ORDER BY idproduto DESC";
                $comando = $conexao->prepare($query);

                $comando->execute();
                return $comando->fetchAll();
            }

        }


        public function editar() {
            $obj = new Conexao();
            $conexao = $obj->conectar();
        
            // cadastrando mercado
            $query = "UPDATE produto SET titulo = :titulo, descricao = :descricao, preco = :preco, precodesconto = :precodesconto, fkcategoria = :fkcategoria, estoque = :estoque, fkmercado = :fkmercado WHERE idproduto = :idproduto";
            $comando = $conexao->prepare($query);
    
            $comando->bindParam(":titulo", $this->titulo);
            $comando->bindParam(":descricao", $this->descricao);
            $comando->bindParam(":preco", $this->preco);
            $comando->bindParam(":precodesconto", $this->precodesconto);
            $comando->bindParam(":fkcategoria", $this->fkcategoria);
            $comando->bindParam(":estoque", $this->estoque);
            $comando->bindParam(":fkmercado", $this->fkmercado);
            $comando->bindParam(":idproduto", $this->idproduto);

            $comando->execute();

        }


        public function deletar() {
            $obj = new Conexao();
            $conexao = $obj->conectar();
            
            if ($this->fkmercado != 0) {
                $query = "DELETE FROM produto WHERE fkmercado = :fkmercado";
                $comando = $conexao->prepare($query);
                $comando->bindParam(":fkmercado", $this->fkmercado);
            
            } else {
                $query = "DELETE FROM produto WHERE idproduto = :idproduto";
                $comando = $conexao->prepare($query);
                $comando->bindParam(":idproduto", $this->idproduto);
            }

            $comando->execute();
        }

    }