// inputs que precisam ser mascarados
var inputs = ["ncelular1", "ncelular2", "ncelular3"];
var masks = ["(99) 99999-9999", "(99) 99999-9999", "(99) 99999-9999"];

// aplicando mascara nos inputs
for (let cont = 0; cont < inputs.length; cont++) 
    $("#" + inputs[cont]).mask(masks[cont]);


$(document).ready(function() {

    $("#editarmercado").submit(function(editarmercado) {
        let prosseguir = true;

        let logo =            document.getElementById("logo");
        let razaosocial =     document.getElementById("razaosocial").value;
        let nomefantasia =    document.getElementById("nomefantasia").value;
        let cnpj =            document.getElementById("cnpj").value;
        let celular =         document.getElementById("ncelular1").value;
        let celular2 =        document.getElementById("ncelular2").value;
        let celular3 =        document.getElementById("ncelular3").value;
        
    
        // se houver algum problema nos inputs dados mercado voltar para a área do titulo "dados mercado"   
        if (logo.files.length === 1) {
            let tamanhoArquivo = parseInt(logo.files[0].size);
            let listLogo = logo.value.split(".");
            let extensao = listLogo[listLogo.length - 1].toLowerCase();
    
            if (!(extensao == "jpg" || extensao == "jpeg" || extensao == "png" || 
                extensao == "bmp" || extensao == "gif" || extensao == "jfif") || tamanhoArquivo > 1000000) {
                    prosseguir = false;
            }
        }

        if (!verificarcnpj(cnpj) || !verificartxt(celular, 15) ||    
            !verificartxt(celular2, 15) && !document.getElementById("ncelular2").disabled ||
            !verificartxt(celular3, 15) && !document.getElementById("ncelular3").disabled) {
                prosseguir = false;
        }
        
        if ((!document.getElementById("ncelular2").disabled && (celular2 == celular || celular2 == celular3)) || 
            (!document.getElementById("ncelular3").disabled && (celular3 == celular || celular3 == celular2))) {
                altura = 1;
                prosseguir = false;
        }

        // se estiver algum campo errado
        if (!prosseguir) 
            editarmercado.preventDefault();           
    });


    $("#editarpessoa").submit(function(editarpessoa){
        let prosseguir = true;
        let nome =        document.getElementById("nome").value;
        let cpf =         document.getElementById("cpf").value;
        let rg =          document.getElementById("rg").value;

        
        if (!verificarcpf(desmascararCpf(cpf)) || !verificartxt(rg, 12) || !verificarnome(nome))
            prosseguir = false;

        if (!prosseguir)
            editarpessoa.preventDefault();           
    });


    $("#editarendereco").submit(function(editarendereco){
        let prosseguir = true;
        let cep = document.getElementById("cep").value;


        if (!verificartxt(cep, 9)) 
            prosseguir = false;

        if (!prosseguir)
            editarendereco.preventDefault();           
    });
    
    
    $("#editarddconta").submit(function(editarddconta) {
        let prosseguir = false;
        let agencia =       document.getElementById("agencia").value;
        let conta =         document.getElementById("conta").value;

        if (agencia.length < 4 || conta.length < 10) 
            prosseguir = false;
        
        if (!prosseguir)
            editarendereco.preventDefault();
    });



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
            
        } else {
            const file = $(this)[0].files[0];
            const fileReader = new FileReader();
                
            fileReader.onloadend = function() {
                $(".edit-logo").attr("src", fileReader.result);
            }    
    
            atualizarErro("logo", "", true);
            fileReader.readAsDataURL(file);
        }
    });

    // quando o check "#semnumero" for alterado
    $("#semnumero").change(function() {
        if (this.checked) { 
            $("#numero").attr("disabled", true);
            $("#numero").val("");  
        } else {
            $("#numero").attr("disabled", false);
        }
    });
});

function visibilidadeModalPerfil(visib) {
    if (visib) {
        $(".modal-editar-perfil").css("bottom", "21vh");
        $(".bg-alvorecer").removeClass("invisivel");

        if ($("#c-celular2").attr("class") == "" && $("#c-celular3").attr("class") == "")
            $("#add-celular").attr("disabled", true);

        if ($("#numero").val() == "") {
            $("#numero").attr("disabled", true);
            $("#semnumero").attr("checked", true);
        }

        if (document.getElementById("documento").value.length == 18) {
            $("#pj").attr("checked", true);
            atualizarRadioTipoConta();
        }

        if (document.getElementById("plano").value == 0)
            alterarPlano("basico");
        else
            alterarPlano("entrega");
    } else {
        $(".modal-editar-perfil").css("bottom", "-100%");
        $(".bg-alvorecer").addClass("invisivel");
    }
}


function atualizarModal(tela) {
    // for que limpa todos os campos, deixando todos invisiveis, com classe "item-select" e habilitados 
    for (let index = 0; index <= 5; index++) {
        if ($("#tela-" + index).attr("class") == "")
            $("#tela-" + index).addClass("invisivel");

        if ($("#li-" + index).attr("class") == "item-selected") {
            $("#li-" + index).removeClass("item-selected");
            $("#li-" + index).addClass("item-edit");
        }
    }

    if ($("#tela-delete").attr("class") == "") 
        $("#tela-delete").addClass("invisivel");

    $("#tela-" + tela).removeClass("invisivel");
    $("#li-" + tela).removeClass("item-edit");
    $("#li-" + tela).addClass("item-selected");
}

// função atualizar campos, exibindo o a mensagem do erro no campo caso esteja errado
function atualizarErro(campo, mensagem, erro) {
    if (!erro) {
        $("#alert-" + campo).html("<i class='fas fa-exclamation-circle'></i> " + mensagem);
        $("#alert-" + campo).removeClass("invisivel");
    } else 
        $("#alert-" + campo).addClass("invisivel");
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

   let ncelular1 = document.getElementById("ncelular1").value;
   let ncelular2 = document.getElementById("ncelular2").value;
   let ncelular3 = document.getElementById("ncelular3").value;
    

   if (ncelular2 == ncelular1 && verificartxt(ncelular2, 15) && verificartxt(ncelular1, 15))
       atualizarErro("ncelular2", "Celular repetido", false);

    if ((ncelular3 == ncelular1 && verificartxt(ncelular3, 15) && verificartxt(ncelular1, 15)) || (ncelular3 == ncelular2 && verificartxt(ncelular3, 15) && verificartxt(ncelular2, 15))) 
        atualizarErro("ncelular3", "Celular repetido", false);

    if (ncelular2 != ncelular1 && verificartxt(ncelular2, 15))
        atualizarErro("ncelular2", "", true);

    if (ncelular3 != ncelular2 && ncelular3 != ncelular1 && verificartxt(ncelular3, 15))
        atualizarErro("ncelular3", "", true);
}

function ativarBotao() {
    if ($("#c-celular2").attr("class") == "invisivel" || $("#c-celular3").attr("class") == "invisivel")
        $("#add-celular").attr("disabled", false);
}

// quando clicar no botao adiciona os inputs celular2 e celular3
function adicionarCelularEdit() {
    let celular2 = $("#c-celular2");
    let celular3 = $("#c-celular3");

    if (celular2.attr("class") == "" && celular3.attr("class") == "") 
        $("#add-celular").attr("disabled", true);
    else 
        $("#add-celular").attr("disabled", false);

        
    if (celular2.attr("class") == "invisivel") {
        celular2.attr("class", "");
        $("#ncelular2").attr("disabled", false);
    
    } else {
        celular3.attr("class", "");
        $("#ncelular3").attr("disabled", false);
        $("#add-celular").attr("disabled", true);
    }
}


function removerCelularEdit(celular) {
    $("#c-celular" + celular).attr("class", "invisivel");
    $("#ncelular" + celular).attr("disabled", true);
    $("#ncelular" + celular).val("");
    atualizarErro("ncelular" + celular, "", true);
    ativarBotao();
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

function habilitarBotaoSalvar(botao) {
    $("#botao-" + botao).attr("disabled", false);
}

function confirmarDelete() {
    $("#tela-0").addClass("invisivel");
    $("#tela-delete").removeClass("invisivel"); 
}

