<?php
    session_start();

    
    if (!isset($_GET["idproduto"]) || empty($_GET["idproduto"]) || !is_numeric($_GET["idproduto"])) {
        echo "<script>window.history.go(-1)</script>";     
        die;
    }
    
    require_once "classes/Produto.php";
    $produto = new Produto();
    $produto->setIdProduto($_GET["idproduto"]);
    $produto = $produto->selecionar();
    
    if (empty($produto)) {
        echo "<script>window.history.go(-1)</script>";     
        die;
    }

    if (isset($_SESSION["id"])) {
        require_once "classes/Pessoa.php";
        $pessoa = new Pessoa();
        $pessoa->setId($_SESSION["id"]);
        $pessoa = $pessoa->selecionar();
    }

    if (isset($_SESSION["id"]) && isset($_SESSION["tipo"]) && $_SESSION["tipo"] == 0) {
        require_once "classes/Visitas.php";
        $visitas = new Visitas();

        $visitas->setFkCliente($_SESSION["id"]);
        $visitas->setFkProduto($produto["idproduto"]);

        $qtd = $visitas->selecionar()["qtd"];

        if ($qtd == 0)
            $visitas->cadastrar();    
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

    <link rel="stylesheet" href="css/produto.css"/>

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

    <div class="sombra-nav"></div>

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
        
        require_once "classes/Produto_Imagem.php";
        $produto_imagem = new Produto_Imagem();
        $produto_imagem->setFkProduto($_GET["idproduto"]);
        $produto_imagem = $produto_imagem->selecionar();
    ?>
    
    <div class="conteudo-prod">
        <div class="perfil-prod">
            <div class="perfil-prod-esquerda">
                <img src="uploads/mercado/<?= $produto["fkmercado"] ?>/produtos/<?= $produto_imagem[0]["imagem"] ?>" alt="<?= $produto["titulo"] ?>" class="img-prod"/>
                
                <div class="list-imagens">
                    <?php
                        foreach ($produto_imagem as $imagem) {
                    ?>
                            <img src="uploads/mercado/<?= $produto["fkmercado"] ?>/produtos/<?= $imagem["imagem"] ?>" class="item-img" onclick="atualizarImagem(<?= $produto['fkmercado'] ?>, '<?= $imagem['imagem'] ?>');"/>
                    <?php    
                        }
                    ?>
                </div>
            </div>

            <div class="perfil-prod-direita">
                <span class="sp-titulo"><?= $produto["titulo"] ?></span></br>

                <?php
                    if (isset($_SESSION["id"]) && $_SESSION["id"] == $produto["fkmercado"]) {
                ?>
                        <form action="lib/produto/editar?idproduto=<?= $produto["idproduto"] ?>" method="post" style="position: relative;">
                            <button type="button" id="fechar-desc" class="invisivel" onclick="adicionarDescricao(false);">&times;</button>
                            <span class="add-desc" ondblclick="adicionarDescricao(true);">
                                <?php
                                    if (empty($produto["descricao"])) 
                                        echo "Clique duas vezes para adicionar descrição ao produto";
                                    else
                                        echo $produto["descricao"];    
                                ?>    
                            </span>
                            
                            <textarea name="descricao" id="descricao" maxlength="120" class="invisivel" onkeyup="atualizarQtdInput('atualizar-qtd', this.value);" onkeydown="atualizarQtdInput('atualizar-qtd', this.value);"><?php if (!empty($produto["descricao"])) echo $produto["descricao"] ?></textarea>

                            <div id="qtd-caracteres" class="invisivel"><span id="atualizar-qtd">0</span> / 120</div>

                            <button type="submit" id="salvar-desc" class="invisivel">Salvar</button>

                            <div id="espaco" class="invisivel"></div>
                        </form>

                <?php
                    } elseif (!empty($produto["descricao"]))
                        echo "<span class='add-desc' style='cursor: text;'> {$produto["descricao"]} </span>";


                    require_once "classes/Mercado.php";
                    
                    $categorias = array("Alimentos", "Bebidas", "Carnes", "Laticínios", "Limpeza", "Padaria", "Perfumaria", "Outros");
                    $mercado = new Mercado();
                    $mercado->pessoa->setId($produto["fkmercado"]);
                    $mercado = $mercado->selecionar();

                    switch ($produto["fkcategoria"]) {
                        case 1:  $categoria = "alimentos";      break;
                        case 2:  $categoria = "bebidas";        break;
                        case 3:  $categoria = "carnes";         break;
                        case 4:  $categoria = "laticinios";     break;
                        case 5:  $categoria = "limpeza";        break;
                        case 6:  $categoria = "padaria";        break;
                        case 7:  $categoria = "perfumaria";     break;
                        case 8:  $categoria = "outros";         break;
                    
                    }
                ?>

                <span class="sp-mercado">Vendido por <a href="mercado?id=<?= $produto["fkmercado"] ?>"><?= $mercado["nomefantasia"] ?></a></span><br/>

                <div style="height: 7px;"></div>

                <a href="categoria?c=<?= $categoria ?>" class="span-categoria"><?= $categorias[$produto["fkcategoria"] - 1] ?></a>

                <?php
                    if ($produto["estoque"] <= 25)
                        echo "<span class='sp-estoque'><i class='fas fa-exclamation-circle'></i>&nbsp; Restam apenas {$produto["estoque"]} em estoque! </span>";

                    if (isset($_SESSION["id"]) && $_SESSION["id"] == $produto["fkmercado"])
                        echo "<a href='vendas?idproduto={$produto["idproduto"]}' class='link-editar'><i class='fas fa-edit'></i><span>&nbsp; Editar produto</span></a>";
                ?>

                <span style="display: block; height: 175px;"></span>
                
                <div class="confirmar-baixo">
                    <?php
                        if ($produto["precodesconto"] != $produto["preco"]) {
                            $desconto = round((($produto["preco"] - $produto["precodesconto"]) * 100) / $produto["preco"]);
                            echo "<span class='sp-preco'>R$".number_format($produto["preco"], 2, ",", ".")."</span>";
                            echo "<span class='sp-desconto'><i class='fas fa-arrow-down'></i> {$desconto}%</span></br>";
                        } 
                    ?>
                            
                    <span class="sp-precofinal">
                        <?php
                            $valor = ($produto["precodesconto"] == $produto["preco"]) ? $produto["preco"] : $produto["precodesconto"];
                            echo "R$". number_format($valor, 2, ",", ".");
                        ?>
                    </span>


                    <?php
                        $disabled = (!isset($_SESSION["id"]) || $_SESSION["tipo"] != 0) ? "disabled" : "";
                        echo "<button class='btn-comprar' id='adicionar-produto-carrinho' onclick='adicionar({$produto["idproduto"]});' {$disabled}>Comprar</button>";
                    ?>

                </div>

            </div>

        </div>


    </div>


    <!-- arquivo .js jquery -->
    <script src="js/jquery/jquery.js"></script>

    <!-- arquivo .js bootstrap -->
    <script src="js/bootstrap/bootstrap.js"></script>

    <script src="js/requisicoes.js"></script>

    <script src="js/produto.js"></script>
</body>
</html>