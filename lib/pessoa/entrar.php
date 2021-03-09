<?php 

    if (!$_POST) {
        echo "<script>window.history.go(-1)</script>"; 
        die;
    }
    
    foreach ($_POST as $key => $value)
        $$key = $value;

    require_once "../../classes/Pessoa.php";

    $pessoa = new Pessoa();
    
    $pessoa->setEmail($email);
    $pessoa->setSenha($senha);

    $entrar = $pessoa->entrar();

    session_start();

    if ($entrar[0]) {
        if ($_SESSION["tipo"] == 1)
            header("Location: ../../mercado?id=". $_SESSION["id"]);
        else
            header("Location: ../../");

    } else {
        $_SESSION["email"] = $email;

        if ($entrar[1] == 0) {
            if ($_GET["confirmar"]) 
                $_SESSION["errosenha"] = true;
    
            header("Location: ../../entrar/confirmar");
        } else
            echo "<script>window.history.go(-1);</script>"; 
    }
