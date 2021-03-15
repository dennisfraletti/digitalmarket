<?php

    session_start();
    
    if (!$_POST || !isset($_SESSION["id"])) {
        echo "<script>window.history.go(-1)</script>"; 
        die;
    }
    
    foreach ($_POST as $key => $value)
        $$key = $value;
    
    require_once "../../classes/Dados_Conta.php";

    $dados_conta = new Dados_Conta();


    require_once "../../classes/Mercado.php";
        
    $mercado = new Mercado();
    $mercado->pessoa->setId($_SESSION["id"]);
    $mercado = $mercado->selecionar();


    if ($tipoconta == "pf") {
        require_once "../../classes/Pessoa.php";

        $pessoa = new Pessoa();
        
        $pessoa->setId($_SESSION["id"]);
        $pessoa = $pessoa->selecionar();
        
        $beneficiario = $pessoa["nome"];
        $documento = $pessoa["cpf"];

        
    } else if ($tipoconta == "pj") {

        $beneficiario = $mercado["razaosocial"];
        $documento = $mercado["cnpj"];
    }

    $dados_conta->setBeneficiario($beneficiario);
    $dados_conta->setDocumento($documento);
    $dados_conta->setBanco($banco);
    $dados_conta->setAgencia($agencia);
    $dados_conta->setConta($conta);
    $dados_conta->setDigito($digito);
    $dados_conta->setIdDConta($mercado["fkdconta"]);
    $dados_conta->editar();

    header("Location: ../../mercado?id=". $_SESSION["id"]);

