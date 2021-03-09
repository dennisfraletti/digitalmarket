<?php
    session_start();
    
    if (!isset($_SESSION["id"]) || $_SESSION["tipo"] != 1) {
        header("Location: /digitalmarket/");
        die;
    }

    require_once "classes/Produto.php";
    require_once "classes/Produto_Imagem.php";
    
    
    if (isset($_GET["idproduto"])) {    
        
        $objProduto = new Produto();
        $objProduto->setIdProduto($_GET["idproduto"]);
        $produto = $objProduto->selecionar();

        if ($produto["fkmercado"] != $_SESSION["id"]) {
            echo "<script>window.history.go(-1)</script>"; 
            die;
        }
    }

    require_once "classes/Mercado.php";

    $mercado = new Mercado();
    $mercado = $mercado->selecionar($_SESSION["id"]);

    require_once "classes/Pessoa.php";

    $pessoa = new Pessoa();
    $pessoa->setId($_SESSION["id"]);
    $pessoa = $pessoa->selecionar();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Anuncie seus produtos | Digital Market</title>

    <!-- arquivo .css bootstrap -->
    <link rel="stylesheet" href="css/bootstrap/bootstrap.css"/>

    <!-- arquivo de icones da font awesome -->
    <link rel="stylesheet" href="font/css/all.css"/>

    <link rel="stylesheet" href="css/cores.css"/>

    <link rel="stylesheet" href="css/vendas.css"/>

    <link rel="shortcut icon" href="img/dm.ico"/>
</head>
<body>
    <div class="bg-alvorecer invisivel" onclick="confirmarDelete(false, 0);"></div>
    
    <!-- navbar -->
    <nav class="nav-menu">
        <div class="nav-logo">
            <a href="index"><i class="fas fa-shopping-cart"></i> Digital Market</a>
        </div>

        <div class="text-perfil">
            <a class="link-perfil">
                <?php
                    $primeiro_nome = explode(" ", $pessoa["nome"]);

                    if (strlen($primeiro_nome[0]) > 12) 
                        $primeiro_nome[0] = mb_strimwidth($primeiro_nome[0], 0, 12, "...");
                ?>
        
                Olá, <span><?= ucfirst($primeiro_nome[0]) ?></span>&nbsp;
                <i class="fas fa-caret-down"></i>
            </a>
        </div>

        <div class="dropdown">
            <a href="mercado?id=<?= $_SESSION["id"] ?>"><i class="fas fa-globe-americas"></i><span>&nbsp; Gerenciar</span></a>        
            <a href="pedidos"><i class="fas fa-truck"></i><span>&nbsp; Pedidos</span></a>
            <a href="lib/pessoa/sair"><i class="fas fa-sign-out-alt"></i><span>&nbsp; Sair</span></a>
        </div>
    </nav>

    <div class="boas-vindas" id="boas-vindas">
        <span>Olá, <?= $primeiro_nome[0] ?>,</span><br/>
        <span>bem-vindo de volta à casa! </span>

        <a href="mercado?id=<?= $_SESSION["id"] ?>" id="link-voltar"><div><i class="fas fa-chevron-left"></i><span id="sp-voltar">&nbsp;Voltar&nbsp;&nbsp;&nbsp;&nbsp;</span></div></a>
    </div>


    <div class="corpo tela-inicio">
        <h1 class="anuncio">Qual anuncio você deseja continuar? </h1>
    
        <!-- listar produtos -->
        <div class="list-produtos">
            <?php
                $objProduto = new Produto();

                $objProduto->setFkMercado($_SESSION["id"]);
                $produtos = $objProduto->selecionar();
                
                foreach ($produtos as $produto) {
                    $produto_imagem = new Produto_Imagem();
                    $produto_imagem->setFkProduto($produto["idproduto"]);
                    $resultado = $produto_imagem->selecionar();

            ?>
                    <div class="item-produto">  
                        <div class="img-prod" onclick="location.href = '?idproduto=<?= $produto['idproduto'] ?>'">
                            <img src="uploads/mercado/<?= $produto["fkmercado"] ?>/produtos/<?= $resultado[0]["imagem"] ?>" class="list-imgprod" alt="<?= $produto["titulo"] ?>"/>
                        </div>
                            
                        <div class="infos-produto" onclick="location.href = '?idproduto=<?= $produto['idproduto'] ?>'">
                            
                            <span class="sp-titulo">
                                <?php
                                    echo $produto["titulo"]. "<br/>"; 
                                        
                                    $meses = array("Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho", "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro");
                                    $ano = explode("-", $produto["cadastro"])[0];
                                    $mes = explode("-", $produto["cadastro"])[1];
                                    $dia = explode(" ", explode("-", $produto["cadastro"])[2]);

                                    echo "<span class='sp-cadastro'>Cadastrado em ". $dia[0] ." de ". $meses[$mes - 1] . " de " . $ano."</span>";
                                ?>
                            </span>
                        </div>
                    
                        <?php 
                            $titulo = $produto["titulo"]; 
                        ?>

                        <button class="link-rmproduto" id="btnprod-<?= $produto["idproduto"] ?>" onclick="confirmarDelete(true, <?= $produto['idproduto'] ?>);">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    
                    </div>
            <?php
                }
            ?>


        </div>

        <button class="add-produto" onclick="iniciarCadastro();">
            Iniciar um novo anuncio
        </button>
    </div>







    <!-- cadastrar produtos -->

    <form action="lib/produto/cadastrar" method="post" id="cadastrarProduto" enctype="multipart/form-data">

        <div class="corpo tela-1 invisivel">
        
            <h1 class="titulo">Indique seu produto, incluindo marca e modelo</h1>
        
            <span class="legenda">Esse será o título de seu produto, todos poderão vê-lo. Um bom título pode atrair mais clientes. Faça um título breve, mais tarde você poderá adicionar uma descrição mais extensa ao seu produto! </span>

            <div class="input">
                <input name="titulo" id="titulo" class="textbox-produto" maxlength="60" autocomplete="off" placeholder="Ex.: Batata Frita Lays Chips Original Lays 96g" tabindex="1" onkeyup="atualizarQtdInput('qtd-titulo', this.value); habilitarBtnProximo(1, this.value);" onkeydown="atualizarQtdInput('qtd-titulo', this.value);" required/>
                    
                <label class="txt-titulo text" for="titulo">Não inclua condições de venda, como parcelamento ou frete gratuito.</label>

                <div class="text-qtd"><span id="qtd-titulo">0</span> / 60</div>    
            </div>
                  
            <button type="button" class="btn-continuar" id="btn-1" tabindex="2" style="margin-top: 45px;" onclick="proximoForm(1);" disabled>Continuar</button>
        
        </div>


        <div class="corpo tela-2 invisivel">
            <h1 class="titulo">Selecione a categoria que seu produto pertence</h1>
        
            <span class="legenda">Seu produto aparecerá quando o usuário pesquisar pela categoria</span>

            <div class="select">
                <h1 class="titulo-radio">A qual categoria seu produto pertence? </h1>

                <div class="radios">
                    <div class="radio">
                        <input type="radio" name="categoria" id="alimentos" value="1" onchange="habilitarBtnProximo(2);"/>
                        <label class="categoria" for="alimentos"><span>&nbsp; Alimentos</span></label>
                    </div>
    
                    <div class="radio">
                        <input type="radio" name="categoria" id="bebidas" value="2" onchange="habilitarBtnProximo(2);"/>
                        <label class="categoria" for="bebidas"><span>&nbsp; Bebidas</span></label>
                    </div>
    
                    <div class="radio">
                        <input type="radio" name="categoria" id="carnes" value="3" onchange="habilitarBtnProximo(2);"/>
                        <label class="categoria" for="carnes"><span>&nbsp; Carnes</span></label>
                    </div>

                    <div class="radio">
                        <input type="radio" name="categoria" id="laticinios" value="4" onchange="habilitarBtnProximo(2);"/>
                        <label class="categoria" for="laticinios"><span>&nbsp; Laticínios</span></label>
                    </div>

                    <div class="radio">
                        <input type="radio" name="categoria" id="limpeza" value="5" onchange="habilitarBtnProximo(2);"/>
                        <label class="categoria" for="limpeza"><span>&nbsp; Limpeza</span></label>
                    </div>

                    <div class="radio">
                        <input type="radio" name="categoria" id="padaria" value="6" onchange="habilitarBtnProximo(2);"/>
                        <label class="categoria" for="padaria"><span>&nbsp; Padaria</span></label>
                    </div>

                    <div class="radio">
                        <input type="radio" name="categoria" id="perfumaria" value="7" onchange="habilitarBtnProximo(2);"/>
                        <label class="categoria" for="perfumaria"><span>&nbsp; Perfumaria</span></label>
                    </div>

                    <div class="radio ultimo-radio">
                        <input type="radio" name="categoria" id="outras" value="8" onchange="habilitarBtnProximo(2);"/>
                        <label class="categoria" for="outras"><span>&nbsp; Outras</span></label>
                    </div>
                </div>
            </div>
                  
            <button type="button" class="btn-continuar" id="btn-2" tabindex="3" onclick="proximoForm(2);" disabled>Continuar</button>

        </div>



       <div class="corpo tela-3 invisivel" id="tela-3">
            <h1 class="titulo">Selecione imagens do seu produto</h1>

            <div class="aviso">
                <i class="fas fa-info-circle ico-aviso"></i>
                <span>Para evitar perder a exposição, certifique-se de que a primeira foto tenha fundo branco puro criado com um editor de imagens. Não adicione bordas, logotipos ou marcas d'água.</span>
            </div>

            <div id="erro" class="alert-erro invisivel"></div>

            <div class="upload-produto">
                <input type="file" name="img[]" id="img" accept="image/png, image/jpeg, image/jpg, image/bmp, image/gif, image/jfif" onchange="habilitarBtnProximo(3);" multiple/>
        
                <label id="lbl-img" for="img">
                    <span>
                        <i class="far fa-file-image ico-img"></i>&nbsp;&nbsp;Adicione suas imagens aqui
                    </span>
                </label>

                <div id="listagem-imagens" class="invisivel"></div>
            </div>

            <button type="button" class="btn-continuar" id="btn-3" tabindex="4" onclick="proximoForm(3);" disabled>Continuar</button>
        </div>

        <div class="corpo tela-4 invisivel">
            <h1 class="titulo">Qual o preço do seu produto? </h1>

            <span class="legenda">Indique o preço e o desconto de seu produto em reais.</span>

            <div class="colunas">
                <div class="campo c-preco">
                    <input name="preco" id="preco" class="textbox-produto" maxlength="15" autocomplete="off" onkeyup="atualizarPrecoFinal(); habilitarBtnProximo(4);" tabindex="1" required/>
                    <label for="preco">Preço </label>
                </div>

                <div class="campo">
                    <input name="estoque" id="estoque" class="textbox-produto" maxlength="4" autocomplete="off" onkeyup="habilitarBtnProximo(4);" tabindex="4" required/>
                    <label for="estoque">Quantidade em estoque </label>
                </div>

                <div class="campo">
                    <input name="desconto" id="desconto" class="textbox-produto" maxlength="4" autocomplete="off" placeholder="" onkeyup="atualizarPrecoFinal(); habilitarBtnProximo(4);" tabindex="3" disabled required/>
                    <label for="desconto">Desconto (em porcentagem) </label>

                    <div class="check">
                        <input type="checkbox" name="semdesc" id="semdesc" class="invisivel" onchange="atualizarBtnDesconto();" tabindex="2" checked/>
                        <label for="semdesc">&nbsp;Aplicar desconto</label>
                    </div>
                </div>

                <div id="campo-pf" class="campo invisivel" style="margin-top: 10px;">
                    <input name="precofinal" id="precofinal" class="textbox-produto" maxlength="15" autocomplete="off" placeholder="" tabindex="3" disabled required/>
                    <label for="precofinal">Preço final<label>
                </div>
            </div>

            <button type="submit" class="btn-continuar" id="btn-4" style="margin-top: 35px;" tabindex="4" disabled>Salvar</button>
        </div>
        
    </form>


    <div id="confirmar-delete" class="invisivel">
        <span id="confdelete">Deseja realmente apagar?</span>

        <div class="botoes">
            <button onclick="window.location='lib/produto/deletar?idproduto=<?= $produto['idproduto'] ?>'" class="btn-deletar">Deletar</button>
            <button class="btn-cancelar" onclick="confirmarDelete(false, 0);">Cancelar</button>
        </div>
    </div>



    <!-- arquivo .js jquery -->
    <script src="js/jquery/jquery.js"></script>

    <script src="js/jquery/jquery.mask.min.js"></script>

    <!-- arquivo .js bootstrap -->
    <script src="js/bootstrap/bootstrap.js"></script>

    <script src="js/vendas.js"></script>


    
    <?php

        if (isset($_GET["idproduto"])) {

            $produto = new Produto();
            $produto->setIdProduto($_GET["idproduto"]);
            $produto = $produto->selecionar();      
    ?>

            <script>
                $(".tela-inicio").addClass("invisivel");

                $(".tela-1").removeClass("invisivel");
                $(".tela-2").removeClass("invisivel");
                $(".tela-3").removeClass("invisivel");
                $(".tela-4").removeClass("invisivel");
                
                $(".boas-vindas").html("<span>Altere os dados do seu produto.</span><a href='vendas' id='link-voltar'><div><i class='fas fa-chevron-left'></i><span id='sp-voltar'>&nbsp;Voltar&nbsp;&nbsp;&nbsp;&nbsp;</span></div></a>");

                $("#titulo").val("<?= $produto["titulo"] ?>");
                atualizarQtdInput("qtd-titulo", "<?= $produto["titulo"] ?>");

                let categorias = ["alimentos", "bebidas", "carnes", "laticinios", "limpeza", "padaria", "perfumaria", "outras"];                


                $("#" + categorias[<?= ($produto["fkcategoria"] - 1) ?>]).attr("checked", true);
                $(".radios").animate({scrollTop: <?= ($produto["fkcategoria"] - 1) * 50 ?>}, 500);

                $("#img").attr("onchange", "verificarCampoImg();");
                
                let i = 0;

                <?php
                    $produto_imagem = new Produto_Imagem();

                    $produto_imagem->setFkProduto($_GET["idproduto"]);
                
                    $imagens = $produto_imagem->selecionar();

                    foreach ($imagens as $imagem) {
                ?>
                        document.getElementById("listagem-imagens").innerHTML += "<div id='img-" + i + "' class='item-imagem' onclick='removerImagem(this.id); atualizarQtdRm();'><img src='uploads/mercado/<?= $_SESSION["id"] ?>/produtos/<?= $imagem["imagem"] ?>' class='imagem'/><button class='btn-excluir' type='button'><span class='borda-btn-exluir'><i class='fas fa-trash-alt lixo'></i></span></button></div>"; 
                        document.getElementById("listagem-imagens").innerHTML += "<input type='hidden' name='file" + i + "' id='img-prod-" + i +"'/>";
                        i++;
                <?php
                    }
                ?>
                
                document.getElementById("tela-3").innerHTML += "<input type='hidden' name='qtdrm' id='qtdrm' value='0'/>";

                $("#listagem-imagens").removeClass("invisivel");
                
                let preco = <?= $produto["preco"] ?>;

                $("#preco").val(transformarDinheiro(preco));
                $("#estoque").val(<?= $produto["estoque"] ?>);

                
                <?php 
                    if ($produto["precodesconto"] != $produto["preco"]) {
                ?>
                
                        let precodesconto = <?= $produto["precodesconto"] ?>;

                        $("#precofinal").val(transformarDinheiro(precodesconto));
                        
                        let desconto = ((parseFloat(preco) - parseFloat(precodesconto)) * 100) / parseFloat(preco);
                        desconto = (!Number.isInteger(desconto)) ? Math.round(desconto) : desconto;
                        $("#desconto").val(desconto);
                        
                        atualizarBtnDesconto(true);
                
                <?php
                    }
                ?>

                $("#cadastrarProduto").attr("action", "lib/produto/editar?idproduto=" + <?= $produto["idproduto"] ?>);
                $("button").addClass("invisivel");

                $("#btn-4").attr("id", "btn-salvar");
                $("#btn-salvar").attr("disabled", false);
                $("#btn-salvar").css("margin-top", "60px");
                $("#btn-salvar").removeClass("invisivel");
               
            </script>
    <?php
        }
    ?>
    
</body>
</html>