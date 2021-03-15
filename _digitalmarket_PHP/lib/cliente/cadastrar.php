<?php 

    if (!$_POST) {
        echo "<script>window.history.go(-1)</script>"; 
        die;
    }
    
    foreach ($_POST as $key => $value)
        $$key = $value;

    require_once "../../classes/Cliente.php";

    $cliente = new Cliente();

    $cliente->pessoa->setEmail($email);
    $qtd = $cliente->pessoa->selecionar()["qtd"];

    if ($qtd > 0) {
        echo "<script>alert('E-mail já cadastrado!'); location.href = '../../cadastrar/cliente'</script>";
        die;
    }
    
    // insert pessoa
    $cliente->pessoa->setNome($nome);
    $cliente->pessoa->setEmail($email);
    $cliente->pessoa->setSenha($senha);
    $cliente->pessoa->setCpf($cpf);
    $cliente->pessoa->setRg($rg);
    $pessoa = $cliente->pessoa->cadastrar();

    // insert celular
    $cliente->pessoa->celular->setFkPessoa($pessoa["id"]);
    $cliente->pessoa->celular->setCelular($celular);    
    $cliente->pessoa->celular->cadastrar();

    if (isset($celular2)) {
        $cliente->pessoa->celular->setCelular($celular2);    
        $cliente->pessoa->celular->cadastrar();
    }

    if (isset($celular3)) {
        $cliente->pessoa->celular->setCelular($celular3);    
         $cliente->pessoa->celular->cadastrar();
    }
    
    // insert endereco
    $numero = (isset($numero)) ? $numero : "S/N"; 
    
    $cliente->endereco->setCep($cep);
    $cliente->endereco->setRua($rua);
    $cliente->endereco->setNumero($numero);
    $cliente->endereco->setBairro($bairro);
    $cliente->endereco->setCidade($cidade);
    $cliente->endereco->setEstado($estado);
    $cliente->endereco->setComplemento($complemento);
    $endereco = $cliente->endereco->cadastrar();

    
    // insert pessoa_endereco
    $cliente->pessoa_endereco->setFkPessoa($pessoa["id"]);
    $cliente->pessoa_endereco->setFkEndereco($endereco["idendereco"]);
    $cliente->pessoa_endereco->cadastrar();


    // insert dados_cartao
    // $mes_validade = ($mes_validade >= 1 && $mes_validade <= 9) ? "0". $mes_validade : $mes_validade;

    // $cliente->dados_cartao->setNCartao($ncartao);
    // $cliente->dados_cartao->setTitular($titular);
    // $cliente->dados_cartao->setValidade($ano_validade."-".$mes_validade."-00");
    // $cliente->dados_cartao->setCvv($cvv);
    // $dados_cartao = $cliente->dados_cartao->cadastrar();

    // insert cliente
    $cliente->pessoa->setId($pessoa["id"]);
    $cliente->endereco->setIdEndereco($endereco["idendereco"]);
    $cliente->cadastrar();

    
    // logando usuário
    $cliente->pessoa->setEmail($email);
    $cliente->pessoa->setSenha($senha);

    $entrar = $cliente->pessoa->entrar();

    header("Location: ../../");

    