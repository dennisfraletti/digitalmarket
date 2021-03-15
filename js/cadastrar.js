// inputs que precisam ser mascarados
var inputs = ["cpf", "rg", "cep", "numero", "ncartao", "cvv", "celular", "celular2", "celular3", "cnpj", "celularcontato", "digito", "agencia", "conta"];
var masks = ["999.999.999-99", "99.999.999-9", "99999-999", "99999999", "9999.9999.9999.9999", "999", "(99) 99999-9999", "(99) 99999-9999", "(99) 99999-9999", "99.999.999/9999-99", "(99) 99999-9999", "9", "9999-99", "0000000000"];


// aplicando mascara nos inputs
for (let cont = 0; cont < inputs.length; cont++) 
    $("#" + inputs[cont]).mask(masks[cont]);


// quando a página carregar
$(document).ready(function() {
    $("#formcliente").submit(function(formcliente) {
        let prosseguir = true;
        let voltar = ["#tt-cad", "#tt-ddp", "#tt-ddb", "#tt-e"];

        
        let email =           document.getElementById("email").value;
        let confirmar_email = document.getElementById("confirmar_email").value;
        
        let senha =           document.getElementById("senha").value;
        let confirmar_senha = document.getElementById("confirmar_senha").value;
        
        let nome =            document.getElementById("nome").value;
        let celular =         document.getElementById("celular").value;
        let celular2 =        document.getElementById("celular2").value;
        let celular3 =        document.getElementById("celular3").value;
        let cpf =             document.getElementById("cpf").value;
        let rg =              document.getElementById("rg").value;

        let cep =             document.getElementById("cep").value;

        // let ncartao =         document.getElementById("ncartao").value;
        // let titular =         document.getElementById("titular").value;
        // let cvv =             document.getElementById("cvv").value;

        // se houver algum problema nos inputs endereço voltar para a área do titulo "endereco"
        if (!verificartxt(cep, 9)) {
            altura = 3;
            prosseguir = false;
        }
                
        // se houver algum problema nos inputs dados bancarios voltar para a área do titulo "dados bancarios"
        // if (!verificartxt(ncartao, 19) || !verificarnome(titular) || !verificartxt(cvv, 3)) {
        //     altura = 2;
        //     prosseguir = false;
        // }
        
        // se houver algum problema nos inputs dados pessoais voltar para a área do titulo "dados pessoais"
        if (!verificarnome(nome) || !verificartxt(celular, 15)  ||
            !verificartxt(celular2, 15) && !document.getElementById("celular2").disabled ||
            !verificartxt(celular3, 15) && !document.getElementById("celular3").disabled ||
            !verificarcpf(desmascararCpf(cpf)) || !verificartxt(rg, 12)) {
                altura = 1;
                prosseguir = false;
        }

        if ((!document.getElementById("celular2").disabled && (celular2 == celular || celular2 == celular3)) || 
            (!document.getElementById("celular3").disabled && (celular3 == celular || celular3 == celular2))) {
                altura = 1;
                prosseguir = false;
        }
        
        // se houver algum problema nos inputs email ou senha voltar para a área do titulo "cadastre-se"
        if (email != confirmar_email || !verificaremail(email) ||
            senha != confirmar_senha || !verificarsenha(senha, 8)) {
                altura = 0;
                prosseguir = false;
        }

        // se estiver algum campo errado
        if (!prosseguir) {
            formcliente.preventDefault();    
            $("html,body").animate({scrollTop: $(voltar[altura]).offset().top},"slow");          
        }
    });

    // quando o check "#semnumero" for alterado
    $("#semnumero").change(function() {
        if (document.getElementById("semnumero").checked) { 
            $("#numero").val("");  
            $("#numero").attr("disabled", true);
            $(".asterisco").hide();
            atualizarErro("numero", "", true);
        } else {
            $("#numero").attr("disabled", false);
            $(".asterisco").show();
        }
    });
});


// calcula forca da senha
function forcaSenha() {
    let senha = document.getElementById("senha").value;
    let forca = 0;

    if (senha.match(/[a-z]+/)) 
        forca += 20;
    if (senha.match(/[A-Z]+/)) 
        forca += 20;
    if (senha.match(/[0-9]+/)) 
        forca += 35;
    if (senha.match(/[@&%#!$.(*),_?{<:;>}=+§¬¢£|]/)) 
        forca += 25;

    atualizarForca(forca);
}


// muda as cores e a largura da barra de progresso
function atualizarForca(forca) {
    if (forca <= 30) 
        cor = "red";  //vermelho
    else if (forca <= 60)
        cor = "#ff8c00";  //laranja
    else if (forca <= 80)
        cor = "yellow";  //amarelo
    else
        cor = "#62ff00";  //verde
    forca += "%";

    $(".progresso").css("width", forca);
    $(".progresso").css("background-color", cor);
}


// desabilita todos os inputs referente ao endereco
function desabilitarInputsEndereco(habilitar) {
    let index = ["rua", "numero", "bairro", "cidade", "estado", "complemento", "semnumero"];

    for (let cont = 0; cont < index.length; cont++) 
        eval("$('#" + index[cont] + "').attr('disabled', " + !habilitar + ")");
}

// esconde input celular2 e celular3
function removerCelular(celular) {
    $(".c-celular" + celular).attr("id", "invisivel");
    $("#celular" + celular).attr("disabled", true);
    $("#celular" + celular).val("");
    atualizarErro("celular" + celular, "", true);
    ativarBotao();
}


// quando algum dos botões estiverem desativados ativa-se o botao add
function ativarBotao() {
    if ($(".c-celular2").attr("id") == "invisivel" || $(".c-celular3").attr("id") == "invisivel")
        $("#add-celular").attr("disabled", false);
}

/* === validação dos eventos onblur do formulário === */

// função atualizar campos, exibindo o a mensagem do erro no campo caso esteja errado
function atualizarErro(campo, mensagem, erro) {
    if (!erro) {
        $("#alert-" + campo).html("<i class='fas fa-exclamation-circle'></i> " + mensagem);
        $("#alert-" + campo).removeClass("invisivel");
    } else 
        $("#alert-" + campo).addClass("invisivel");
}

// verifica se email possui @ e .
function validarEmail(email) {
    if (verificaremail(email)) {
        atualizarErro("email", "", true);
        $("#confirmar_email").attr("disabled", false);
        $(".espaco-email").css("height", "15px");

    } else {
        atualizarErro("email", "E-mail inválido", false);
        $("#confirmar_email").attr("disabled", true);
        $("#confirmar_email").val("");
        $("#alert-confirmar_email").html("");
        $("#alert-confirmar_email").addClass("invisivel");
        $(".espaco-email").css("height", "0px");
        
        if (email.length == 0) 
            atualizarErro("email", "Campo obrigatório.", false);
    }


    if ($("#confirmar_email").val() != "") {
        if (email != $("#confirmar_email").val()) 
            atualizarErro("confirmar_email", "Os e-mails não coincidem.", false);
        else   
            atualizarErro("confirmar_email", "", true);
    }
}


// verifica se campo email é igual a confirmar_email
function validarConfirmarEmail(confirmar_email) {
    let email = document.getElementById("email").value;

    if (confirmar_email == email) 
        atualizarErro("confirmar_email", "", true);   
    else {
        atualizarErro("confirmar_email", "Os e-mails não coincidem.", false);
        if (confirmar_email.length == 0) 
            atualizarErro("confirmar_email", "Campo obrigatório.", false);
    }
}


// verifica se senha possui mais de 8 digitos
function validarSenha(senha) {
    if (verificarsenha(senha, 8)) {
        atualizarErro("senha", "", true);
        $("#confirmar_senha").attr("disabled", false);
        
    } else {
        atualizarErro("senha", "A senha deve conter no mínimo 8 caracteres", false);
        $("#confirmar_senha").attr("disabled", true);
        $("#confirmar_senha").val("");
        $("#alert-confirmar_senha").html("");
        $("#alert-confirmar_senha").addClass("invisivel");
        
        if (senha.length == 0) 
            atualizarErro("senha", "Campo obrigatório.", false);
    }

    if ($("#confirmar_senha").val() != "") {
        if (senha != $("#confirmar_senha").val()) 
            atualizarErro("confirmar_senha", "As senhas não coincidem.", false);
        else   
            atualizarErro("confirmar_senha", "", true);
    }
}


// verifica se campo senha coincide com campo confirmar_senha
function validarConfirmarSenha(confirmar_senha) {
    let senha = document.getElementById("senha").value;

    if (confirmar_senha == senha) 
        atualizarErro("confirmar_senha", "", true);   
    else {
        atualizarErro("confirmar_senha", "As senhas não coincidem.", false);
        if (confirmar_senha.length == 0) 
            atualizarErro("confirmar_senha", "Campo obrigatório.", false);
    }
}


// verifica se o usuário inseriu ao menos um sobrenome
function validarNome(nome) {
    if (verificarnome(nome)) 
        atualizarErro("nome", "", true);
    else {
        atualizarErro("nome", "Insira ao menos um sobrenome.", false);
        if (nome.length == 0) 
            atualizarErro("nome", "Campo obrigatório.", false);
    }
}


// verifica se celular possui 14 caracteres
function validarCelular(input, celular) {
    if (verificartxt(celular, 15)) 
        atualizarErro(input, "", true);
    else {
        atualizarErro(input, "Celular inválido.", false);
        if (celular.length == 0) 
            atualizarErro(input, "Campo obrigatório.", false);
    }

   let celular1 = document.getElementById("celular").value;
   let celular2 = document.getElementById("celular2").value;
   let celular3 = document.getElementById("celular3").value;
    

   if (celular2 == celular1 && verificartxt(celular2, 15) && verificartxt(celular1, 15))
       atualizarErro("celular2", "Celular repetido", false);

    if ((celular3 == celular1 && verificartxt(celular3, 15) && verificartxt(celular1, 15)) || (celular3 == celular2 && verificartxt(celular3, 15) && verificartxt(celular2, 15))) 
        atualizarErro("celular3", "Celular repetido", false);

    if (celular2 != celular1 && verificartxt(celular2, 15))
        atualizarErro("celular2", "", true);

    if (celular3 != celular2 && celular3 != celular1 && verificartxt(celular3, 15))
        atualizarErro("celular3", "", true);

}


// retirar pontos e traço do cpf
function desmascararCpf(cpf) {
    return cpf.replace(".", "").replace(".", "").replace("-", "");    
} 

// faz procedimento para verificar se cpf é valido
function validarCpf(cpf) {
    cpf = desmascararCpf(cpf);    

    if (verificarcpf(cpf))
        atualizarErro("cpf", "", true);
    else {
        atualizarErro("cpf", "CPF inválido.", false);
        if (cpf.length == 0) 
           atualizarErro("cpf", "Campo obrigatório.", false);
    }
}


// verifica se rg tem 12 caracteres
function validarRg(rg) {
    if (verificartxt(rg, 12))
        atualizarErro("rg", "", true);
    else {
        atualizarErro("rg", "RG inválido.", false);
        if (rg.length == 0) 
            atualizarErro("rg", "Campo obrigatório.", false);
    }
}


// indica o usuário a preencher os campos
function validarCamposEndereco(campo, valor) {
    if (valor.length == 0)
        atualizarErro(campo, "Campo obrigatório.", false);
    else 
        atualizarErro(campo, "", true);
}


// verifica se cartão possui 19 caracteres "9999.9999.9999.9999"
function validarCartao(ncartao) {
    if (verificartxt(ncartao, 19)) 
        atualizarErro("ncartao", "", true);
    else {
        atualizarErro("ncartao", "Cartão de crédito inválido.", false);
        if (ncartao.length == 0) 
            atualizarErro("ncartao", "Campo obrigatório.", false); 
    }
}


// validar titular faz a mesma verificação que o campo nome
function validarTitular(titular) {
    if (verificarnome(titular)) 
        atualizarErro("titular", "", true);
    else {
        atualizarErro("titular", "Insira ao menos um sobrenome.", false);
        if (titular.length == 0) 
            atualizarErro("titular", "Campo obrigatório.", false);
    }
}


// não permite campo vazio
function validarValidadeMes(mes_validade) {
    if (mes_validade != "") 
        atualizarErro("mes_validade", "", true);
    else 
        atualizarErro("mes_validade", "Campo obrigatório.", false);
}


// não permite campo vazio
function validarValidadeAno(ano_validade) {
    if (ano_validade != "") 
        atualizarErro("ano_validade", "", true);
    else 
        atualizarErro("ano_validade", "Campo obrigatório.", false);
}


// verifica se cvv tem 3 caracteres
function validarCvv(cvv) {
    if (verificartxt(cvv, 3)) 
        atualizarErro("cvv", "", true);
    else {
        atualizarErro("cvv", "CVV inválido.", false);
        if (cvv.length == 0)
            atualizarErro("cvv", "Campo obrigatório.", false);
    }
}

// verifica se o campo banco esta vazio 
function validarBanco(banco) {
    if (banco.length == 0)
        atualizarErro("banco", "Campo obrigatório.", false);
    else 
        atualizarErro("banco", "", true);
}

// verifica se o campo agencia esta vazio 
function validarAgencia(agencia) {
    if (agencia.length == 0)
        atualizarErro("agencia", "Campo obrigatório.", false);
    else { 
        atualizarErro("agencia", "", true);
        if (agencia.length < 4) 
            atualizarErro("agencia", "Conta inválida.", false);
    }
}


// verifica se o campo conta esta vazio 
function validarConta(conta) {
    if (conta.length == 0)
        atualizarErro("conta", "Campo obrigatório.", false);
    else { 
        if (conta.length < 10) 
            atualizarErro("conta", "Conta inválida.", false);

        else if (document.getElementById("digito").value != "") 
            atualizarErro("conta", "", true);
    }
}


// verifica se o campo digito esta vazio 
function validarDigito(digito) {
    if (digito.length == 0)
        atualizarErro("conta", "Campo dígito vazio.", false);
        
    else if (document.getElementById("conta").value.length == 10)
        atualizarErro("conta", "", true);
}


// quando clicar no botao adiciona os inputs celular2 e celular3
function adicionarCelular() {
    let celular2 = $(".c-celular2");
    let celular3 = $(".c-celular3");

    if (celular2.attr("id") == "" && celular3.attr("id") == "") 
        $("#add-celular").attr("disabled", true);
    else 
        $("#add-celular").attr("disabled", false);

        
    if (celular2.attr("id") == "invisivel") {
        celular2.attr("id", "");
        $("#celular2").attr("disabled", false);
    
    } else {
        celular3.attr("id", "");
        $("#celular3").attr("disabled", false);
        $("#add-celular").attr("disabled", true);
    }
}


