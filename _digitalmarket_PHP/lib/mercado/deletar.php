<?php
    
    session_start();

    if (!$_POST || !isset($_SESSION["id"]) || $_SESSION["tipo"] != 1) {
        echo "<script>window.history.go(-1)</script>";
        die; 
    }

    require_once "../../classes/Mercado.php";
    $objMercado = new Mercado();

    
    $objMercado->pessoa->setId($_SESSION["id"]);
    $mercado = $objMercado->selecionar();
    
    $pessoa = $objMercado->pessoa->selecionar();

    if (sha1($_POST["senha"]) != $pessoa["senha"]) {
        $_SESSION["erro"] = true;
        header("Location: ../../mercado?id=" . $_SESSION["id"]);
        die;
    }

    $obj = new Conexao();
    $conexao = $obj->conectar();

    $query = "SELECT COUNT(*) AS 'qtd' FROM pedido_detalhes pd INNER JOIN produto pr ON pd.fkproduto = pr.idproduto WHERE pr.fkmercado = :fkmercado";
    $comando = $conexao->prepare($query);

    $comando->bindparam(":fkmercado", $_SESSION["id"]);
    $comando->execute();
    $qtd = $comando->fetch()["qtd"];

    if ($qtd > 0) {
        echo "<script>alert('Impossível excluir! Seu mercado já fez vendas anteriormente...'); window.history.go(-1)</script>";        
        die;
    }

    $objMercado->pessoa->celular->setFkPessoa($mercado["idmercado"]);
    $objMercado->pessoa->celular->deletar();
    
    $objMercado->pessoa_endereco->setFkPessoa($mercado["idmercado"]);
    $objMercado->pessoa_endereco->deletar();
    
    
    require_once "../../classes/Produto.php";
    $objProduto = new Produto();
    
    $objProduto->setFkMercado($_SESSION["id"]);
    $produtos = $objProduto->selecionar();


    require_once "../../classes/Produto_Imagem.php";
    $objProduto_Imagem = new Produto_Imagem();

    require_once "../../classes/Visitas.php";
    $objVisita = new Visitas();


    foreach ($produtos as $produto) {
        $objVisita->setFkProduto($produto["idproduto"]);
        $objVisita->deletar();

        $objProduto_Imagem->setFkProduto($produto["idproduto"]);
        $imagens = $objProduto_Imagem->selecionar();

        foreach ($imagens as $imagem) {
            unlink("../../uploads/mercado/".$_SESSION["id"] ."/produtos/".$imagem["imagem"]);
        }

        $objProduto_Imagem->deletar();
    }

    $objProduto->deletar();

    unlink("../../uploads/mercado/" . $_SESSION["id"] . "/". $mercado["logo"]);

    rmdir("../../uploads/mercado/" . $_SESSION["id"] . "/produtos/");

    rmdir("../../uploads/mercado/" . $_SESSION["id"] . "/");

    $objMercado->pessoa->setId($mercado["idmercado"]);
    $objMercado->deletar();
    $objMercado->pessoa->deletar();

    $objMercado->endereco->setIdEndereco($mercado["fkendereco"]);
    $objMercado->endereco->deletar(); 

    $objMercado->dados_conta->setIdDConta($mercado["fkdconta"]);
    $objMercado->dados_conta->deletar();

    header("Location: ../pessoa/sair");


