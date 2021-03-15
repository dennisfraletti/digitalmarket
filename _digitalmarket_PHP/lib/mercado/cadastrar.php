<?php 

    if (!$_POST) {
        echo "<script>window.history.go(-1)</script>"; 
        die;
    }
    
    foreach ($_POST as $key => $value)
        $$key = $value;
      
    require_once "../../classes/Mercado.php";

    $mercado = new Mercado();
    $mercado->pessoa->setEmail($email);
    $qtd = $mercado->pessoa->selecionar()["qtd"];

    if ($qtd > 0) {
        echo "<script>alert('E-mail já cadastrado!'); location.href = '../../cadastrar/mercado'</script>";
        die;
    }


    // insert pessoa
    $mercado->pessoa->setNome($nome);
    $mercado->pessoa->setEmail($email);
    $mercado->pessoa->setSenha($senha);
    $mercado->pessoa->setCpf($cpf);
    $mercado->pessoa->setRg($rg);
    $pessoa = $mercado->pessoa->cadastrar();

    // insert celular
    $mercado->pessoa->celular->setFkPessoa($pessoa["id"]);
    $mercado->pessoa->celular->setCelular($celular);    
    $mercado->pessoa->celular->cadastrar();
    
    if (isset($celular2)) {
        $mercado->pessoa->celular->setCelular($celular2);    
        $mercado->pessoa->celular->cadastrar();
    }
    
    if (isset($celular3)) {
        $mercado->pessoa->celular->setCelular($celular3);    
        $mercado->pessoa->celular->cadastrar();
    }
    

    // insert endereco
    $numero = (isset($numero)) ? $numero : "S/N"; 
    
    $mercado->endereco->setCep($cep);
    $mercado->endereco->setRua($rua);
    $mercado->endereco->setNumero($numero);
    $mercado->endereco->setBairro($bairro);
    $mercado->endereco->setCidade($cidade);
    $mercado->endereco->setEstado($estado);
    $mercado->endereco->setComplemento($complemento);
    $endereco = $mercado->endereco->cadastrar();


    // insert pessoa_endereco
    $mercado->pessoa_endereco->setFkPessoa($pessoa["id"]);
    $mercado->pessoa_endereco->setFkEndereco($endereco["idendereco"]);
    $mercado->pessoa_endereco->cadastrar();
    

    // insert dados_conta
    $beneficiario = ($tipoconta == "pf") ? $nome : $razaosocial;
    $mercado->dados_conta->setBeneficiario($beneficiario);

    $documento = ($tipoconta == "pf") ? $cpf : $cnpj;
    $mercado->dados_conta->setDocumento($documento);
    $mercado->dados_conta->setBanco($banco);
    $mercado->dados_conta->setAgencia($agencia);
    $mercado->dados_conta->setConta($conta);
    $mercado->dados_conta->setDigito($digito);
    $dados_conta = $mercado->dados_conta->cadastrar();


    // insert mercado
    if ($_FILES) {
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
            
            mkdir("../../uploads/mercado/".$pessoa["id"]);
            
            if (!move_uploaded_file($logo["tmp_name"], "../../uploads/mercado/".$pessoa["id"]."/".$newnome)) {            
                echo "<script>alert('Ocorreu algum erro com o upload!'); window.history.go(-1)</script>";                    
                die;

            } else 
                $logo = $newnome;

        } else {
            echo "<script>alert('Arquivo inválido!'); window.history.go(-1)</script>"; 
            die;
        }
    } 

    $mercado->pessoa->setId($pessoa["id"]);    
    $mercado->setRazaoSocial($razaosocial);
    $mercado->setNomeFantasia($nomefantasia);
    $mercado->setCnpj($cnpj);
    $mercado->setLogo($logo);
    $mercado->endereco->setIdEndereco($endereco["idendereco"]);
    // $mercado->setPlano($plano);
    $mercado->dados_conta->setIdDConta($dados_conta["iddconta"]);
    
    $mercado->cadastrar();

        
    mkdir("../../uploads/mercado/".$pessoa["id"]."/produtos");
    
    // logando usuário
    $mercado->pessoa->setEmail($email);
    $mercado->pessoa->setSenha($senha);

    $entrar = $mercado->pessoa->entrar();

    header("Location: ../../mercado?id=" . $_SESSION["id"]);
