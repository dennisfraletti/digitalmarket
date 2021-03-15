<?php
    session_start();

    if (isset($_SESSION["id"]) || !isset($_SESSION["email"]) || !isset($_SESSION["iderro"])) {
        echo "<script>window.history.go(-1)</script>"; 
        die;
    }

    require_once "../classes/Pessoa.php";
    $pessoa = new Pessoa();
    $pessoa->setId($_SESSION["iderro"]);
    $pessoa = $pessoa->selecionar();

    $erro = isset($_SESSION["email"]) ? "" : "invisivel";
    $errosenha = isset($_SESSION["errosenha"]) ? "" : "invisivel";

    unset($_SESSION["email"]);
    unset($_SESSION["errosenha"]);
    unset($_SESSION["iderro"]);

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Confirmar e-mail | Digital Market</title>

    <!-- arquivo .css bootstrap -->
    <link rel="stylesheet" href="../css/bootstrap/bootstrap.css"/>

    <!-- arquivo de icones da font awesome -->
    <link rel="stylesheet" href="../font/css/all.css"/>
    
    <link rel="stylesheet" href="../css/cores.css"/>

    <link rel="stylesheet" href="../css/entrar.css"/>

    <link rel="shortcut icon" href="../img/dm.ico"/>
</head>
<body>
    <!-- navbar -->
    <nav class="nav-menu">
        <div class="nav-logo">
            <a href="../entrar">
                <i class="fas fa-shopping-cart"></i>&nbsp;Digital Market
            </a>
        </div>
    </nav>

    <!-- conteudo pag -->
    
    <div class="centro">
        <h1 class="entrar-titulo">Confirmar e-mail</h1>
        <label class="entrar-text-form" style="display: block;">
            Entrar como <span class="negrito"><?= $pessoa["nome"] ?></span>
        </label>

        <label class="lbl-confirmar-email">
            <span class="sp-email"><?= $pessoa["email"] ?>&nbsp;</span>

            <a href="../entrar/" class="link-login">Não é você?</a>
        </label>
            
        <form action="../lib/pessoa/entrar?confirmar=true" method="post" id="regForm">
            <div class="campo-texto" style="display:none">
                <input name="email" class="textbox email" maxlength="70" autocomplete="on" value="<?= $pessoa["email"] ?>" tabindex="1"/>
                <label class="label-input">E-mail</label>
            </div>

            <div class="campo-texto">
                <input type="password" name="senha" class="textbox senha" maxlength="40" autocomplete="off" tabindex="2" onfocus="esconderBalao();" required/>    
                <label class="label-input">Senha</label>
                    
                <button type="button" class="botao-visibilidade">
                    <i id="visibilidade" class="far fa-eye"></i>
                </button>
                
                <div id="balao" class="<?= $errosenha ?>"><span class="msg-erro">Senha incorreta!</span></div>
            </div>


            <button type="submit" class="form-botao" tabindex="3">Entrar</button>
        </form>

        <div class="divisao">ou</div>

        <button class="btn-cadastrar form-botao" onclick="location.href = '../cadastrar/cliente';" tabindex="4">Cadastrar-se</button>
    </div>

    

    <!-- arquivo .js jquery -->
    <script src="../js/jquery/jquery.js"></script>

    <!-- arquivo .js bootstrap -->
    <script src="../js/bootstrap/bootstrap.js"></script>

    <script src="../js/requisicoes.js"></script>
</body>
</html>