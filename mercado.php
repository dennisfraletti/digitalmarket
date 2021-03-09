<?php
    session_start();

    if (!isset($_GET["id"]) || empty($_GET["id"])) {
        header("Location: /digitalmarket/");
        die;
    }
    
    if (isset($_SESSION["id"])) {
        require_once "classes/Pessoa.php";
        $pessoa = new Pessoa();
        $pessoa->setId($_SESSION["id"]);
        $pessoa = $pessoa->selecionar();
    }

    require_once "classes/Mercado.php";
    $mercado = new Mercado();
    $mercado->pessoa->setId($_GET["id"]);
    $mercado = $mercado->selecionar();

    if (empty($mercado)) {
        header("Location: /digitalmarket/");
        die;
    }
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title><?= ucfirst($mercado["nomefantasia"]) ?> | Digital Market</title>

    <!-- arquivo .css bootstrap -->
    <link rel="stylesheet" href="css/bootstrap/bootstrap.css"/>

    <!-- arquivo de icones da font awesome -->
    <link rel="stylesheet" href="font/css/all.css"/>

    <link rel="stylesheet" href="css/cores.css"/>
    
    <link rel="stylesheet" href="css/design.css"/>

    <link rel="stylesheet" href="css/mercado.css"/>

    <link rel="stylesheet" href="css/radio.css">

    <link rel="shortcut icon" href="img/dm.ico"/>

    <style>
        .list-produtos { margin-right: 30px !important; }

        @media screen and (max-width: 1000px) {
            .list-produtos { margin-right: 3.5% !important; }
        }        
    </style>
</head>
<body class="corpo">
    <?php 
        if (isset($_SESSION["id"]) && $_SESSION["id"] == $_GET["id"])
            echo "<div class='bg-alvorecer invisivel' onclick='visibilidadeModalPerfil(false);'></div>";
    ?>

    <div class="bg-escurecer invisivel" onclick="visibilidadeSidebar(false);"></div>
    
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
                
                } else
                    echo "<div style='height: 26px;'></div>";
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
                                // echo "<a href='mercado?id=". $_SESSION['id'] ."'><i class='fas fa-globe-americas'></i><span>&nbsp; Gerenciar</span></a>";        
                                echo "<a href='vendas'><i class='fas fa-shopping-cart'></i><span>&nbsp; Vender</span></a>";
                                echo "<a href='pedidos'><i class='fas fa-truck'></i><span>&nbsp; Pedidos</span></a>";                            
                            }
                        ?>
                        
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
    ?>
    
    <br/>

    
    <div class="conteudo-mercado">
        <div class="perfil" style="width: 100% !important; max-width: 100% !important;">
            <img src="uploads/mercado/<?= $_GET['id'].'/'.$mercado['logo']?>" alt="<?= $mercado['logo'] ?>" class="logo-mercado">
                
            <span class="nomefantasia"><?= $mercado["nomefantasia"] ?></span>

            <?php
                $meses = array("Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho", "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro");
                $ano = explode("-", $mercado["cadastro"])[0];
                $mes = explode("-", $mercado["cadastro"])[1];
            ?>

            <span class="entrou-dm">Entrou em <?= $meses[$mes - 1]." de ". $ano?></span>
        
            <?php
                if (isset($_SESSION["id"]) && $_SESSION["id"] == $_GET["id"]) 
                    echo "<button class='btn-editar' onclick='visibilidadeModalPerfil(true)'>Editar Perfil</button>";   
            ?>
        </div>
    
        <div class="sobre">
            <h1 class="titulo-sobre">Sobre <?= $mercado["nomefantasia"] ?></h1>

            <?php
                if (!empty($mercado["bio"]))
                    echo "<span class='bio'>{$mercado["bio"]}</span>";
            ?>

            <div class="infos-mercado">
                <div>
                    <span id="sp">
                        <span class="titulo-info">CNPJ: </span>
                        <?= $mercado["cnpj"] ?>
                    </span>
    
                    <span id="sp">
                        <span class="titulo-info">Razão Social: </span>
                        <?= $mercado["razaosocial"] ?>
                    </span>
                </div>

                <div>
                    <span id="sp">
                        <?php
                            require_once "classes/Endereco.php";

                            $endereco = new Endereco();
                            $endereco = $endereco->selecionar($_GET["id"]);

                        ?>
                        <span class="titulo-info">Endereco: </span>
                    
                        <?php 
                            echo $endereco["cep"].", ".$endereco["rua"].", ".$endereco["numero"].", ".$endereco["bairro"].", ".$endereco["cidade"]." - ".$endereco["estado"]; 
                            if(!empty($endereco["complemento"])) echo ", ". $endereco["complemento"]; 
                        ?>
                    
                    </span>
                </div>
            </div>

            <?php
                if (isset($_SESSION["id"]) && $_SESSION["id"] == $_GET["id"]) {
            ?>
                    <button class="add-produto" id="add-produto" onclick="location.href = 'vendas';">
                        Gerenciar produtos
                    </button>
            <?php
                }
            ?>
        </div>
    </div>    
    

    <?php
        require_once "classes/Produto.php";
        require_once "classes/Produto_Imagem.php";

        $produto = new Produto();
        $produto->setFkMercado($_GET["id"]);
        $produtos = $produto->selecionar();

        if (empty($produtos))
            echo "<h1 class='titulo-sobre vendidopela' style='font-size: 1.5rem'>Ainda não há produtos vendidos por este mercado</h1>";
        else
            echo "<h1 class='titulo-sobre vendidopela'>Produtos vendidos por este mercado</h1>";
    ?>

    <div class="list-produtos">
        
        <?php    
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
                            <img class="card-produto-img" id="image-<?= $produto["idproduto"] ?>" src="uploads/mercado/<?= $_GET["id"] ?>/produtos/<?= $produto_imagem[0]["imagem"] ?>" alt="<?= $produto["titulo"] ?>" onmouseenter="<?php if (isset($produto_imagem[1])) echo "atualizarImagemCard(this.id, '{$produto_imagem[1]["imagem"]}', {$_GET["id"]})"; ?>" onmouseleave="<?php if (isset($produto_imagem[1])) echo "atualizarImagemCard(this.id, '{$produto_imagem[0]["imagem"]}', {$_GET["id"]})"; ?>"/>
        
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
        ?>
    
    </div>














    <?php
        if (isset($_SESSION["id"]) && $_SESSION["id"] == $_GET["id"]) {
    ?>
            <!-- modal editar só deve existir se o usuário for o proprio dono do mercado -->
            
            <div class="modal-editar-perfil">

                <button class="fechar-editar" onclick="visibilidadeModalPerfil(false)">&times;</button>

                <div class="conteudo-modal">

                    <nav class="itens-edit">
                        <li id="li-0" class="item-selected"><button onclick="atualizarModal(0);">Editar mercado</button></li>
                        
                        <li id="li-1" class="item-edit"><button onclick="atualizarModal(1);">Editar proprietário</button></li>
                        
                        <li id="li-2" class="item-edit"><button onclick="atualizarModal(2);">Editar endereço</button></li>
                        
                        <li id="li-3" class="item-edit"><button onclick="atualizarModal(3);">Editar dados bancários</button></li>
                        
                        <!-- <li id="li-4" class="item-edit"><button onclick="atualizarModal(4);">Mudar plano</button></li> -->
                    </nav>
                 
                    <div id="edits">
                        <div id="tela-0" class="">
                            <form action="lib/mercado/editar" method="post" id="editarmercado" enctype="multipart/form-data" onkeyup="habilitarBotaoSalvar(0);" style="margin-top: 12px">
                                <div class="campo">
                                    <label for="logo" class="lbl-edit">Logo </label>
                                    <div>
                                        <input type="file" id="logo" name="logo" accept="image/png, image/jpeg, image/jpg, image/bmp, image/gif, image/jfif" onchange="habilitarBotaoSalvar(0);" class="invisivel">
                                        
                                        <label for="logo" style="border-radius: 100rem; cursor: pointer;">
                                            <img src="uploads/mercado/<?= $_GET['id'].'/'.$mercado['logo']?>" alt="<?= $mercado['logo'] ?>" class="edit-logo">
                                        </label>
                                        
                                        <label class="alterar-logo" for="logo"><i class="fas fa-paperclip"></i> Alterar logo do mercado</label>

                                        <div class="alerta invisivel" id="alert-logo"></div>                            
                                    </div>
                                </div>

                                <div class="campo">
                                    <label for="razaosocial" class="lbl-edit">Razão Social </label>

                                    <div class="input-razaosocial">
                                        <input id="razaosocial" name="razaosocial" class="textbox-edit" maxlength="50" autocomplete="off" placeholder="Razão Social" value="<?= $mercado["razaosocial"] ?>" onblur="validarRazaoSocial(this.value)" required/>
                                        <div class="alerta invisivel" id="alert-razaosocial"></div>                            
                                    </div>
                                </div>

                                <div class="campo">
                                    <label for="nomefantasia" class="lbl-edit">Nome Fantasia </label>

                                    <div class="input-nomefantasia">
                                        <input id="nomefantasia" name="nomefantasia" class="textbox-edit" maxlength="50" autocomplete="off" placeholder="Esse nome estará visivel a todos" value="<?= $mercado["nomefantasia"] ?>" onblur="validarNomeFantasia(this.value)" required/>
                                        <div class="alerta invisivel" id="alert-nomefantasia"></div>                            
                                    </div>
                                </div>

                                <div class="campo">
                                    <label for="cnpj" class="lbl-edit">CNPJ </label>
                                    
                                    <div class="input-cnpj">
                                        <input id="cnpj" name="cnpj" class="textbox-edit" maxlength="19" autocomplete="off" placeholder="00.000.000/0000-00" value="<?= $mercado["cnpj"] ?>" onblur="validarCnpj(this.value)" required/>
                                        <div class="alerta invisivel" id="alert-cnpj"></div>                            
                                    </div>
                                </div>

                                <div class="campo">
                                    <label for="bio" class="lbl-edit">Biografia </label>

                                    <textarea name="bio" id="bio" class="textbox-edit" maxlength="255" autocomplete="off" rows="3"><?= $mercado["bio"] ?></textarea>
                                </div>

                                <div class="campo" style="margin-bottom: 7px;">
                                    <label for="celular" class="lbl-edit">Celulares </label>

                                    <?php
                                        require_once "classes/Celular.php";

                                        $celular = new Celular();
                                        $registros = $celular->selecionar($_SESSION["id"]); 
                                                                                
                                        foreach ($registros as $registro)
                                            $values[] = $registro["celular"];
                                    ?>


                                    <div class="inputs-celulares">
                                        <div id="c-celular1" class="<?php if (empty($values[0])) echo "invisivel" ?>">
                                            <input id="ncelular1" name="celular" class="textbox-edit" maxlength="15" autocomplete="off" placeholder="(00) 00000-0000" onblur="validarCelular('ncelular1', this.value)" value="<?php if(!empty($values[0])) echo $values[0] ?>" required/>
                                        
                                            <button type="button" class="btn-celular" id="add-celular" onclick="adicionarCelularEdit(); habilitarBotaoSalvar(0);"><span class="plus"><span>+</span></span></button>
                                        
                                            <div class="alerta invisivel" id="alert-ncelular1"></div>
                                        </div>


                                        <div id="c-celular2" class="<?php if (empty($values[1])) echo "invisivel" ?>">
                                            <input id="ncelular2" name="celular2" class="textbox-edit" maxlength="15" autocomplete="off" placeholder="(00) 00000-0000" onblur="validarCelular('ncelular2', this.value)" value="<?php if(!empty($values[1])) echo $values[1] ?>" <?php if(empty($values[1])) echo "disabled" ?> required/>
                                        
                                            <button type="button" class="btn-celular" id="rm-celular2" onclick="removerCelularEdit(2); habilitarBotaoSalvar(0);"><span class="trash"><i class="fas fa-trash-alt lixo"></i></span></button>                                       
                                        
                                            <div class="alerta invisivel" id="alert-ncelular2"></div>
                                        </div>


                                        <div id="c-celular3" class="<?php if (empty($values[2])) echo "invisivel" ?>">
                                            <input id="ncelular3" name="celular3" class="textbox-edit" maxlength="15" autocomplete="off" placeholder="(00) 00000-0000" onblur="validarCelular('ncelular3', this.value)" value="<?php if(!empty($values[2])) echo $values[2] ?>" <?php if(empty($values[2])) echo "disabled" ?> required/>
                                        
                                            <button type="button" class="btn-celular" id="rm-celular3" onclick="removerCelularEdit(3); habilitarBotaoSalvar(0);"><span class="trash"><i class="fas fa-trash-alt lixo"></i></span></button>                                       
                                        
                                            <div class="alerta invisivel" id="alert-ncelular3"></div>
                                        </div>
                                    </div>                                    
                                </div>
                                
                                <div class="campo">
                                    <div></div>
                                    <button type="button" class="deletar-conta" onclick="confirmarDelete();">Deletar conta permanentemente.</button>
                                </div>

                                <div style="position: relative;">
                                    <button type="submit" id="botao-0" class="btn-salvar" disabled>Salvar</button>
                                </div>
                            </form>
                        </div>

                        <div id="tela-delete" class="invisivel">
                            <form action="lib/mercado/deletar" method="post" style="margin-top: 25px">
                                <span class="ola-dono">Olá, <span class="sp-negrito"><?= $pessoa["nome"] ?></span></span>

                                <div class="c-senha">
                                    <label for="senha" class="lbl-edit">Para continuar, insira a sua senha novamente </label>
                                        
                                    <div class="input-senha">
                                        <input type="password" id="senha" name="senha" class="textbox-edit" maxlength="40" onkeyup="habilitarBotaoSalvar(5);" autocomplete="off" required/>
                                        
                                        <?php
                                            if (isset($_SESSION["erro"])) 
                                                echo "<div class='alerta' style='margin-top: 5px;' id='alert-senha'><i class='fas fa-exclamation-circle'></i>  Senha incorreta! </div>";                            
                                        ?>
                                    </div>

                                    <span class="info-delete">
                                        Quando você pressionar o botão abaixo, seus produtos, seu perfil e suas informações serão deletadas permanentemente.
                                    </span>
                                </div>

                                <div class="c-btndelete">
                                    <button type="submit" id="botao-5" class="btn-salvar" disabled>Excluir conta permanentemente</button>
                                </div>
                            </form>
                        </div>

                        <div id="tela-1" class="invisivel">
                            <form action="lib/pessoa/editar" method="post" id="editarpessoa" style="margin-top: 25px" onkeyup="habilitarBotaoSalvar(1);">
                                <div class="campo">
                                    <label for="nome" class="lbl-edit">Proprietário </label>
                                    <div class="input-nome">
                                        <input id="nome" name="nome" class="textbox-edit" maxlength="50" autocomplete="off" placeholder="Nome completo" value="<?= $pessoa["nome"] ?>" onblur="validarNome(this.value);" required/>
                                        <div class="alerta invisivel" id="alert-nome"></div>
                                    </div>
                                </div>

                                <div class="campo">
                                    <label for="cpf" class="lbl-edit">CPF </label>
                                    <div class="input-cpf">
                                        <input id="cpf" name="cpf" class="textbox-edit" maxlength="14" autocomplete="off" placeholder="000.000.000-00" value="<?= $pessoa["cpf"] ?>" onblur="validarCpf(this.value);" required/>
                                        <div class="alerta invisivel" id="alert-cpf"></div>
                                    </div>
                                </div>

                                <div class="campo">
                                    <label for="rg" class="lbl-edit">RG </label>
                                    <div class="input-rg">
                                        <input id="rg" name="rg" class="textbox-edit" maxlength="12" autocomplete="off" placeholder="00.000.000-0" value="<?= $pessoa["rg"] ?>" onblur="validarRg(this.value);"  required/>
                                        <div class="alerta invisivel" id="alert-rg"></div>
                                    </div>
                                </div>

                                <div style="position:relative;">
                                    <button type="submit" id="botao-1" class="btn-salvar" style="margin-top: 10px" disabled>Salvar</button>
                                </div>
                            </form>
                        </div>

                        <div id="tela-2" class="invisivel">
                            <form action="lib/endereco/editar?idendereco=<?= $endereco["idendereco"] ?>" method="post" id="editarendereco" onkeyup="habilitarBotaoSalvar(2);" style="margin-top: 25px">
                                <div class="campo">
                                    <label for="cep" class="lbl-edit">CEP </label>
                                
                                    <div class="cep-direita">
                                        <input name="cep" class="textbox-edit" id="cep" maxlength="9" autocomplete="off" tabindex="15" placeholder="00000-000" onblur="pesquisacep(this.value)" value="<?= $endereco["cep"] ?>" required/>
                                        
                                        <div class="alerta invisivel" id="alert-cep"></div>
                                    </div>    
                                </div>

                                <div class="campo">
                                    <label for="rua" class="lbl-edit">Rua </label>

                                    <div class="input-rua">
                                        <input name="rua" class="textbox-edit" id="rua" maxlength="50" autocomplete="off" tabindex="16" placeholder="Digite sua rua" onblur="validarCamposEndereco('rua', this.value);" value="<?= $endereco["rua"] ?>" required/>

                                        <div class="alerta invisivel" id="alert-rua"></div>
                                    </div>
                                </div>

                                <div class="campo">
                                    <label for="numero" class="lbl-edit">Número </label>
                                    <div class="numero-direita">
                                        <input name="numero" class="textbox-edit" id="numero" maxlength="8" placeholder="Digite somente números" autocomplete="off" tabindex="17" onblur="validarCamposEndereco('numero', this.value);" value="<?= $endereco["numero"] ?>" required/>

                                        <input type="checkbox" name="semnumero" id="semnumero" tabindex="14"/>
                                        <label for="semnumero" id="text-sn">&nbsp;Endereço sem número</label>  
                                
                                        <div class="alerta invisivel" id="alert-numero"></div>
                                    </div> 
                                </div>
                                
                                <div class="campo">
                                    <label for="bairro" class="lbl-edit">Bairro </label>
                                    
                                    <div class="input-bairro">
                                        <input name="bairro" class="textbox-edit" id="bairro" maxlength="50" placeholder="Digite seu bairro" autocomplete="off" tabindex="18" onblur="validarCamposEndereco('bairro', this.value);" value="<?= $endereco["bairro"] ?>" required/>

                                        <div class="alerta invisivel" id="alert-bairro"></div>
                                    </div>
                                </div>

                                <div class="campo">
                                    <label for="cidade" class="lbl-edit">Cidade </label>
                                    
                                    <div class="input-cidade">
                                        <input name="cidade" class="textbox-edit" id="cidade" maxlength="50" placeholder="Digite sua cidade" autocomplete="off" tabindex="19" onblur="validarCamposEndereco('cidade', this.value);" value="<?= $endereco["cidade"] ?>" required/>

                                        <div class="alerta invisivel" id="alert-cidade"></div>
                                    </div>
                                </div>
            
                                <div class="campo">
                                    <label for="estado" class="lbl-edit">Estado </label>
                                    
                                    <div class="input-estado">
                                        <input name="estado" class="textbox-edit" id="estado" maxlength="20" placeholder="Digite seu estado" autocomplete="off" tabindex="20" onblur="validarCamposEndereco('estado', this.value);" value="<?= $endereco["estado"] ?>" required/>

                                        <div class="alerta invisivel" id="alert-estado"></div>
                                    </div>
                                </div> 

                                <div class="campo c-complemento">
                                    <label for="complemento" class="lbl-edit">Complemento</label>
                                    <input name="complemento" class="textbox-edit" id="complemento" maxlength="80" placeholder="Digite um complemento (opcional)" autocomplete="off" tabindex="21" value="<?= $endereco["complemento"] ?>"/>
                                </div>

                                <div style="position: relative;">
                                    <button type="submit" id="botao-2" class="btn-salvar" disabled>Salvar</button>
                                </div>
                            </form>
                        </div>

                        <div id="tela-3" class="invisivel">
                            <?php
                                require_once "classes/Dados_Conta.php";
                                $objDados_Conta = new Dados_Conta();
                                $objDados_Conta->setIdDConta($mercado["fkdconta"]);
                                $dados_conta = $objDados_Conta->selecionar();
                            ?>

                            <form action="lib/dados_conta/editar" method="post" id="editarddconta" onkeyup="habilitarBotaoSalvar(3);" onchange="habilitarBotaoSalvar(3);" style="margin-top: 25px">

                                <div class="campo">
                                    <label for="tipoconta" class="lbl-edit">Tipo de Conta</label>
                                    
                                    <input type="text" id="documento" value="<?= $dados_conta["documento"] ?>" class="invisivel"/>

                                    <div class="input-tipoconta">
                                        <label class="lbl-tipoconta-checked lbl-pf" for="pf">
                                            <input type="radio" name="tipoconta" id="pf" value="pf" onchange="atualizarRadioTipoConta();" tabindex="22"/>
                                            
                                            <div id="rd-pf" class="radio-design-checked"></div>

                                            <span class="span-pf">&nbsp; Pessoa Física</span>

                                            <div class="radio-partes">
                                                <div>
                                                    <span class="desc">Nome</span> <span id="span-nome"><?= $pessoa["nome"] ?></span>
                                                </div>
                                                
                                                <div>
                                                    <span class="desc">CPF</span> <span id="span-cpf"><?= $pessoa["cpf"] ?></span>
                                                </div>
                                            </div>
                                        </label>
                                            

                                        <label class="lbl-tipoconta lbl-pj" for="pj">
                                            <input type="radio" name="tipoconta" id="pj" value="pj" onchange="atualizarRadioTipoConta();" tabindex="23"/>

                                            <div id="rd-pj" class="radio-design"></div>
                                            <span class="span-pj">&nbsp; Pessoa Juridica</span>

                                            <div class="radio-partes">
                                                <div>
                                                    <span class="desc">Razão Social</span> <span id="span-razaosocial"><?= $mercado["razaosocial"] ?></span>
                                                </div>
                                                
                                                <div>
                                                    <span class="desc">CNPJ</span> <span id="span-cnpj"><?= $mercado["cnpj"] ?></span>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                </div>

                                <div class="campo">
                                    <label for="banco" class="lbl-edit">Banco</label>

                                    <div class="input-banco">
                                        <input name="banco" class="textbox-edit" id="banco" maxlength="50" autocomplete="off" tabindex="24" placeholder="Aonde seu negócio possui conta?" onblur="validarBanco(this.value);" value="<?= $dados_conta["banco"] ?>" required/>
                                        
                                        <div class="alerta invisivel" id="alert-banco"></div>
                                    </div>
                                </div>

                                <div class="campo">
                                    <label for="agencia" class="lbl-edit">Agência</label>

                                    <div class="input-agencia">
                                        <input name="agencia" class="textbox-edit" id="agencia" maxlength="7" autocomplete="off" tabindex="25" placeholder="Agência do seu negócio" onblur="validarAgencia(this.value);" value="<?= $dados_conta["agencia"] ?>" required/>
                                        
                                        <div class="alerta invisivel" id="alert-agencia"></div>
                                    </div>
                                </div>

                                <div class="campo">
                                    <label for="conta" class="lbl-edit">Conta</label>

                                    <div class="input-conta">
                                        <div class="conta-row">
                                            <input name="conta" class="textbox-edit" id="conta" maxlength="10" autocomplete="off" tabindex="26" placeholder="Conta do seu negócio" onblur="validarConta(this.value);" value="<?= $dados_conta["conta"] ?>" required/>
                                            
                                            <input name="digito" class="textbox-edit" id="digito" maxlength="1" autocomplete="off" tabindex="27" placeholder="Dígito" onblur="validarDigito(this.value);" value="<?= $dados_conta["digito"] ?>" required/>
                                        </div>

                                        <div class="alerta invisivel" id="alert-conta"></div>
                                    </div>
                                </div>

                            
                                <div style="position: relative;">
                                    <button type="submit" id="botao-3" class="btn-salvar" disabled>Salvar</button>
                                </div>
                            </form>
                        </div>

                        <!-- <div id="tela-4" class="invisivel">
                            <form action="lib/mercado/alterarplano" method="post" style="margin-top: 25px">

                                <input name="plano" id="plano" value="<?= $mercado["plano"] ?>"/>

                                <div class="bloco-plano bloco-basico" id="sombra" onclick="alterarPlano('basico'); habilitarBotaoSalvar(4);">                                
                                    <div class="cabecalho-bloco amarelo">
                                        <h1 class="titulo-bloco">Plano básico</h1>  
                                        
                                        <i class="fas fa-check selecionado invisivel"></i>
                                    </div>

                                    <div class="corpo-bloco">
                                        <span><i class="fas fa-check-circle"></i> Entrega feita pelo mercado. </span>

                                        <span><i class="fas fa-check-circle"></i> Comissão de 5% das vendas mais mensalidade de R$ 100,00. </span>
                                    
                                        <span><i class="fas fa-check-circle"></i> Cancele o plano a qualquer momento. </span>
                                    </div>

                                    <button type="button" class="btn-escolher" id="btn-basico">Escolher</button>
                                </div>

                                <br/>

                                <div class="bloco-plano bloco-entrega" id="sombra" onclick="alterarPlano('entrega'); habilitarBotaoSalvar(4);">
                                    
                                    <div class="cabecalho-bloco roxo">
                                        <h1 class="titulo-bloco">Plano entrega</h1>

                                        <i class="fas fa-check selecionado invisivel"></i>
                                    </div>

                                    <div class="corpo-bloco">
                                        <span><i class="fas fa-check-circle"></i> Entrega pelos entregadores do Digital Market. </span>

                                        <span><i class="fas fa-check-circle"></i> Acione entregadores pela região a qualquer instante. </span>

                                        <span><i class="fas fa-check-circle"></i> Comissão de 12% mais mensalidade de R$ 200,00. </span>

                                        <span><i class="fas fa-check-circle"></i> Cancele o plano a qualquer momento. </span>
                                    </div>

                                    <button type="button" class="btn-escolher" id="btn-entrega">Escolher</button>
                                </div>

                                <div style="position: relative; margin-top: 27.5px;">
                                    <button type="submit" id="botao-4" class="btn-salvar" disabled>Salvar</button>
                                </div>
                            </form>
                        </div> -->
                    </div>
                </div>
            </div>
    <?php
        }
    ?>

    <!-- arquivo .js jquery -->
    <script src="js/jquery/jquery.js"></script>

    <script src="js/jquery/jquery.mask.min.js"></script>

    <!-- arquivo .js bootstrap -->
    <script src="js/bootstrap/bootstrap.js"></script>

    <script src="js/requisicoes.js"></script>

    <script src="js/cep.js"></script>

    <script src="js/verificadores.js"></script>

    <script src="js/cadastrar.js"></script>

    <script src="js/cadmercado.js"></script>

    <script src="js/mercado.js"></script>


    <script>
        <?php
            if (isset($_SESSION["erro"])) {
        ?>
                $("#tela-0").attr("class", "invisivel");
                $("#tela-delete").attr("class", "");
                visibilidadeModalPerfil(true);        
        <?php  
                unset($_SESSION["erro"]);
            }
        ?> 
    </script>
</body>
</html>