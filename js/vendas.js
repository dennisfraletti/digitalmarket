$(document).ready(function() {
    // aplicando mascara aos campos input
    $("#preco").mask("#.##0,00", {reverse: true});
    $("#estoque").mask("9999");
    $("#desconto").mask("###%", {reverse: true});
    $("#precofinal").mask("#.##0,00", {reverse: true});

    // habilitando os campos na hora que for enviado
    $("#cadastrarProduto").submit(function() {
        $("#titulo").attr("disabled", false);
        $("#precofinal").attr("disabled", false);
    });

    // mostrar o placeholder no input quando estiver focado
    $("#preco").focus(function() {
        setTimeout(function(){ $("#preco").attr("placeholder", "Insira somente números"); }, 300);
    });

    $("#desconto").focus(function() {
        setTimeout(function(){ $("#desconto").attr("placeholder", "Insira um número entre 1 e 100"); }, 300);
    });

    // esconder placeholder quando não estiver focado
    $("#preco").blur(function() { $("#preco").attr("placeholder", ""); });

    $("#desconto").blur(function() { $("#desconto").attr("placeholder", ""); });


    // quando o input[type='file'] for alterado
    $("#img").change(function() {
        document.getElementById("listagem-imagens").innerHTML = "";
        document.getElementById("erro").innerHTML = "";

        let arquivo = document.getElementById("img");
        let maxlength = 0;
        let imagenscorretas = 0, imagenserradas = 0;

        
        maxlength = (arquivo.files.length > 8) ? 8 : arquivo.files.length; 

        // para cada arquivo que for selecionado no input[type='file]
        for (let i = 0; i < maxlength; i++) {
            let tamanhoArquivo = parseInt(arquivo.files[i].size);        
            let listArquivo = arquivo.files[i].name.split(".");
            let extensao = listArquivo[listArquivo.length - 1].toLowerCase();
            let erro = false;

            // se arquivo for maior que 1 MB
            if (tamanhoArquivo >= 1000000)
                erro = true;
            
            // se a extensão for inválida 
            if (!(extensao == "jpg" || extensao == "jpeg" || extensao == "png" || extensao == "bmp" || extensao == "gif" || extensao == "jfif"))
                erro = true;
        
            // se o arquivo for menor que 600 KB e tiver extensão válida
            if (!erro) {
                const file = $(this)[0].files[i];
                const fileReader = new FileReader();
                
                
                fileReader.onloadend = function() {
                    document.getElementById("listagem-imagens").innerHTML += "<div id='img-" + i + "' class='item-imagem' onclick='removerImagem(this.id)'><img src='" + fileReader.result + "' class='imagem'/><button class='btn-excluir' type='button'><span class='borda-btn-exluir'><i class='fas fa-trash-alt lixo'></i></span></button></div>";
                    document.getElementById("listagem-imagens").innerHTML += "<input type='hidden' name='file" + i + "' id='img-prod-" + i +"'/>";
                }

                fileReader.readAsDataURL(file);   
                
                imagenscorretas++;
            
            } else {
                if (imagenserradas == 0) {
                    $("#erro").html("");
                    $("#erro").removeClass("invisivel");
                    document.getElementById("erro").innerHTML += "<i class='fas fa-exclamation-circle ico-erro'></i>";
                    document.getElementById("erro").innerHTML += "<div id='msgs-erro'></div>";
                }

                erroarquivo = false;

                if (!(extensao == "jpg" || extensao == "jpeg" || extensao == "png" || extensao == "bmp" || extensao == "gif" || extensao == "jfif")) {
                    document.getElementById("msgs-erro").innerHTML += "<span>Arquivo " + (i + 1) + ": Extensão ." + extensao + " inválida! </span>";
                    erroarquivo = true;
                }
                
                if (tamanhoArquivo >= 1000000 && !erroarquivo)
                    document.getElementById("msgs-erro").innerHTML += "<span>Arquivo " + (i + 1) + ": Tamanho arquivo " + Math.round(tamanhoArquivo / 1000) + " KB excede limite de 1 MB</span>";
            
                imagenserradas++;
            }
        }
        
        if (imagenscorretas > 0)
            $("#listagem-imagens").removeClass("invisivel");
        else {
            if ($("#listagem-imagens").attr("css") == "") 
                $("#listagem-imagens").addClass("invisivel");
        }    
    });

    $(window).scroll(function() {
        let y = $(this).scrollTop();
        
        if (y > 240) 
            $("#link-voltar").css("color", "#b7b7b7");
        else 
            $("#link-voltar").css("color", "white");    
    });
});

function removerImagem(id) {
    
    setTimeout(function() {
        
        $("#" + id).remove();
        numeroid = id.split("-");
        $("#img-prod-" + numeroid[1]).remove();
    
        if ($("#listagem-imagens").html() == "") {
            $("#listagem-imagens").addClass("invisivel");
            $("#btn-3").attr("disabled", true);
        }

    }, 200);
}

function iniciarCadastro() {
    $(".boas-vindas").html("<span>Para começar,</span><br/><span>digite alguns dados do produto</span><a href='vendas' id='link-voltar'><div><i class='fas fa-chevron-left'></i><span id='sp-voltar'>&nbsp;Voltar&nbsp;&nbsp;&nbsp;&nbsp;</span></div></a>");
    $(".tela-inicio").addClass("invisivel");
    $(".tela-1").removeClass("invisivel");
    document.getElementById("titulo").focus();
    $("html,body").animate({scrollTop: $(".tela-1").offset().top},"slow");
}

function atualizarQtdInput(span_qtd, campo) {
    $("#" + span_qtd).html(campo.length);
}

function habilitarBtnProximo(etapa, valor) {
    if (etapa == 1 && valor.length > 0)
        $("#btn-" + etapa).attr("disabled", false);
    else 
        $("#btn-" + etapa).attr("disabled", true);


    if (etapa == 2) 
        $("#btn-2").attr("disabled", false);


    if (etapa == 3 && $("listagem-imagens").html() != "")
        $("#btn-3").attr("disabled", false);
    else
        $("#btn-3").attr("disabled", true);

    
    preco = document.getElementById("preco").value;
    preco = preco.replace(".", "").replace(".", "").replace(".", "").replace(",", ".");
    
    if (etapa == 4 && parseFloat(preco) > 0 && parseInt(document.getElementById("estoque").value) > 0) {
        $("#btn-4").attr("disabled", false);
    } else
        $("#btn-4").attr("disabled", true);
}

function proximoForm(etapa) {
    if (etapa == 1) {
        $(".tela-1").css("opacity", ".6");
        $(".tela-1").css("pointer-events", "none");
        $("#titulo").attr("disabled", true);
        $("#btn-1").attr("disabled", true);
        $(".tela-2").removeClass("invisivel");
        $("html,body").animate({scrollTop: $(".tela-2").offset().top},"slow");           
    
    } else if (etapa == 2) {
        $(".tela-2").css("opacity", ".6");
        $(".tela-2").css("pointer-events", "none");
        $("#btn-2").attr("disabled", true);
        $(".tela-3").removeClass("invisivel");
        $("html,body").animate({scrollTop: $(".tela-3").offset().top},"slow");    
        $("body").css("padding-bottom", "165px");   
    
    } else if (etapa == 3) {
        $(".tela-3").css("opacity", ".6");
        $(".tela-3").css("pointer-events", "none");
        $("#btn-3").attr("disabled", true);
        $(".tela-4").removeClass("invisivel");
        document.getElementById("preco").focus();
        $("html,body").animate({scrollTop: $(".tela-4").offset().top},"slow");  
    }
}

function atualizarBtnDesconto(opa) {
    if (document.getElementById("desconto").disabled == true) {
        $("#desconto").attr("disabled", false);
        $("#semdesc ~label").html("&nbsp;Remover desconto");
        if (!opa)
            document.getElementById("desconto").focus();
            
        $("#campo-pf").removeClass("invisivel");
    } else {
        $("#desconto").attr("disabled", true);
        $("#semdesc ~label").html("&nbsp;Aplicar desconto");
        $("#desconto").val("");
        $("#campo-pf").addClass("invisivel");
        $("#precofinal").val("");
    }
}

function atualizarPrecoFinal() {
    let preco = document.getElementById("preco").value; 
    let desconto = document.getElementById("desconto").value;
    
    preco = preco.replace(".", "").replace(".", "").replace(".", "").replace(",", ".");
    desconto = desconto.replace("%", "");
    
    if (parseInt(desconto) >= 1 && parseInt(desconto) <= 100) {
        if (preco.length > 0 && desconto.length > 0 ) {
            preco = parseFloat(preco);
            desconto = parseInt(desconto);

            let precofinal = (preco - ((preco * desconto) / 100));

            let resultado = precofinal.toLocaleString("pt-br", {minimumFractionDigits: 2});

            listResultado = resultado.split(",");
            listDecimal = listResultado[1].split("");

            if (listDecimal.length > 2) 
                resultado = listResultado[0] + "," + listDecimal[0] + listDecimal[1]; 

            document.getElementById("precofinal").value = resultado;
        }
    
    } else {

        if (desconto.length > 0)
            document.getElementById("precofinal").value = "desconto inválido";
    }
}

function confirmarDelete(visib, idproduto) {
    if (visib) {
        $("#btnprod-" + idproduto).attr("css", "");
        $(".bg-alvorecer").removeClass("invisivel");
        $("#confirmar-delete").removeClass("invisivel");
    } else {
        $(".bg-alvorecer").addClass("invisivel");
        $("#confirmar-delete").addClass("invisivel");
    }
    
    $(".btn-deletar").attr("onclick", "window.location='lib/produto/deletar?idproduto=" + idproduto + "'");
}

function atualizarQtdRm() {
    let qtdrm = parseInt($("#qtdrm").val());
    qtdrm++;
    $("#qtdrm").val(qtdrm);

    let html = document.getElementById("listagem-imagens");
    
    if (html.children.length <= 2)
        $("#btn-salvar").attr("disabled", true);
}

function verificarCampoImg() {
    if (parseInt(document.getElementById("img").files.length) > 0) 
        $("#btn-salvar").attr("disabled", false);
    else
        $("#btn-salvar").attr("disabled", true);
}

function transformarDinheiro(money) {
    let newmoney;
    if (Number.isInteger(money))
        newmoney = money + "00";    
    else {
        money = money.toString();
        let decimal = money.split(".")[1];
        newmoney = (decimal.length == 1) ? money + "0" : money;                     
    }
    
    return newmoney;
}