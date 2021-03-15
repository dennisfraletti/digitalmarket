<?php

    require_once "Conexao.php";

    class Produto_Imagem {
        private $idimagem;
        private $fkproduto;
        private $imagem;

        public function __construct() {
            $this->idimagem = 0;
            $this->fkproduto = 0;
            $this->imagem = "";
        }

        public function getIdImagem() { return $this->idimagem; }
        public function setIdImagem($idimagem) { $this->idimagem = $idimagem; }


        public function getFkProduto() { return $this->fkproduto; }
        public function setFkProduto($fkproduto) { $this->fkproduto = $fkproduto; }


        public function getImagem() { return $this->imagem; }
        public function setImagem($imagem) { $this->imagem = $imagem; }


        public function cadastrar() {
            $obj = new Conexao();
            $conexao = $obj->conectar();
        
            $query = "INSERT INTO produto_imagem VALUES(0, :fkproduto, :imagem)";
            $comando = $conexao->prepare($query);
    
            $comando->bindParam(":fkproduto", $this->fkproduto);
            $comando->bindParam(":imagem", $this->imagem);
    
            $comando->execute();
        }

        public function selecionar() {
            $obj = new Conexao();
            $conexao = $obj->conectar();
        
            if ($this->fkproduto != 0) {
                $query = "SELECT * FROM produto_imagem WHERE fkproduto = :fkproduto ORDER BY idimagem";
                $comando = $conexao->prepare($query);
                $comando->bindParam(":fkproduto", $this->fkproduto);

                $comando->execute();
                return $comando->fetchAll();                
            
                
            } elseif ($this->idimagem != 0) {
                $query = "SELECT * FROM produto_imagem WHERE idimagem = :idimagem ORDER BY idimagem";
                $comando = $conexao->prepare($query);
                $comando->bindParam(":idimagem", $this->idimagem);

                $comando->execute();
                return $comando->fetch();                
            }
        }

        public function deletar() {
            $obj = new Conexao();
            $conexao = $obj->conectar();
        
            if ($this->fkproduto != 0) {
                $query = "DELETE FROM produto_imagem WHERE fkproduto = :fkproduto";
                $comando = $conexao->prepare($query);
                $comando->bindParam(":fkproduto", $this->fkproduto);
                
            } elseif ($this->idimagem != 0) {
                $query = "DELETE FROM produto_imagem WHERE idimagem = :idimagem";
                $comando = $conexao->prepare($query);
                $comando->bindParam(":idimagem", $this->idimagem);
            }


            $comando->execute();
        }
    }