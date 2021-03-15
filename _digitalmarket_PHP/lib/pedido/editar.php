<?php
    
    session_start();
        
    if (!isset($_SESSION["id"]) || !isset($_SESSION["tipo"]) || $_SESSION["tipo"] != 1 || !isset($_GET["idpedido"])) {
        echo "<script>window.history.go(-1)</script>"; 
        die;
    }

    require_once "../../classes/Pedido_Detalhes.php";
    $objPedido_Detalhes = new Pedido_Detalhes();

    $obj = new Conexao();
    $conexao = $obj->conectar();
    
    $query = "SELECT pd.iddetalhe AS 'codigo', pd.entregue AS 'status' FROM pedido p INNER JOIN pedido_detalhes pd ON p.idpedido = pd.fkpedido INNER JOIN produto pr ON pd.fkproduto = pr.idproduto WHERE pr.fkmercado = :fkmercado AND p.idpedido = :idpedido";
    $comando = $conexao->prepare($query);

    $comando->bindparam(":fkmercado", $_SESSION["id"]);
    $comando->bindparam(":idpedido", $_GET["idpedido"]);
    $comando->execute();

    $detalhes = $comando->fetchAll();

    foreach ($detalhes as $detalhe) {
        $objPedido_Detalhes->setIdDetalhe($detalhe["codigo"]);
        $status = ($detalhe["status"] > 2) ? $detalhe["status"] : $detalhe["status"] + 1;  
        $objPedido_Detalhes->setEntregue($status);
        $objPedido_Detalhes->editar();
    }

    header("Location: ../../pedidos");

    