<?php
    session_start();

    if (!isset($_SESSION["id"])) {
        echo "<script>window.history.go(-1)</script>"; 
        die;
    
    } else { 

        require_once "classes/Pessoa.php";
        $pessoa = new Pessoa();
        
        $pessoa->setId($_SESSION["id"]);
        $pessoa = $pessoa->selecionar();
    }
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    
    <title>
        <?php
            if ($_SESSION["tipo"] == 0)   echo "Meus pedidos ";
            
            elseif ($_SESSION["tipo"] == 1)   echo "Pedidos ";
        
        ?>
    
        | Digital Market
    
    </title>

    
    <!-- arquivo .css bootstrap -->
    <link rel="stylesheet" href="css/bootstrap/bootstrap.css"/>

    <!-- arquivo de icones da font awesome -->
    <link rel="stylesheet" href="font/css/all.css"/>

    <link rel="stylesheet" href="css/cores.css"/>
    
    <link rel="stylesheet" href="css/design.css"/>

    <link rel="stylesheet" href="css/pedidos.css"/>

    <link rel="shortcut icon" href="img/dm.ico"/>
</head>

<body class="corpo">

    <div class="bg-escurecer invisivel" onclick="visibilidadeSidebar(false)"></div>
    
    <!-- navbar -->
    <nav class="nav-menu">
        <div class="nav-logo">
            <a href="index">
                <i class="fas fa-shopping-cart logo"></i>&nbsp;
                <span class="digitalmarket">Digital Market</span>
            </a>
        </div>

        <div class="nav-links">
            <?php
                if (!isset($pessoa["id"]))   echo "<a href='cadastrar/mercado'>Tenha sua loja</a>";
                
                else   echo "<div style='height: 26px;'></div>";
            ?>
        </div>

        <div class="nav-pesquisa">
            <form action="resultados" method="get">
                <div class="md-form input-group mb-3">
                    <input type="text" name="query" class="form-control txtpesquisa" placeholder="Procure por produtos, mercados, categorias..." aria-label="Recipient's username" aria-describedby="MaterialButton-addon2" autocomplete="off" required>
                        
                    <div class="input-group-append">
                        <button class="btn btn-md m-0 px-3 btnpesquisa" type="submit" id="MaterialButton-addon2"><i class="fas fa-search"></i></button>
                    </div>
                </div>
            </form>
        </div>

        <div class="nav-entrar">
            <?php
                if (!isset($pessoa["id"])) {
            ?>
                    <div class="text-entrar">
                        <label>Bem-vindo :)</label><br/>
                        <a href="entrar/">Entre ou cadastre-se</a>
                    </div>

                    <a href="entrar/" class="ico-entrar"><i class="fas fa-sign-in-alt"></i></a>
            <?php

                } else {

                    $primeiro_nome = explode(" ", $pessoa["nome"]);

                    if (strlen($primeiro_nome[0]) > 12) 
                        $primeiro_nome[0] = mb_strimwidth($primeiro_nome[0], 0, 12, "...");
            ?>
                    <div class="text-perfil">
                        <a class="link-perfil">
                            Olá, <span><?= ucfirst($primeiro_nome[0]) ?></span>&nbsp;
                            <i class="fas fa-caret-down"></i>
                        </a>
                        
                        <a class="ico-entrar"><i class="fas fa-user"></i></a>
                    </div>

                    <div class="dropdown">
                        <?php
                            if ($_SESSION["tipo"] == 0) {
                                // echo "<a href='perfil'><i class='fas fa-user'></i><span>&nbsp; Perfil</span></a>";
                                // echo "<a href='desejos'><i class='fas fa-heart'></i><span>&nbsp; Desejos</span></a>";
                                // echo "<a href='pedidos'><i class='fas fa-truck'></i><span>&nbsp; Pedidos</span></a>";
                            
                            } else if ($_SESSION["tipo"] == 1) {
                                echo "<a href='mercado?id=". $_SESSION['id'] ."'><i class='fas fa-globe-americas'></i><span>&nbsp; Gerenciar</span></a>";        
                                echo "<a href='vendas'><i class='fas fa-shopping-cart'></i><span>&nbsp; Vender</span></a>";
                            }
                        ?>
                        
                        <!-- <a href=""><i class="fas fa-heart"></i><span>&nbsp; Favoritos</span></a> -->
                        <a href="lib/pessoa/sair"><i class="fas fa-sign-out-alt"></i><span>&nbsp; Sair</span></a>
                    </div>
            <?php
                }
            ?>
        </div>

        <div class="nav-sacola">
            <button id="btn-sacola" onclick="visibilidadeSidebar(true)">                            
                <?php
                    if ((isset($_SESSION["id"]) && $_SESSION["tipo"] == 0) || !isset($_SESSION["id"]))
                        echo "<i class='fas fa-shopping-bag'></i>";
                    else
                        echo "<i class='fas fa-bell'></i>";
                ?>
            </button>
        </div>
    </nav>

    <!-- <div class="sombra-nav"></div> -->

    <div class="espaco-nav"></div>
    

    
    <?php
        if ((isset($_SESSION["id"]) && $_SESSION["tipo"] == 0) || !isset($_SESSION["id"])) {
    ?>
            <!-- sidebar -->
            <nav class="sidebar side-sacola" id="sidebar">
                <div class="side-header">
                    <label class="side-text">
                        Sacola de compras <i class="fas fa-shopping-bag"></i>
                    </label>

                    <button id="btn-fechar" class="side-close" onclick="visibilidadeSidebar(false)">
                        &times;
                    </button>
                </div>

                <div class="side-body listagem-sacola">
                    <div class="itens" id="itens">

                    </div>  
                </div>

                <div class="side-footer">
                    <div class="side-total">
                        <?php
                            if (isset($_SESSION["id"]) && isset($_SESSION["tipo"]) && $_SESSION["tipo"] == 0) {
                                require_once "classes/Carrinho.php";
                                $objCarrinho = new Carrinho();
                    
                                $objCarrinho->setFkCliente($_SESSION["id"]);
                                $itens = $objCarrinho->selecionar(false);
            
                            
                                $visib = (count($itens) > 0) ? "" : "invisivel";

                                echo "<label class='side-label-total negrito {$visib}'>Total</label>";
                                    
                                $objCarrinho = new Carrinho();
                                $objCarrinho->setFkCliente($_SESSION["id"]);
                                $total = $objCarrinho->selecionar(true);
                                echo "<label class='side-label-valor {$visib}'>R$". number_format($total["soma"], 2, ",", ".") ."</label>";             
                                
                            }
                        ?>
                    </div>

                    <div class="side-finalizar-pedido">
                        <?php
                            $disabled = (!isset($_SESSION["id"]) || !isset($itens) || count($itens) == 0) ? "disabled" : "";
                        ?>
                        <button class="side-botao" onclick="location.href = 'sacola';" <?= $disabled ?>>Finalizar Pedido</button>
                    </div>
                </div>
            </nav>
    <?php
        } elseif (isset($_SESSION["id"]) && $_SESSION["tipo"] == 1) {
    ?>
            <!-- sidebar -->
            <nav class="sidebar side-sacola" id="sidebar">
                <div class="side-header">
                    <label class="side-text">
                        Pedidos &nbsp;<i class="fas fa-truck"></i>
                    </label>

                    <button id="btn-fechar" class="side-close" onclick="visibilidadeSidebar(false)">
                        &times;
                    </button>
                </div>

                <div class="side-body listagem-sacola">
                    <?php
                        $obj = new Conexao();
                        $conexao = $obj->conectar();

                        $query = "SELECT * FROM pedido_detalhes pd INNER JOIN pedido p ON pd.fkpedido = p.idpedido INNER JOIN produto pr ON pr.idproduto = pd.fkproduto WHERE pr.fkmercado = :fkmercado GROUP BY p.idpedido ORDER BY p.idpedido DESC";
                        $comando = $conexao->prepare($query);
                 
                        $comando->bindparam(":fkmercado", $_SESSION["id"]);
                        $comando->execute();
                 
                        $resultados = $comando->fetchAll();

                        if (count($resultados) == 0) {
                            echo "<span class='side-text-aviso'>Nenhum pedido até o momento! <br/> :(</span>";
                            
                        } else {
                        
                            require_once "classes/Cliente.php";
                            $objCliente = new Cliente();

                            require_once "classes/Pedido_Detalhes.php";
                            $objPedido_Detalhes = new Pedido_Detalhes();
                           
                            $i = 1;
                            foreach ($resultados as $resultado) {
                                
                                $objCliente->pessoa->setId($resultado["fkcliente"]);
                                $cliente = $objCliente->selecionar();          
                    
                    ?>
                                <div class="item-pedido" style="<?php if ($i == count($resultados)) echo 'border-bottom: 0;'; ?>">
                                    <span class="cliente-pedido">
                                        <?= $cliente["nome"] ?>
                                        <span>
                                            <?php
                                                $listdata = explode(" ", $resultado["data_pedido"]);
                                                $listDia = explode("-", $listdata[0]);
                                                $listHora = explode(":", $listdata[1]);

                                                echo $listDia[2] . "/" . $listDia[1] . " às " . $listHora[0] . ":" . $listHora[1];
                                            ?>

                                        </span>
                                    </span>
                                    <span class="endereco-pedido">
                                        <?php
                                            echo "CEP: " . $cliente["cep"].", ".$cliente["rua"].", ".$cliente["numero"].", ".$cliente["bairro"].", ".$cliente["cidade"]." - ".$cliente["estado"]; 
                                            if (!empty($cliente["complemento"])) echo ", ". $cliente["complemento"]; 
                                        ?>
                                    </span>
                                </div>
                    <?php
                                $i++;
                            }
                        }
                    ?>
                </div>

                <div class="side-footer">
                    <div class="side-total"></div>

                    <div class="side-finalizar-pedido">
                        <button class="side-botao" onclick="location.href = 'pedidos';">Ver pedidos</button>
                    </div>
                </div>
            </nav>
    <?php        
        }
    ?>

    <div class="conteudo-pedidos">
        <?php
            require_once "classes/Pedido.php";
            $objPedido = new Pedido();

            require_once "classes/Pedido_Detalhes.php";
            $objPedido_Detalhes = new Pedido_Detalhes();


            require_once "classes/Produto.php";
            $objProduto = new Produto();


            require_once "classes/Produto_Imagem.php";
            $objProduto_Imagem = new Produto_Imagem();


            require_once "classes/Mercado.php";
            $objMercado = new Mercado();
            

            if ($_SESSION["tipo"] == 0) {

                $objPedido->setFkCliente($_SESSION["id"]);
                $pedidos = $objPedido->selecionar();


                if (count($pedidos) == 0) {
        ?>
                    <h1 class="pedido-vazio">Nenhum pedido! </h1>

                    <span class="adicione">Que pena, você ainda não tem nenhum pedido no Digital Market.</span>
                    
                    <a href="/digitalmarket/" class="voltar-compras"><i class="fas fa-chevron-left"></i>&nbsp;&nbsp; Fazer compras.</a>

        <?php
                
                } else {
                    
                    echo "<h1 class='titulo-pedidos'>Seus pedidos</h1>";


                    $j = 1;
                    foreach ($pedidos as $pedido) {
        ?>
                        <div class="container-tabela" style="<?php if ($j == count($pedidos)) echo 'margin-bottom: 0 !important;' ?>">
                            <table class="tabela">
                                <thead>
                                    <tr class="tabela-linha">
                                        <td class="cabecalho-coluna col-1">
                                            <span>PEDIDO REALIZADO</span><br/> 
                                            
                                            <?php                                    
                                                $meses = array("janeiro", "fevereiro", "março", "abril", "maio", "junho", "julho", "agosto", "setembro", "outubro", "novembro", "dezembro");
                                                $listdata = explode("-", $pedido["data_pedido"]);
                                                $listDia = explode(" ", $listdata[2]);
                                                $listHora = explode(":", $listDia[1]);

                                                echo $listDia[0] . " de " . $meses[$listdata[1] - 1] . " de " . $listdata[0] . " às " . $listHora[0] . ":". $listHora[1];
                                            ?>
                                        </td>

                                        <td class="cabecalho-coluna col-2">
                                            <span>TOTAL</span><br/> 
                                            <?= "R$ ". number_format($pedido["precototal"], 2, ",", ".") ?>
                                        </td>

                                        <td class="cabecalho-coluna col-3">
                                            <span>ENVIAR PARA</span><br/> 
                                            <?= $pessoa["nome"] ?>
                                        </td>
                                        
                                        <td class="cabecalho-coluna col-4">
                                            <span>CÓDIGO PEDIDO</span><br/> 
                                            <?= $pedido["idpedido"] ?>
                                        </td>
                                    </tr>
                                </thead>

                            </table>

                            <?php
                                $objPedido_Detalhes->setFkPedido($pedido["idpedido"]);
                                $detalhes = $objPedido_Detalhes->selecionar();

                                $i = 1;
                                foreach ($detalhes as $detalhe) {

                                    $objProduto->setIdProduto($detalhe["fkproduto"]);
                                    $produto = $objProduto->selecionar();

                                    $objProduto_Imagem->setFkProduto($detalhe["fkproduto"]);
                                    $imagem = $objProduto_Imagem->selecionar()[0]["imagem"];


                                    $objMercado->pessoa->setId($produto["fkmercado"]);
                                    $mercado = $objMercado->selecionar();
                            ?>
                                    <div class="item-produto" style="<?php if ($i == count($detalhes)) echo 'border-bottom: 0;' ?>">
                                        <a href="produto?idproduto=<?= $produto["idproduto"] ?>" class="link-img">
                                            <img src="uploads/mercado/<?= $produto["fkmercado"] ?>/produtos/<?= $imagem ?>" alt="<?= $produto["titulo"] ?>" class="imagem-produto"/>
                                        </a>
                                        <div class="informacoes-produto">
                                            <span class="titulo-produto">
                                                <a href="produto?idproduto=<?= $produto["idproduto"] ?>"><?= $produto["titulo"] ?> </a><?= "(".$detalhe["qtd"].")" ?><br/>
                                            </span>
    
                                            <span class="mercado-produto">
                                                Vendido por    
                                                <a href="mercado?id=<?= $mercado["idmercado"] ?>"><?= $mercado["nomefantasia"]; ?></a><br/>
                                            </span>

                                            <div class="precos">
                                                <?php 
                                                    if ($detalhe["qtd"] > 1) {
                                                        echo "<span class='preco-unidade'>R$ ". number_format($produto["precodesconto"], 2, ",", "."). " un.</span>"; 
                                                        echo "<span class='preco-total'>R$ ". number_format($produto["precodesconto"] * $detalhe["qtd"], 2, ",", ".")."</span>";
                                                    } else 
                                                        echo "<span class='preco-total'>R$ ". number_format($produto["precodesconto"], 2, ",", "."). " un.</span>";
                                                ?>
                                            </div>
                                            
                                            <div class="status">
                                                <?php
                                                    if ($detalhe["entregue"] == 0) {
                                                        echo "<i class='fas fa-dollar-sign' style='transform: translateX(-35%)'></i><br/>";
                                                        echo "<span class='status-entrega' style='transform: translateX(-35%); cursor: default;'>Pago</span>";

                                                    } elseif ($detalhe["entregue"] == 1) {
                                                        echo "<i class='fas fa-truck' style='transform: translateX(12%)'></i><br/>";  
                                                        echo "<span class='status-entrega' style='transform: translateX(12%); cursor: default;'>A caminho</span>";
                                                    
                                                    } elseif ($detalhe["entregue"] == 2) {
                                                        echo "<i class='fas fa-box-open' style='transform: translateX(1%)'></i><br/>";  
                                                        echo "<i class='fas fa-check-circle ok' style='transform: translateX(1%)'></i>";
                                                        echo "<span class='status-entrega' style='transform: translateX(%); cursor: default;'>Entregue</span>";
                                                    }
                                                ?>    
                                            </div>

                                        </div>
                                    </div>    
                            <?php

                                    $i++;
                                }
                            ?>
                        </div>
        <?php   
                        $j++;
                    }
                }
            

            } elseif ($_SESSION["tipo"] == 1) {
                

                require_once "classes/Conexao.php";
                $obj = new Conexao();
                $conexao = $obj->conectar();


                $query = "SELECT * FROM pedido_detalhes pd INNER JOIN pedido p ON pd.fkpedido = p.idpedido INNER JOIN produto pr ON pr.idproduto = pd.fkproduto WHERE pr.fkmercado = :fkmercado GROUP BY p.idpedido ORDER BY p.idpedido DESC";
                $comando = $conexao->prepare($query);
        
                $comando->bindparam(":fkmercado", $_SESSION["id"]);
                $comando->execute();
        
                $resultados = $comando->fetchAll();

                if (count($resultados) == 0) {
        ?>
                    <h1 class="pedido-vazio">Nenhum pedido ainda! </h1> <br/>

                    <span class="adicione">Que pena, ainda não há nenhum pedido para o seu mercado. Espere mais um pouco.</span>
        <?php
                
                } else {
                    
                    echo "<h1 class='titulo-pedidos'>Encomendas</h1>";

                    
                    require_once "classes/Cliente.php";
                    $objCliente = new Cliente();

                    $i = 1;
                    foreach ($resultados as $resultado) {   
                        
                        $objCliente->pessoa->setId($resultado["fkcliente"]);        
                        $cliente = $objCliente->selecionar();

        ?>
                        <div class="container-tabela" style="<?php if ($i == count($resultados)) echo 'margin-bottom: 0 !important;' ?>">
                            <table class="tabela">
                                <thead>
                                    <tr class="tabela-linha">
                                        <td class="cabecalho-coluna dol-1">
                                            <span>PEDIDO FEITO POR</span><br/> 
                                            <?= $cliente["nome"]; ?>
                                        </td>

                                        <td class="cabecalho-coluna dol-2">
                                            <span>PEDIDO REALIZADO</span><br/> 
                                                
                                            <?php                                                                          
                                                $meses = array("janeiro", "fevereiro", "março", "abril", "maio", "junho", "julho", "agosto", "setembro", "outubro", "novembro", "dezembro");
                                                $listdata = explode("-", $resultado["data_pedido"]);
                                                $listDia = explode(" ", $listdata[2]);
                                                $listHora = explode(":", $listDia[1]);

                                                echo $listDia[0] . " de " . $meses[$listdata[1] - 1] . " de " . $listdata[0] . " às " . $listHora[0] . ":". $listHora[1];
                                            ?>
                                        </td>

                                        <td class="cabecalho-coluna dol-3">
                                            <span>CÓDIGO PEDIDO</span><br/> 
                                            <?= $resultado["idpedido"] ?>
                                        </td>

                                        <td class="cabecalho-coluna dol-4">
                                            <a class="link-status">
                                                
                                                <?php                                             
                                                    $obj = new Conexao();
                                                    $conexao = $obj->conectar();
                                                    
                                                    $query = "SELECT pd.entregue FROM pedido_detalhes pd INNER JOIN produto pr ON pd.fkproduto = pr.idproduto WHERE pd.fkpedido = :fkpedido AND pr.fkmercado = :fkmercado GROUP BY pd.entregue";
                                                    $comando = $conexao->prepare($query);
                                                    $comando->bindparam(":fkpedido", $resultado["idpedido"]);
                                                    $comando->bindparam(":fkmercado", $_SESSION["id"]);
                                                    $comando->execute();

                                                    $entregue = $comando->fetch()["entregue"];
                                                    
                                                    if ($entregue == 0) {
                                                        echo "<a href='lib/pedido/editar?idpedido={$resultado["idpedido"]}' class='link-status'>";      
                                                            echo "<i class='fas fa-dollar-sign' style='transform: translateX(15%)'></i><br/>";
                                                            echo "<span class='status-entrega' style='transform: translateX(15%)'>Pago</span>";
                                                        echo "</a>";

                                                    
                                                    } elseif ($entregue == 1) {
                                                        echo "<a href='lib/pedido/editar?idpedido={$resultado["idpedido"]}' class='link-status'>";
                                                            echo "<i class='fas fa-truck' style='transform: translateX(12%)'></i><br/>";  
                                                            echo "<span class='status-entrega' style='transform: translateX(12%)'>A caminho</span>";
                                                        echo "</a>";
                                                    
                                                    
                                                    } elseif ($entregue == 2) {
                                                        echo "<a class='link-status'>";
                                                            echo "<i class='fas fa-box-open' style='transform: translateX(1%)'></i><br/>";  
                                                            echo "<i class='fas fa-check-circle ok' style='right: 21px;'></i>";
                                                            echo "<span class='status-entrega' style='transform: translateX(%); cursor: default;'>Entregue</span>";
                                                        echo "</a>";
                                                    }
                                                ?>    
                                            </a>
                                        </td>

                                    </tr>
                                </thead>

                            </table>

                            <?php
                                $objPedido_Detalhes->setFkPedido($resultado["idpedido"]);
                                $detalhes = $objPedido_Detalhes->selecionar();

                                $j = 1;
                                foreach ($detalhes as $detalhe) {

                                    $objProduto->setIdProduto($detalhe["fkproduto"]);
                                    $produto = $objProduto->selecionar();

                                    if ($produto["fkmercado"] == $_SESSION["id"]) {
                                        $objProduto_Imagem->setFkProduto($detalhe["fkproduto"]);
                                        $imagem = $objProduto_Imagem->selecionar()[0]["imagem"];
    
                                        $objMercado->pessoa->setId($produto["fkmercado"]);
                                        $mercado = $objMercado->selecionar();
                                        
                                    

                            ?>
                                        <div class="item-produto" style="<?php if ($j == count($detalhes)) echo 'border-bottom: 0;' ?>">
                                            <a href="produto?idproduto=<?= $produto["idproduto"] ?>" class="link-img">
                                                <img src="uploads/mercado/<?= $produto["fkmercado"] ?>/produtos/<?= $imagem ?>" alt="<?= $produto["titulo"] ?>" class="imagem-produto"/>
                                            </a>
                                            <div class="informacoes-produto">
                                                <span class="titulo-produto">
                                                    <a href="produto?idproduto=<?= $produto["idproduto"] ?>"><?= $produto["titulo"] ?> </a><?= "(".$detalhe["qtd"].")" ?><br/>
                                                </span>

                                                <div class="precos">
                                                    <?php 
                                                        if ($detalhe["qtd"] > 1) {
                                                            echo "<span class='preco-unidade'>R$ ". number_format($produto["precodesconto"], 2, ",", "."). " un.</span>"; 
                                                            echo "<span class='preco-total'>R$ ". number_format($produto["precodesconto"] * $detalhe["qtd"], 2, ",", ".")."</span>";
                                                        } else 
                                                            echo "<span class='preco-total'>R$ ". number_format($produto["precodesconto"], 2, ",", "."). " un.</span>";
                                                    ?>
                                                </div>
                                            </div>
                                        </div>    
                            <?php
                                    }
                                    
                                    $j++;
                                }
                            ?>
                            
                            <div class="dados-pedido">
                                <span class="pedido-endereco">
                                    <span class="abc">ENDEREÇO</span><br/>

                                    <span class="bcd">
                                        <?php
                                            $objCliente = new Cliente();
                                            $objCliente->pessoa->setId($resultado["fkcliente"]);

                                            $cliente = $objCliente->selecionar();


                                            echo "CEP: " . $cliente["cep"].", ".$cliente["rua"].", ".$cliente["numero"].", ".$cliente["bairro"].", ".$cliente["cidade"]." - ".$cliente["estado"]; 
                                            if (!empty($cliente["complemento"])) echo ", ". $cliente["complemento"]; 
            
                                        ?>
                                    </span>
                                </span>
                            </div>
                        </div>

        <?php
                        $i++;
                    }
                }
            }
        ?>
    </div>

    
    <hr class="traco"/>


    <?php
        require_once "classes/Visitas.php";
        $objVisitas = new Visitas();

        $objVisitas->setFkCliente($_SESSION["id"]);
        $visitas = $objVisitas->selecionar();


        if (count($visitas) > 0) {
            
            echo "<h1 class='titulo-list'>Produtos que você já viu</h1>";

            echo "<div class='list-produtos'>";

                $objProduto = new Produto();
                $objProduto_Imagem = new Produto_Imagem();
        
                foreach ($visitas as $visita) {
                    $objProduto->setIdProduto($visita["fkproduto"]);
                    $produto = $objProduto->selecionar();

                    $objProduto_Imagem->setFkProduto($produto["idproduto"]);
                    $produto_imagem = $objProduto_Imagem->selecionar();     
    ?>                  
                    <div class="card">
                        <?php
                            if (isset($_SESSION["id"]) && $_SESSION["tipo"] == 0) 
                                echo "<button class='btn-add-sacola' id='adicionar-produto-carrinho' onclick='adicionar(".$produto["idproduto"].")'><div><i class='fas fa-shopping-bag'></i></div></button>";        
                        ?>    

                        <a href="produto?idproduto=<?= $produto["idproduto"] ?>">
                            <div class="card-produto" onclick="location.href = 'produto?idproduto=<?= $produto['idproduto'] ?>'">
                                <img class="card-produto-img" id="s-image-<?= $produto["idproduto"] ?>" src="uploads/mercado/<?= $produto["fkmercado"] ?>/produtos/<?= $produto_imagem[0]["imagem"] ?>" alt="<?= $produto["titulo"] ?>" onmouseenter="<?php if (isset($produto_imagem[1])) echo "atualizarImagemCard(this.id, '{$produto_imagem[1]["imagem"]}', {$produto['fkmercado']})"; ?>" onmouseleave="<?php if (isset($produto_imagem[1])) echo "atualizarImagemCard(this.id, '{$produto_imagem[0]["imagem"]}', {$produto['fkmercado']})"; ?>"/>

                                <div class="card-produto-info">
                                    <span class="card-sp-titulo"><?= $produto["titulo"] ?></span><br/>

                                    <div class="precos">
                                        <span class="card-sp-preco">
                                            <?php
                                                if ($produto["precodesconto"] != $produto["preco"]) echo "<span class='card-sp-desconto'>R$" . number_format($produto["preco"], 2, ',', '.') . "</span><br/>";
                                                
                                                $exibir = ($produto["precodesconto"] != $produto["preco"]) ? $produto["precodesconto"] : $produto["preco"];
                                                echo "<span>R$". number_format($exibir, 2, ',', '.')."</span>";
                                            ?>
                                        </span>
                                    </div>
                                </div>
                            </div>      
                        </a>
                    </div>
    <?php
                }

            echo "</div>";
        }
    ?>
    


    <!-- arquivo .js jquery -->
    <script src="js/jquery/jquery.js"></script>

    <!-- arquivo .js bootstrap -->
    <script src="js/bootstrap/bootstrap.js"></script>

    <script src="js/requisicoes.js"></script>
</body>
</html>