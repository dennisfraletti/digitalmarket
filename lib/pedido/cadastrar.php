<?php

    session_start();
        
    if (!$_POST || !isset($_SESSION["id"]) || !isset($_SESSION["tipo"]) || $_SESSION["tipo"] != 0) {
        echo "<script>window.history.go(-1)</script>"; 
        die;
    }

    foreach ($_POST as $key => $value)
        $$key = $value;
    
    require_once "../../classes/Pedido.php";
    $objPedido = new Pedido();

    $objPedido->setFkCliente($_SESSION["id"]);
    
    
    require_once "../../classes/Pedido_Detalhes.php";
    $objPedido_Detalhes = new Pedido_Detalhes();


    if ($pagamento == "cartao") {
        require_once "../../classes/Dados_Cartao.php";
        $objDados_Cartao = new Dados_Cartao();
        

        require_once "../../classes/Cliente.php";
        $objCliente = new Cliente();

        $objCliente->pessoa->setId($_SESSION["id"]);
        $cliente = $objCliente->selecionar();
        

        if (isset($salvarcartao)) {                
            $validade = $ano_validade . "-" . $mes_validade . "-00";

            $objDados_Cartao->setNCartao($ncartao);
            $objDados_Cartao->setTitular($titular);
            $objDados_Cartao->setValidade($validade);
            $objDados_Cartao->setCvv($cvv);
            
            $iddcartao = $objDados_Cartao->cadastrar()["iddcartao"];


            $objCliente->endereco->setIdEndereco($cliente["fkendereco"]);
            $objCliente->dados_cartao->setIdDCartao($iddcartao);
            $objCliente->editar();


        } elseif (!isset($salvarcartao) && !empty($cliente["fkdcartao"])) {

            $objCliente->endereco->setIdEndereco($cliente["fkendereco"]);
            $objCliente->dados_cartao->setIdDCartao(NULL);
            $objCliente->editar();

            $objDados_Cartao->setIdDCartao($cliente["fkdcartao"]);
            $objDados_Cartao->deletar();
        }
    
        $objPedido->setTroco(null);
        $objPedido->setPagamento(0);

        // cobrar cartao

    } elseif ($pagamento == "dinheiro") {
        $troco = (isset($troco)) ? number_format($troco, 2, ".", "") : 0;
    
        $objPedido->setTroco($troco);
        $objPedido->setPagamento(1);
    }

    $objPedido->setPrecoTotal($precototal);

    $idpedido = $objPedido->cadastrar()["idpedido"];


    require_once "../../classes/Carrinho.php";
    $objCarrinho = new Carrinho();


    $objCarrinho->setFkCliente($_SESSION["id"]);
    $itens_carrinho = $objCarrinho->selecionar(false);
    

    $objPedido_Detalhes->setFkPedido($idpedido);

    $obj = new Conexao();
    $conexao = $obj->conectar();

    foreach ($itens_carrinho as $item) {
        $objPedido_Detalhes->setFkProduto($item["fkproduto"]);
        $objPedido_Detalhes->setQtd($item["qtd"]);

        $query = "UPDATE produto SET estoque = estoque - :qtd WHERE idproduto = :fkproduto";
        $comando = $conexao->prepare($query);
        $comando->bindparam(":qtd", $item["qtd"]);
        $comando->bindparam(":fkproduto", $item["fkproduto"]);
        $comando->execute();

        $objPedido_Detalhes->cadastrar();

        $objCarrinho->setIdCarrinho($item["idcarrinho"]);
        $objCarrinho->remover();
    }


    header("Location: ../../pedidos");


