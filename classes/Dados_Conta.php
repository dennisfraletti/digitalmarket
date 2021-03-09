<?php
    class Dados_Conta {
        private $iddconta;
        private $beneficiario;
        private $documento;
        private $banco;
        private $agencia;
        private $conta;
        private $digito;
    
    
        public function __construct() {
            $this->iddconta = 0;
            $this->beneficiario = "";
            $this->documento = "";
            $this->banco = "";
            $this->agencia = "";
            $this->conta = "";
            $this->digito = "";
        }
    

        public function getIdDConta() { return $this->iddconta; }
        public function setIdDConta($iddconta) { $this->iddconta = $iddconta; }


        public function getBeneficiario() { return $this->beneficiario; }
        public function setBeneficiario($beneficiario) { $this->beneficiario = $beneficiario; }
    
    
        public function getDocumento() { return $this->documento; }
        public function setDocumento($documento) { $this->documento = $documento; }
    
        public function getBanco() { return $this->banco; }
        public function setBanco($banco) { $this->banco = $banco; }
    
        public function getAgencia() { return $this->agencia; }
        public function setAgencia($agencia) { $this->agencia = $agencia; }
    
    
        public function getConta() { return $this->conta; }
        public function setConta($conta) { $this->conta = $conta; }
    
    
        public function getDigito() { return $this->digito; }
        public function setDigito($digito) { $this->digito = $digito; }
    

        public function cadastrar() {
            $obj = new Conexao();
            $conexao = $obj->conectar();
            
            $query = "INSERT INTO dados_conta VALUES(0, :beneficiario, :documento, :banco, :agencia, :conta, :digito)";
            $comando = $conexao->prepare($query);

            $comando->bindParam(":beneficiario", $this->beneficiario);
            $comando->bindParam(":documento", $this->documento);
            $comando->bindParam(":banco", $this->banco);
            $comando->bindParam(":agencia", $this->agencia);
            $comando->bindParam(":conta", $this->conta);
            $comando->bindParam(":digito", $this->digito);

            $comando->execute();

            $query = "SELECT MAX(iddconta) AS 'iddconta' FROM dados_conta";
            $comando = $conexao->prepare($query);
            $comando->execute();
            
            return $comando->fetch();
        }
        

        public function selecionar() {
            $obj = new Conexao();
            $conexao = $obj->conectar();
            
            if ($this->iddconta == 0) {
                $query = "SELECT * FROM dados_conta";
                $comando = $conexao->prepare($query);
                
                $comando->execute();
                
                return $comando->fetchAll();
            
            } elseif ($this->iddconta != 0) {
                $query = "SELECT * FROM dados_conta WHERE iddconta = :iddconta";
                $comando = $conexao->prepare($query);
                $comando->bindParam(":iddconta", $this->iddconta);
                
                $comando->execute();

                return $comando->fetch();
            }
        }

        public function editar() {
            $obj = new Conexao();
            $conexao = $obj->conectar();

            $query = "UPDATE dados_conta SET beneficiario = :beneficiario, documento = :documento, banco = :banco, agencia = :agencia, conta = :conta, digito = :digito WHERE iddconta = :iddconta";
            $comando = $conexao->prepare($query);

            $comando->bindParam(":beneficiario", $this->beneficiario);
            $comando->bindParam(":documento", $this->documento);
            $comando->bindParam(":banco", $this->banco);
            $comando->bindParam(":agencia", $this->agencia);
            $comando->bindParam(":conta", $this->conta);
            $comando->bindParam(":digito", $this->digito);
            $comando->bindParam(":iddconta", $this->iddconta);

            $comando->execute();
        }

        public function deletar() {
            $obj = new Conexao();
            $conexao = $obj->conectar();

            $query = "DELETE FROM dados_conta WHERE iddconta = :iddconta";
            $comando = $conexao->prepare($query);
            $comando->bindParam(":iddconta", $this->iddconta);
            
            $comando->execute();
        }
    }