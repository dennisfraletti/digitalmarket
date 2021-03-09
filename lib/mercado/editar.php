<?php   
    session_start();
    
    if (!$_POST || !isset($_SESSION["id"])) {
        echo "<script>window.history.go(-1)</script>"; 
        die;
    }

    foreach ($_POST as $key => $value)
        $$key = $value;
      
    require_once "../../classes/Mercado.php";

    $mercado = new Mercado();

    $registros = $mercado->pessoa->celular->selecionar($_SESSION["id"]);
    

    if ($registros[0]["celular"] != $celular) {
        $mercado->pessoa->celular->setCelular($celular);
        $mercado->pessoa->celular->setIdCelular($registros[0]["idcelular"]);
        $mercado->pessoa->celular->editar();
    }
    
    if (isset($registros[1]["celular"]) && isset($celular2)) {
        $mercado->pessoa->celular->setCelular($celular2);
        $mercado->pessoa->celular->setIdCelular($registros[1]["idcelular"]);
        $mercado->pessoa->celular->editar();
    }

    if (!isset($registros[1]["celular"]) && isset($celular2)) {
        $mercado->pessoa->celular->setFkPessoa($_SESSION["id"]);
        $mercado->pessoa->celular->setCelular($celular2);
        $mercado->pessoa->celular->cadastrar();
    }
    
    
    if (isset($registros[1]["celular"]) && empty($celular2)) {
        $mercado->pessoa->celular->setIdCelular($registros[1]["idcelular"]);    
        $mercado->pessoa->celular->deletar();
    }

    if (isset($registros[2]["celular"]) && isset($celular3)) {
        $mercado->pessoa->celular->setCelular($celular3);
        $mercado->pessoa->celular->setIdCelular($registros[2]["idcelular"]);
        $mercado->pessoa->celular->editar();
    }

    if (!isset($registros[2]["celular"]) && isset($celular3)) {
        $mercado->pessoa->celular->setFkPessoa($_SESSION["id"]);
        $mercado->pessoa->celular->setCelular($celular3);
        $mercado->pessoa->celular->cadastrar();
    }
    
    
    if (isset($registros[2]["celular"]) && empty($celular3)) {
        $mercado->pessoa->celular->setIdCelular($registros[2]["idcelular"]);    
        $mercado->pessoa->celular->deletar();
    }

    $mercado->pessoa->setId($_SESSION["id"]);
    $oldmercado = $mercado->selecionar();

    // insert mercado
    if (isset($_FILES["logo"])) {
        $formatos = array("jpg", "jpeg", "png", "bmp", "gif", "jfif");
        $logo = $_FILES["logo"];
        $extensao = pathinfo($logo["name"], PATHINFO_EXTENSION); 
        
        if (in_array($extensao, $formatos) && $logo["size"] <= 1000000) {
            $nome = pathinfo($logo["name"], PATHINFO_FILENAME);
            $nomejpg = $nome.".jpg";
    
            $obj = new Conexao();
            $conexao = $obj->conectar();
    
            $query = "SELECT COUNT(*) AS 'qtd' FROM mercado WHERE logo = :logo";
            $comando = $conexao->prepare($query);
            $comando->bindparam(":logo", $nomejpg);
            
            $comando->execute();
        
            $resultado = $comando->fetch();
            
            $newnome = ($resultado["qtd"] == 0) ? "{$nome}.jpg" : "{$nome} ({$resultado["qtd"]}).jpg";
                        
            if (!move_uploaded_file($logo["tmp_name"], "../../uploads/mercado/".$_SESSION["id"]."/".$newnome)) {            
                echo "<script>alert('Ocorreu algum erro com o upload!'); window.history.go(-1)</script>";   
                die;                 
            } else { 
                $logo = $newnome;
                unlink("../../uploads/mercado/".$_SESSION["id"]."/".$oldmercado["logo"]);
            }
        } else {
            echo "<script>alert('Arquivo inválido!'); window.history.go(-1)</script>"; 
            unset($logo);
        }
    } 

    $mercado->setRazaoSocial($razaosocial);
    $mercado->setNomeFantasia($nomefantasia);
    $mercado->setCnpj($cnpj);

    $logo = (isset($logo)) ? $logo : $oldmercado["logo"];  
    
    $mercado->setLogo($logo);
    $mercado->setBio($bio);
    $mercado->pessoa->setId($_SESSION["id"]);
    $mercado->editar();


    $mercado->dados_conta->setIdDConta($oldmercado["fkdconta"]);
    $dados_conta = $mercado->dados_conta->selecionar();

    if (strlen($dados_conta["documento"]) == 18) {
        $mercado->dados_conta->setBeneficiario($razaosocial);
        $mercado->dados_conta->setDocumento($cnpj);
        $mercado->dados_conta->setBanco($dados_conta["banco"]);
        $mercado->dados_conta->setAgencia($dados_conta["agencia"]);
        $mercado->dados_conta->setConta($dados_conta["conta"]);
        $mercado->dados_conta->setDigito($dados_conta["digito"]);
        $mercado->dados_conta->setIdDConta($oldmercado["fkdconta"]);
        $mercado->dados_conta->editar();
    }
    
    header("Location: ../../mercado?id=". $_SESSION["id"]);
