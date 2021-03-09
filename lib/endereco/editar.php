<?php

    session_start();
    
    if (!$_POST || !isset($_SESSION["id"]) || (!isset($_GET["idendereco"]) && !isset($_POST["idendereco"]))) {
        echo "<script>window.history.go(-1)</script>"; 
        die;
    }
    
    $idendereco = (isset($_POST["idendereco"])) ? $_POST["idendereco"] : $_GET["idendereco"];

    foreach ($_POST as $key => $value)
        $$key = $value;
    
    require_once "../../classes/Endereco.php";

    $endereco = new Endereco();

    $endereco->setCep($cep);
    $endereco->setRua($rua);
    
    $numero = (isset($numero)) ? $numero : "S/N";
        
    $endereco->setNumero($numero);
    $endereco->setBairro($bairro);
    $endereco->setCidade($cidade);
    $endereco->setEstado($estado);
    $endereco->setComplemento($complemento);
    $endereco->setIdEndereco($idendereco);

    $endereco->editar();

    if ($_SESSION["tipo"] == 0) {
        header("Location: ../../sacola");
        $_SESSION["sacola"] = true; 

    } elseif ($_SESSION["tipo"] == 1)
        header("Location: ../../mercado?id=". $_SESSION["id"]);


