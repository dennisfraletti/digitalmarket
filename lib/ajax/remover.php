<?php

    session_start();

    if (!isset($_POST["idcarrinho"]) || !isset($_SESSION["id"]) || $_SESSION["tipo"] != 0) {
        header("location: ../../");
        die;
    }
    
    require_once "../../classes/Carrinho.php";

    $carrinho = new Carrinho();

    $carrinho->setIdCarrinho($_POST["idcarrinho"]);
    $carrinho->remover();

    $objCarrinho = new Carrinho();
    $objCarrinho->setFkCliente($_SESSION["id"]);
    $total = $objCarrinho->selecionar(true);

    $qtdregistros = $objCarrinho->selecionar(false);

    echo number_format($total["soma"], 2 , ",", ".")."<><>".count($qtdregistros)."<><>".number_format($total["soma"] + 9.99, 2 , ",", ".")."<><>".number_format($total["soma"] + 9.99, 2 , ".", "");
