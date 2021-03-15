<?php
    session_start();

    if (isset($_SESSION["id"])) {
        echo "<script>window.history.go(-1)</script>"; 
        die;
    }

    $email = isset($_SESSION["email"]) ? $_SESSION["email"] : "";
    $erro = isset($_SESSION["email"]) ? "" : "invisivel";

    unset($_SESSION["email"]);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Entrar | Digital Market</title>

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
            <a href="../">
                <i class="fas fa-shopping-cart"></i>&nbsp;Digital Market
            </a>
        </div>
    </nav>

    <!-- conteudo pag -->
    <div class="pagina">
        <!-- lado esquerdo -->
        <div class="esquerda">
            <label class="entrar-text">
                <span>Junte-se ao</span>
                <span>Digital Market</span>
            </label>
        </div>

        <!-- lado direito -->
        <div class="direita">
            <h1 class="entrar-titulo">Entrar</h1>
            <label class="entrar-text-form">Inicie uma sessão no Digital Market</label>
            
            <form action="../lib/pessoa/entrar" method="post" id="regForm">
                <div class="campo-texto">
                    <input name="email" class="textbox email" maxlength="70" autocomplete="off" value="<?= $email ?>" tabindex="1" onfocus="esconderBalao();" required/>
                    <label class="label-input">E-mail</label>
                    
                    <div id="balao" class="<?= $erro ?>"><span class="msg-erro">E-mail não encontrado! </span></div>
                </div>

                <div class="campo-texto">
                    <input type="password" name="senha" class="textbox senha" maxlength="40" autocomplete="off" tabindex="2" required/>    
                    <label class="label-input">Senha</label>
                    
                    <button type="button" class="botao-visibilidade">
                        <i id="visibilidade" class="far fa-eye"></i>
                    </button>
                </div>

                <button type="submit" class="form-botao" tabindex="3">Entrar</button>
            </form>

            <div class="divisao">ou</div>

            <button class="btn-cadastrar form-botao" onclick="location.href = '../cadastrar/cliente';" tabindex="4">Cadastrar-se</button>
        </div>
    </div>

    <!-- arquivo .js jquery -->
    <script src="../js/jquery/jquery.js"></script>

    <!-- arquivo .js bootstrap -->
    <script src="../js/bootstrap/bootstrap.js"></script>
    
    <script src="../js/requisicoes.js"></script>
</body>
</html>