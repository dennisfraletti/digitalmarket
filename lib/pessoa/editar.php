<?php

    session_start();
        
    if (!$_POST || !isset($_SESSION["id"])) {
        echo "<script>window.history.go(-1)</script>"; 
        die;
    }

    foreach ($_POST as $key => $value)
        $$key = $value;
    
    require_once "../../classes/Pessoa.php";

    $pessoa = new Pessoa();

    $pessoa->setNome($nome);
    $pessoa->setCpf($cpf);
    $pessoa->setRg($rg);
    $pessoa->setId($_SESSION["id"]);
    $pessoa->editar();

    
    if ($_SESSION["tipo"] == 1) {
        
        require_once "../../classes/Mercado.php";
        
        $objMercado = new Mercado();
        $mercado = $objMercado->selecionar($_SESSION["id"]);
        $dados_conta = $objMercado->dados_conta->selecionar($mercado["fkdconta"]);

        if (strlen($dados_conta["documento"]) == 14) {
            $objMercado->dados_conta->setBeneficiario($nome);
            $objMercado->dados_conta->setDocumento($cpf);
            $objMercado->dados_conta->setBanco($dados_conta["banco"]);
            $objMercado->dados_conta->setAgencia($dados_conta["agencia"]);
            $objMercado->dados_conta->setConta($dados_conta["conta"]);
            $objMercado->dados_conta->setDigito($dados_conta["digito"]);
            $objMercado->dados_conta->setIdDConta($mercado["fkdconta"]);
            $objMercado->dados_conta->editar();   
        }
    }

    header("Location: ../../mercado?id=". $_SESSION["id"]);
