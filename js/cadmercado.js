$(document).ready(function() {
    // quando o formulario for enviado
    $("#formmercado").submit(function(formmercado) {
        let prosseguir = true;
        let voltar = ["#tt-cad", "#tt-ddp", "#tt-dm", "#tt-e", "#tt-ddb"];
        
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
        
        let razaosocial =     document.getElementById("razaosocial").value;
        let nomefantasia =    document.getElementById("nomefantasia").value;
        let cnpj =            document.getElementById("cnpj").value;
        let logo =            document.getElementById("logo");
        // let plano =           document.getElementById("plano").value;

        let agencia =         document.getElementById("agencia").value;
        let conta =         document.getElementById("conta").value;

        
        if (agencia.length < 4 || conta.length < 10) {
            altura = 4;
            prosseguir = false;
        }

        // se houver algum problema nos inputs endereço voltar para a área do titulo "endereco"
        if (!verificartxt(cep, 9)) {
            altura = 3;
            prosseguir = false;
        }
        
        // se houver algum problema nos inputs dados mercado voltar para a área do titulo "dados mercado"   
        if (!(logo.files.length === 0)) {
            let tamanhoArquivo = parseInt(logo.files[0].size);
            let listLogo = logo.value.split(".");
            let extensao = listLogo[listLogo.length - 1].toLowerCase();
        
            if (!(extensao == "jpg" || extensao == "jpeg" || extensao == "png" || 
                extensao == "bmp" || extensao == "gif" || extensao == "jfif") || tamanhoArquivo > 1000000) {
                    prosseguir = false;
                    altura = 2;        
            }
        } else {
            atualizarErro("logo", "Campo obrigatório.", false);
            prosseguir = false;
            altura = 2;
        }

        if (!verificarcnpj(cnpj)) {
            prosseguir = false;
            altura = 2;
        }

        // if (plano == "") {
        //     atualizarErro("plano", "Campo obrigatório.", false);
        //     prosseguir = false;
        //     altura = 2;
        // }
  

        // se houver algum problema nos inputs dados proprietario voltar para a área do titulo "dados pessoais"
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
            formmercado.preventDefault();          
            $("html,body").animate({scrollTop: $(voltar[altura]).offset().top},"slow");    
        }        
    });


    // verifica se arquivo é valido quando o usuário anexa
    $("#logo").change(function() {
        let arquivo = document.getElementById("logo");
        let tamanhoArquivo = parseInt(arquivo.files[0].size);        
        
        let listArquivo = arquivo.value.split(".");
        let extensao = listArquivo[listArquivo.length - 1].toLowerCase();
        let erro = false, msgerro;


        if (tamanhoArquivo >= 1000000) {
            msgerro = "Tamanho excede a 1MB.";
            erro = true;
        }

        if (!(extensao == "jpg" || extensao == "jpeg" || extensao == "png" || extensao == "bmp" || extensao == "gif" || extensao == "jfif")) {
            msgerro = "Extensão inválida.";   
            erro = true;
        }

        if (erro) {
            atualizarErro("logo", msgerro, false);
            atualizarUpload(false);
        
        } else {
            const file = $(this)[0].files[0];
            const fileReader = new FileReader();
            
            fileReader.onloadend = function() {
                atualizarUpload(true, fileReader.result);
            }    

            atualizarErro("logo", "", true);
            fileReader.readAsDataURL(file);
        }        
    });
    
    $("input[name='plano']").change(function(){
        alert($("input[name='plano']").val("a"));
    });
});


// verifica se razaosocial está vazia
function validarRazaoSocial(razaosocial) {
    if (razaosocial == "")
        atualizarErro("razaosocial", "Campo obrigatório.", false);
    else
        atualizarErro("razaosocial", "", true);
}

// verifica se nomefantasia está vazia
function validarNomeFantasia(nomefantasia) {
    if (nomefantasia == "")
        atualizarErro("nomefantasia", "Campo obrigatório.", false);
    else
        atualizarErro("nomefantasia", "", true);
}


// verifica se o cnpj inserido é valido
function validarCnpj(cnpj) {
    if (verificarcnpj(cnpj)) 
        atualizarErro("cnpj", "", true);
    else {
        atualizarErro("cnpj", "CNPJ inválido.", false);
        if (cnpj.length == 0) 
            atualizarErro("cnpj", "Campo obrigatório.", false);
    }
}

// insere ou remove a imagem do input logo
function atualizarUpload(ok, img) {
    if (ok) {
        $("#imglogo").attr("src", img);
        $(".lbl-addlogo span").html("Alterar logo");
        $("#imglogo").removeClass("invisivel");
        $(".rm-img").removeClass("invisivel");

    } else {
        $("#imglogo").attr("src", "");
        $(".lbl-addlogo span").html("Adicionar logo");
        $("#imglogo").addClass("invisivel");
        $(".rm-img").addClass("invisivel");
        $("#logo").val("");
    }
}


// botão que remove o arquivo do input file
function apagaLogo() {
    $("#imglogo").attr("src", "");
    $(".lbl-addlogo span").html(" Adicionar logo");
    $("#imglogo").addClass("invisivel");
    $(".rm-img").addClass("invisivel");
    $("#logo").val("");
    atualizarErro("logo", "", true);
}


function atualizarRadioTipoConta() {
    if (document.getElementById("pf").checked) {
        $(".lbl-pf").removeClass("lbl-tipoconta");
        $(".lbl-pf").addClass("lbl-tipoconta-checked");
        $("#rd-pf").removeClass("radio-design");
        $("#rd-pf").addClass("radio-design-checked");
            
    } else {
        $(".lbl-pf").removeClass("lbl-tipoconta-checked");
        $(".lbl-pf").addClass("lbl-tipoconta");
        $("#rd-pf").removeClass("radio-design-checked");
        $("#rd-pf").addClass("radio-design");
    }


    if (document.getElementById("pj").checked) {
        $(".lbl-pj").removeClass("lbl-tipoconta");
        $(".lbl-pj").addClass("lbl-tipoconta-checked");
        $("#rd-pj").removeClass("radio-design");
        $("#rd-pj").addClass("radio-design-checked");

    } else {
        $(".lbl-pj").removeClass("lbl-tipoconta-checked");
        $(".lbl-pj").addClass("lbl-tipoconta");
        $("#rd-pj").removeClass("radio-design-checked");
        $("#rd-pj").addClass("radio-design");
    }
}


//  funcão responsavel por atualizar o input type radio "tipoconta" com os valores do usuário
function atualizarTipoConta(campo, valor) {
    if (campo == "nome" && verificarnome(valor))
        $("#span-nome").html(valor);
    else {
        if (campo == "nome") 
            $("#span-nome").html("");
    }

    if (campo == "cpf" && verificarcpf(desmascararCpf(valor)))
        $("#span-cpf").html(valor);
    else {
        if (campo == "cpf") 
            $("#span-cpf").html("");
    }

    if (campo == "razaosocial" && valor != "") 
        $("#span-razaosocial").html(valor);
    else {
        if (campo == "razaosocial") 
            $("#span-razaosocial").html("");
    }

    if (campo == "cnpj" && verificarcnpj(valor)) 
        $("#span-cnpj").html(valor);
    else {
        if (campo == "cnpj") 
            $("#span-cnpj").html("");
    }
}

function alterarPlano(campo) {
    if (campo == "basico") {
        $(".amarelo .fa-check").removeClass("invisivel");
        $(".roxo .fa-check").addClass("invisivel");
        $("#btn-basico").html("Selecionado");
        $("#btn-entrega").html("Escolher");
        $(".bloco-basico, #btn-basico").css("cursor", "default");
        $(".bloco-entrega, #btn-entrega").css("cursor", "pointer");
        $(".bloco-basico").attr("id", "");
        $("#btn-basico").attr("disabled", true);
        $("#btn-entrega").attr("disabled", false);
        $("#plano").val("0");
        
        if ($(".bloco-entrega").attr("id") != "sombra")
            $(".bloco-entrega").attr("id", "sombra");
    
    } else if (campo == "entrega") {
        $(".roxo .fa-check").removeClass("invisivel");
        $(".amarelo .fa-check").addClass("invisivel");
        $("#btn-entrega").html("Selecionado");
        $("#btn-basico").html("Escolher");
        $(".bloco-basico, #btn-basico").css("cursor", "pointer");
        $(".bloco-entrega, #btn-entrega").css("cursor", "default");
        $(".bloco-entrega").attr("id", "");
        $("#btn-entrega").attr("disabled", true);
        $("#btn-basico").attr("disabled", false);
        $("#plano").val("1");

        if ($(".bloco-basico").attr("id") != "sombra")
             $(".bloco-basico").attr("id", "sombra");
    }

    atualizarErro("plano", "", true);
}

