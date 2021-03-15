<?php

    require_once "Conexao.php";
    require_once "Celular.php";


    class Pessoa {
        private $id;
        private $nome;
        private $email;
        private $senha;
        private $cpf;
        private $rg;
        public $celular;


        public function __construct() {
            $this->id = 0;
            $this->nome = "";
            $this->email = "";
            $this->senha = "";
            $this->cpf = "";
            $this->rg = "";
            $this->celular = new Celular();
        }

        public function getId() { return $this->id; }
        public function setId($id) { $this->id = $id; }


        public function getNome() { return $this->nome; }
        public function setNome($nome) { $this->nome = $nome; }


        public function getEmail() { return $this->email; }
        public function setEmail($email) { $this->email = $email; }


        public function getSenha() { return $this->senha; }
        public function setSenha($senha) { $this->senha = $senha; }


        public function getCpf() { return $this->cpf; }
        public function setCpf($cpf) { $this->cpf = $cpf; }


        public function getRg() { return $this->rg; }
        public function setRg($rg) { $this->rg = $rg; }


        public function selecionar() {
            $obj = new Conexao();
            $conexao = $obj->conectar();
            
            if ($this->id != 0) {
                $query = "SELECT * FROM pessoa WHERE id = :id";
                $comando = $conexao->prepare($query);
                $comando->bindParam(":id", $this->id);
                
                $comando->execute();
                
                return $comando->fetch();
                

            } elseif ($this->email != "") {
                $query = "SELECT COUNT(*) AS 'qtd' FROM pessoa WHERE email = :email";
                $comando = $conexao->prepare($query);
                $comando->bindParam(":email", $this->email);
                
                $comando->execute();
                
                return $comando->fetch();
            
            } else {
                $query = "SELECT * FROM pessoa";
                $comando = $conexao->prepare($query);
                
                $comando->execute();
                
                return $comando->fetchAll();
            }
        }


        public function cadastrar() {
            $obj = new Conexao();
            $conexao = $obj->conectar();

            $query = "SELECT COUNT(*) AS 'qtd' FROM pessoa WHERE email = :email";
            $comando = $conexao->prepare($query);
            $comando->bindParam(":email", $this->email);
            $comando->execute();
            $resultado = $comando->fetch();
            
            // se não tiver nenhum registro
            if ($resultado["qtd"] == 0) {

                $query = "INSERT INTO pessoa VALUES(0, :nome, :email, sha1(:senha), :cpf, :rg)";
                $comando = $conexao->prepare($query);
    
                $comando->bindParam(":nome", $this->nome);
                $comando->bindParam(":email", $this->email);
                $comando->bindParam(":senha", $this->senha);
                $comando->bindParam(":cpf", $this->cpf);
                $comando->bindParam(":rg", $this->rg);
    
                $comando->execute();
    
                $query = "SELECT MAX(id) AS 'id' FROM pessoa";
                $comando = $conexao->prepare($query);
                $comando->execute();
                
                return $comando->fetch();
                
            } else {
                echo "<script>alert('E-mail já cadastrado! '); window.history.go(-1);</script>";
            }

        }


        public function entrar() {
            $obj = new Conexao();
            $conexao = $obj->conectar();

            // selecionando registro
            $query = "SELECT id, senha, COUNT(*) AS 'qtd' FROM pessoa WHERE email = :email";
            $comando = $conexao->prepare($query);

            $comando->bindParam(":email", strval($this->getEmail()));
            $comando->execute();

            $pessoa = $comando->fetch();

            // se achar um e-mail
            if ($pessoa["qtd"] > 0) {
                
                session_start();
                
                // se email e senha estiverem corretas
                if ($pessoa["senha"] == sha1($this->getSenha())) {  
                    $_SESSION["id"] = $pessoa["id"];
                  
                    $query = "SELECT COUNT(*) AS 'qtd' FROM cliente WHERE idcliente = :id";
                    $comando = $conexao->prepare($query);
                    $comando->bindParam(":id", $pessoa["id"]);
                    $comando->execute();
                    $resultado = $comando->fetch();
                    $qtdcliente = $resultado["qtd"];


                    $query = "SELECT COUNT(*) AS 'qtd' FROM mercado WHERE idmercado = :id";
                    $comando = $conexao->prepare($query);
                    $comando->bindParam(":id", $pessoa["id"]);
                    $comando->execute();
                    $resultado = $comando->fetch();
                    $qtdmercado = $resultado["qtd"];

                    if ($qtdcliente > 0)
                        $tipo = 0;
                        
                    else if ($qtdmercado > 0) 
                        $tipo = 1;
                    
                    $_SESSION["tipo"] = $tipo; 


                    return array(true, null);

                // se o email estiver certo e senha errada
                } else {
                    $_SESSION["iderro"] = $pessoa["id"];
                    $erro = 0;
                    return array(false, $erro);
                }
                
            // email não encontrado
            } else {
                $erro = 1;
                return array(false, $erro);
            }
        }


        public function editar() {
            $obj = new Conexao();
            $conexao = $obj->conectar();

            $query = "UPDATE pessoa SET nome = :nome, cpf = :cpf, rg = :rg WHERE id = :id";
            $comando = $conexao->prepare($query);
            $comando->bindParam(":nome", $this->nome);
            $comando->bindParam(":cpf", $this->cpf);
            $comando->bindParam(":rg", $this->rg);
            $comando->bindParam(":id", $this->id);

            $comando->execute();
        }


        public function deletar() {
            $obj = new Conexao();
            $conexao = $obj->conectar();
            
            $query = "DELETE FROM pessoa WHERE id = :id";
            $comando = $conexao->prepare($query);

            $comando->bindParam(":id", $this->id);

            $comando->execute();
        }
    }

