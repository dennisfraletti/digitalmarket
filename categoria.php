<?php
    session_start();

    if (isset($_SESSION["id"])) {
        require_once "classes/Pessoa.php";
        $pessoa = new Pessoa();
        $pessoa->setId($_SESSION["id"]);
        $pessoa = $pessoa->selecionar();
    }

    $categorias = array (
        "nome" => array("Alimentos", "Bebidas", "Carnes", "Laticínios", "Limpeza", "Padaria", "Perfumaria", "Outros"),
        "link" => array("alimentos", "bebidas", "carnes", "laticinios", "limpeza", "padaria", "perfumaria", "outros")
    );

    if (!isset($_GET["c"])) {
        echo "<script>window.history.go(-1);</script>";
        die;
    }

    switch ($_GET["c"]) {

        case "alimentos":   $idcategoria = 1;      break;
        case "bebidas":     $idcategoria = 2;      break;
        case "carnes":      $idcategoria = 3;      break;
        case "laticinios":  $idcategoria = 4;      break;
        case "limpeza":     $idcategoria = 5;      break;
        case "padaria":     $idcategoria = 6;      break;
        case "perfumaria":  $idcategoria = 7;      break;
        case "outros":      $idcategoria = 8;      break;
        
        
        default:
            echo "<script>window.history.go(-1);</script>"; die;

            break;
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

    <style>
        .list-produtos,
        .list-categorias { margin-right: 30px !important; }

        @media screen and (max-width: 1000px) {
            .list-produtos,
            .list-categorias { margin-right: 3.5% !important; }
        }  
    </style>
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
                                require_once "classes/Carrinho";
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


        // LIST PRODUTOS

        require_once "classes/Produto.php";
        require_once "classes/Produto_Imagem.php";

        $objProduto = new Produto();
        $objProduto->setFkCategoria($idcategoria);
        $produtos = $objProduto->selecionar();

        echo "<h1 class='titulo-list'>{$categorias["nome"][$idcategoria - 1]}</h1>";
        
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


        // LIST CATEGORIAS

        echo "<h1 class='titulo-list'>Categorias</h1>";
        
        echo "<div class='list-categorias'>";

        $categorias = array (
            "nome" => array("Alimentos", "Bebidas", "Carnes", "Laticínios", "Limpeza", "Padaria", "Perfumaria", "Outros"),
            "link" => array("alimentos", "bebidas", "carnes", "laticinios", "limpeza", "padaria", "perfumaria", "outros")
        );
        
        
        for ($i = 0; $i < count($categorias["nome"]); $i++) {
            $aleatorio = rand(1, 5);
    ?>
            <a href="categoria?c=<?= $categorias["link"][$i] ?>" class="card-categoria">
                <img src="img/<?= $categorias["link"][$i] ."-". $aleatorio ?>.jpg" class="card-img-categoria"/>

                <span class="sp-categoria"><?= $categorias["nome"][$i] ?></span>
            </a>    
    <?php
        }

        echo "</div>";
    ?>




    <!-- arquivo .js jquery -->
    <script src="js/jquery/jquery.js"></script>

    <!-- arquivo .js bootstrap -->
    <script src="js/bootstrap/bootstrap.js"></script>

    <script src="js/requisicoes.js"></script>
</body>
</html>