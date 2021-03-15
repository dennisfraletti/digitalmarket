<?php

    session_start();

    if (!$_POST || !isset($_SESSION["id"]) || (isset($_SESSION["tipo"]) && $_SESSION["tipo"] != 0)) {
        echo "<script>window.history.go(-1)</script>";
        die;
    }

    require_once "../../classes/Carrinho.php";
    $objCarrinho = new Carrinho();
    
    $objCarrinho->setIdCarrinho($_POST["idcarrinho"]);
    $carrinho = $objCarrinho->selecionar(false);
    
    
    require_once "../../classes/Produto.php";

    $objProduto = new Produto();
    $objProduto->setIdProduto($carrinho["fkproduto"]);
    $produto = $objProduto->selecionar();

    if ($carrinho["qtd"] < $produto["estoque"])  $objCarrinho->alterar($_POST["operacao"]);    
        
    elseif ($_POST["operacao"] == -1 && $carrinho["qtd"] > 1)  $objCarrinho->alterar(-1);
    
    $objCarrinho = new Carrinho();
    $objCarrinho->setFkCliente($_SESSION["id"]);
    $total = $objCarrinho->selecionar(true);
    

    $objCarrinho = new Carrinho();
    $objCarrinho->setIdCarrinho($_POST["idcarrinho"]);
    $carrinho = $objCarrinho->selecionar(false);

    // retorna quantidade, preco, precodesconto, precototal
    echo $carrinho["qtd"]."<><>".number_format($produto["preco"] * $carrinho["qtd"], 2, ",", ".")."<><>".number_format($produto["precodesconto"] * $carrinho["qtd"], 2, ",", ".")."<><>".number_format($total["soma"], 2, ",", ".")."<><>".number_format($total["soma"] + 9.99, 2, ",", ".")."<><>".number_format($total["soma"] + 9.99, 2, ".", "");
    