<?php
    
    session_start();

    if (!isset($_GET["idproduto"]) || empty($_GET["idproduto"]) || !isset($_SESSION["id"]) || isset($_SESSION["tipo"]) && $_SESSION["tipo"] != 1) {
        echo "<script>window.history.go(-1)</script>"; 
        die;
    } 

    require_once "../../classes/Pedido_Detalhes.php";
    $objPedido_Detalhes = new Pedido_Detalhes();

    $objPedido_Detalhes->setFkProduto($_GET["idproduto"]);
    $qtd = $objPedido_Detalhes->selecionar()["qtd"];

    
    if ($qtd > 0) { 
        echo "<script>window.location = '../../vendas'; alert('Esse produto já foi comprado por alguns usuários!');</script>";
        die;      
    }

    require_once "../../classes/Produto.php";
    
    $objProduto = new Produto();
    $objProduto->setIdProduto($_GET["idproduto"]);
    $produto = $objProduto->selecionar();
    
    require_once "../../classes/Produto_Imagem.php";


    if ($produto["fkmercado"] == $_SESSION["id"]) {
        require_once "../../classes/Carrinho.php";
        $objCarrinho = new Carrinho();
        
        $objCarrinho->setFkProduto($_GET["idproduto"]);
        $objCarrinho->remover();
    
    
        require_once "../../classes/Visitas.php";
        $objVisitas = new Visitas();
        
        $objVisitas->setFkProduto($_GET["idproduto"]);
        $objVisitas->deletar();


        $produto_imagem = new Produto_Imagem();

        $produto_imagem->setFkProduto($_GET["idproduto"]);
        

        $imagens = $produto_imagem->selecionar();

        foreach ($imagens as $imagem)
            unlink("../../uploads/mercado/". $_SESSION["id"] ."/produtos/" . $imagem["imagem"]);
        

        $produto_imagem->setFkProduto($produto["idproduto"]);
        $produto_imagem->deletar();

        $objProduto->setIdProduto($_GET["idproduto"]);
        $objProduto->deletar();

    } 

    header("location: ../../vendas");
    