<?php
    session_start();
    
    if (isset($_SESSION["id"])) {
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
    <title>Digital Market</title>

    <!-- arquivo .css bootstrap -->
    <link rel="stylesheet" href="css/bootstrap/bootstrap.css"/>

    <!-- arquivo de icones da font awesome -->
    <link rel="stylesheet" href="font/css/all.css"/>

    <link rel="stylesheet" href="css/cores.css"/>
    
    <link rel="stylesheet" href="css/design.css"/>

    <link rel="stylesheet" href="css/listagem.css"/>
    
    <link rel="shortcut icon" href="img/dm.ico"/>

</head>
<body class="corpo">
    <div class="bg-escurecer invisivel" onclick="visibilidadeSidebar(false)"></div>

    <!-- navbar -->
    <nav class="nav-menu">
        <div class="nav-logo">
            <a href="" style="pointer-events: none;">
                <i class="fas fa-shopping-cart logo"></i>&nbsp;
                <span class="digitalmarket">Digital Market</span>
            </a>
        </div>

        <div class="nav-links">
            <?php
                if (!isset($pessoa["id"])) {
                    echo "<a href='cadastrar/mercado'>Tenha sua loja</a>";
                    // echo "<a href='cadastrar/entregador'>Seja entregador</a>";
                } else {
                    echo "<div style='height: 26px;'></div>";
                }
            ?>
        </div>

        <div class="nav-pesquisa">            
            <form action="resultados" method="get">
                <div id="combo-pesquisa" class="md-form input-group mb-3">
                    <input type="text" name="query" id="query" class="form-control txtpesquisa" placeholder="Procure por produtos, mercados, categorias..." aria-label="Recipient's username" aria-describedby="MaterialButton-addon2" autocomplete="off"/>
                        
                    <div class="input-group-append">
                        <button class="btn btn-md m-0 px-3 btnpesquisa" type="submit" id="MaterialButton-addon2"><i class="fas fa-search"></i></button>
                    </div>
                    <!--         
                    <div id="list-pesquisas" class="invisivel">

                    </div> -->
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
                                echo "<a href='pedidos'><i class='fas fa-truck'></i><span>&nbsp; Pedidos</span></a>";
                            
                            } else if ($_SESSION["tipo"] == 1) {
                                echo "<a href='mercado?id=". $_SESSION['id'] ."'><i class='fas fa-globe-americas'></i><span>&nbsp; Gerenciar</span></a>";        
                                echo "<a href='vendas'><i class='fas fa-shopping-cart'></i><span>&nbsp; Vender</span></a>";
                                echo "<a href='pedidos'><i class='fas fa-truck'></i><span>&nbsp; Pedidos</span></a>";                            
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



    <div class="pagina">
        
        <?php
            require_once "classes/Mercado.php";
            
            $objMercado = new Mercado();
            $mercados = $objMercado->selecionar();
        
        ?>

        
        <div class="menu-lateral" style="height: calc(450px + <?= "40px * ". count($mercados) ?>)">
            <h1 class="titulo-list procurar">Procurar</h1>

            <?php
                echo "<h2 class='subtitulo'>Mercados</h2>";

                $i = 1;
                foreach ($mercados as $mercado) {
                    $border = (count($mercados) == $i) ? "border-bottom: 0" : "";
                    echo "<a href='mercado?id={$mercado["idmercado"]}' class='link-mercado' style='{$border}'><span>" .$mercado["nomefantasia"] ."</span></a>";
                    $i++;
                }

                require_once "classes/Categoria.php";
                $objCategoria = new Categoria();

                $categorias = $objCategoria->selecionar();


                echo "<h2 class='subtitulo'>Categorias</h2>";
            
                $i = 1;
                foreach ($categorias as $categoria) {
                    switch ($categoria["categoria"]) {

                        case "Alimentos":    $linkcategoria = "alimentos";        break;
                        case "Bebidas":      $linkcategoria = "bebidas";          break;
                        case "Carnes":       $linkcategoria = "carnes";           break;
                        case "Laticínios":     $linkcategoria = "laticinios";       break;
                        case "Limpeza":      $linkcategoria = "limpeza";          break;
                        case "Padaria":      $linkcategoria = "padaria";          break;
                        case "Perfumaria":   $linkcategoria = "perfumaria";       break;
                        case "Outros":       $linkcategoria = "outros";           break;
                    }

                    $border = (count($categorias) == $i) ? "border-bottom: 0" : "";
                    echo "<a href='categoria?c={$linkcategoria}' class='link-mercado' style='{$border}'><span>" .$categoria["categoria"] ."</span></a>";
                    $i++;
                }
            ?>
        </div>
             

        <div class="conteudo" style="padding-right: 30px;">
                    
            <?php    
                // LIST PRODUTOS

                require_once "classes/Produto.php";
                require_once "classes/Produto_Imagem.php";

                $objProduto = new Produto();
                $produtos = $objProduto->selecionar();

                echo "<h1 class='titulo-list'>Novidades</h1>";
                
                echo "<div class='list-produtos'>";
                
                    foreach ($produtos as $produto) {
                        $produto_imagem = new Produto_Imagem();
                        $produto_imagem->setFkProduto($produto["idproduto"]);
                        $produto_imagem = $produto_imagem->selecionar();        
            ?>                  
                        <div class="card">
                            <?php
                                if (isset($_SESSION["id"]) && $_SESSION["tipo"] == 0) 
                                    echo "<button class='btn-add-sacola' id='adicionar-produto-carrinho' onclick='adicionar(".$produto["idproduto"].")'><div><i class='fas fa-shopping-bag'></i></div></button>";        
                            ?>    

                            <a href="produto?idproduto=<?= $produto["idproduto"] ?>">
                                <div class="card-produto" onclick="location.href = 'produto?idproduto=<?= $produto['idproduto'] ?>'">
                                    <img class="card-produto-img" id="image-<?= $produto["idproduto"] ?>" src="uploads/mercado/<?= $produto["fkmercado"] ?>/produtos/<?= $produto_imagem[0]["imagem"] ?>" alt="<?= $produto["titulo"] ?>" onmouseenter="<?php if (isset($produto_imagem[1])) echo "atualizarImagemCard(this.id, '{$produto_imagem[1]["imagem"]}', {$produto['fkmercado']})"; ?>" onmouseleave="<?php if (isset($produto_imagem[1])) echo "atualizarImagemCard(this.id, '{$produto_imagem[0]["imagem"]}', {$produto['fkmercado']})"; ?>"/>

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


                // LIST MERCADOS
                
                echo "<h1 class='titulo-list'>Mercados</h1>";
                echo "<div class='list-mercados'>";

                foreach ($mercados as $mercado) {        
            ?>                  
                    <a href="mercado?id=<?= $mercado["idmercado"] ?>">
                        <div class="card-mercado">    
                            <img class="card-mercado-img" src="uploads/mercado/<?= $mercado["idmercado"] ?>/<?= $mercado["logo"] ?>" alt="<?= $mercado["nomefantasia"] ?>"/>
            
                            <div class="card-mercado-info">
                                <span class="card-sp-nomefantasia"><?= $mercado["nomefantasia"] ?></span><br/>

                            </div>
                            <button class="btn-visitar">
                                Visitar
                            </button>
                        </div>
                    </a>
            <?php
                }

                echo "</div>";

                
                function mudarCategoria($idcategoria) {
                    switch ($idcategoria) {
                        case 1:    return "alimentos";    break;
                        case 2:    return "bebidas";      break;
                        case 3:    return "carnes";       break;
                        case 4:    return "laticinios";   break;
                        case 5:    return "limpeza";      break;
                        case 6:    return "padaria";      break;
                        case 7:    return "perfumaria";   break;
                        case 8:    return "outros";       break;
                    }
                }

                // LIST CATEGORIAS

                echo "<h1 class='titulo-list'>Categorias</h1>";
                
                echo "<div class='list-categorias'>";

                    require_once "classes/Categoria_Imagem.php";
                    $objCategoria_Imagem = new Categoria_Imagem();
                    

                    foreach ($categorias as $categoria) { 
                        $objCategoria_Imagem->setFkCategoria($categoria["idcategoria"]);
                        $imagem_categoria = $objCategoria_Imagem->selecionar();
                        
                        $aleatorio = rand(0, (count($imagem_categoria) - 1));
            ?>
                        <a href="categoria?c=<?= mudarCategoria($categoria["idcategoria"]) ?>" class="card-categoria">
                            <img src="img/<?= $imagem_categoria[$aleatorio]["imagem"] ?>" class="card-img-categoria"/>
                            <span class="sp-categoria"><?= $categoria["categoria"] ?></span>

                        </a>
            <?php
                    }

                echo "</div>";

  
                if (isset($_SESSION["id"]) && isset($_SESSION["tipo"]) && $_SESSION["tipo"] == 0) {

                    require_once "classes/Pesquisas.php";

                    $objPesquisa = new Pesquisas();
                    $objPesquisa->setFkCliente($_SESSION["id"]);

                    $pesquisa = $objPesquisa->selecionar();


                    $objProduto = new Produto();
                    $objProduto->setTitulo($pesquisa["pesquisa"]);
                    $produtos = $objProduto->selecionar();


                    if (!empty($pesquisa) && !empty($produtos)) {
                        
                        echo "<h1 class='titulo-list'>Você pesquisou por &#34;{$pesquisa["pesquisa"]}&#34;</h1>";

                        echo "<div class='list-produtos'>";
                            
                            $objProduto_Imagem = new Produto_Imagem();
                            
                            foreach ($produtos as $produto) {
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
                                            <img class="card-produto-img" id="p-image-<?= $produto["idproduto"] ?>" src="uploads/mercado/<?= $produto["fkmercado"] ?>/produtos/<?= $produto_imagem[0]["imagem"] ?>" alt="<?= $produto["titulo"] ?>" onmouseenter="<?php if (isset($produto_imagem[1])) echo "atualizarImagemCard(this.id, '{$produto_imagem[1]["imagem"]}', {$produto['fkmercado']})"; ?>" onmouseleave="<?php if (isset($produto_imagem[1])) echo "atualizarImagemCard(this.id, '{$produto_imagem[0]["imagem"]}', {$produto['fkmercado']})"; ?>"/>

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
                    

                    require_once "classes/Visitas.php";
                    $objVisitas = new Visitas();

                    $objVisitas->setFkCliente($_SESSION["id"]);
                    $visitas = $objVisitas->selecionar();


                    if (count($visitas) > 0) {
                        
                        echo "<h1 class='titulo-list'>Produtos que você já visitou</h1>";

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


                }
            ?>



        </div>
    </div> 


    <!-- arquivo .js jquery -->
    <script src="js/jquery/jquery.js"></script>

    <!-- arquivo .js bootstrap -->
    <script src="js/bootstrap/bootstrap.js"></script>

    <script src="js/requisicoes.js"></script>
</body>
</html>

