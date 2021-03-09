<?php

    session_start();
    
    if (!isset($_POST["idproduto"]) || !isset($_SESSION["id"]) || $_SESSION["tipo"] != 0) {
        header("location: ../../");
        die;
    }

    require_once "../../classes/Carrinho.php";

    $objConexao = new Conexao();
    $conexao = $objConexao->conectar();
    
    $query = "SELECT *, COUNT(*) AS 'repeticoes' FROM carrinho WHERE fkproduto = :fkproduto AND fkcliente = :fkcliente";
    $comando = $conexao->prepare($query);
    $comando->bindparam(":fkproduto", $_POST["idproduto"]);
    $comando->bindparam(":fkcliente", $_SESSION["id"]);
    
    $comando->execute();
    $carrinho = $comando->fetch();
    

    if ($carrinho["repeticoes"] == 0) {
        $objCarrinho = new Carrinho();

        $objCarrinho->setFkProduto($_POST["idproduto"]);
        $objCarrinho->setFkCliente($_SESSION["id"]);
        $objCarrinho->setQtd(1);
        $objCarrinho->adicionar();
    
    } else {
        require_once "../../classes/Produto.php";

        $objProduto = new Produto();
        $objProduto->setIdProduto($carrinho["fkproduto"]);
        $produto = $objProduto->selecionar();

        if ($carrinho["qtd"] < $produto["estoque"]) {
            $objCarrinho = new Carrinho();
            $objCarrinho->setIdCarrinho($carrinho["idcarrinho"]);
            $objCarrinho->alterar(1);    
        }
    }


    $objCarrinho = new Carrinho();
    $objCarrinho->setFkCliente($_SESSION["id"]);
    $total = $objCarrinho->selecionar(true);

    echo number_format($total["soma"], 2, ",", ".");

    