<?php

    class Conexao {
        private $servidor;
        private $database;
        private $usuario;
        private $senha;

        public function __construct() {
            // $this->servidor = "sql205.epizy.com";
            // $this->database = "epiz_27515931_digitalmarket";
            // $this->usuario = "epiz_27515931";
            // $this->senha = "DEsPBq6QAKY";
            $this->servidor = "localhost";
            $this->database = "digitalmarket";
            $this->usuario = "root";
            $this->senha = "";
        }

        
        public function conectar() {
            try {
                $link = new PDO("mysql:host={$this->servidor}; dbname={$this->database}",
                                    $this->usuario, 
                                    $this->senha,
                                    array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")
                                );
                return $link;
                
            } catch (Exception $e) {
                echo "Ocorreu algum erro ao conectar com o banco de dados! ";
            }
        }
    }

