<?php 

    require_once "Conexao.php";

    class Dados_Cartao {
        private $iddcartao;
        private $ncartao;
        private $titular;
        private $validade;
        private $cvv;


        public function __construct() {
            $this->iddcartao = 0;
            $this->ncartao = "";
            $this->titular = "";
            $this->validade = "";
            $this->cvv = "";
        }

        
        public function getIdDCartao() { return $this->iddcartao; }
        public function setIdDCartao($iddcartao) { $this->iddcartao = $iddcartao; }


        public function getNCartao() { return $this->ncartao; }
        public function setNCartao($ncartao) { $this->ncartao = $ncartao; }


        public function getTitular() { return $this->titular; }
        public function setTitular($titular) { $this->titular = $titular; }


        public function getValidade() { return $this->validade; }
        public function setValidade($validade) { $this->validade = $validade; }


        public function getCvv() { return $this->cvv; }
        public function setCvv($cvv) { $this->cvv = $cvv; }


        public function cadastrar() {
            $obj = new Conexao();
            $conexao = $obj->conectar();
            
            $query = "INSERT INTO dados_cartao VALUES(0, :ncartao, :titular, :validade, :cvv)";
            $comando = $conexao->prepare($query);

            $comando->bindParam(":ncartao", $this->ncartao);
            $comando->bindParam(":titular", $this->titular);
            $comando->bindParam(":validade", $this->validade);
            $comando->bindParam(":cvv", $this->cvv);

            $comando->execute();

            $query = "SELECT MAX(iddcartao) AS 'iddcartao' FROM dados_cartao";
            $comando = $conexao->prepare($query);
            $comando->execute();
            
            return $comando->fetch();
        }


        public function selecionar() {
            $obj = new Conexao();
            $conexao = $obj->conectar();
            
            $query = "SELECT * FROM dados_cartao WHERE iddcartao = :iddcartao";
            $comando = $conexao->prepare($query);
            $comando->bindParam(":iddcartao", $this->iddcartao);
            $comando->execute();
            
            return $comando->fetch();
            
        }

        public function deletar() {
            $obj = new Conexao();
            $conexao = $obj->conectar();
            
            $query = "DELETE FROM dados_cartao WHERE iddcartao = :iddcartao";
            $comando = $conexao->prepare($query);
            $comando->bindparam(":iddcartao", $this->iddcartao);
            $comando->execute();
         }

    }