$("#cep").mask("99999-999");
$("#grana").mask("#.##0,00", {reverse: true});

$(document).ready(function() {
    listarTarefa();

    $("#editarendereco").submit(function(editarendereco){
        let prosseguir = true;
        let cep = document.getElementById("cep").value;


        if (!verificartxt(cep, 9)) 
            prosseguir = false;

        if (!prosseguir)
            editarendereco.preventDefault();           
    });

    // $("input[name='entrega']").change(function() {
    //     var dados = new FormData();
    //     dados.append("tipo", $(this).val());
    
    //     $.ajax({
    //         url: "/digitalmarket/lib/ajax/endereco",
    //         dataType: "text",
    //         cache: false,
    //         contentType: false,
    //         processData: false,
    //         method: "post",
    //         data: dados,
    //         success: function(retorno) {

                
    //         },
    
    //         timeout: 5000
    //     });

        
    //     if ($(this).val() == 0) {
    //         $("#aa").html("Retirar no estabelecimento");
    //         $("#bb").html("Endereço do estabelecimento");
            
    //     } else {
    //         $("#aa").html("Entrega em até 10 dias úteis");
    //         $("#bb").html("Endereço");
            
    //     }
    // });
});


function listarTarefa() {
    $.ajax({
        url: "/digitalmarket/lib/ajax/tabela",
        dataType: "text",
        cache: false,
        contentType: false,
        processData: false,
        success: function(retorno){ 
            $("#tabela-carrinho").html(retorno);
        },

        timeout: 5000
    });
}

function alterarFormSacola(form) {
    for (let i = 1; i < 5; i++) {
        if ($("#form-" + i).attr("class") == "") 
            $("#form-" + i).addClass("invisivel");
    }


    if (form == 1) {
        $("#form-1").removeClass("invisivel");
        $("#voltar").addClass("invisivel");
        $("#prosseguir").attr("onclick", "alterarFormSacola(2)");
        $(".icones label:not(.icone-sacola)").css("color", "var(--terciaria)");
        $(".icone-sacola").css("color", "white");
        $("#prosseguir").removeClass("invisivel");


    } else if (form == 2) {
        $("#form-2").removeClass("invisivel");
        $("#voltar").attr("onclick", "alterarFormSacola(1)");
        $("#voltar").removeClass("invisivel");
        $("#prosseguir").attr("onclick", "alterarFormSacola(3)");
        $(".icones label:not(.icone-entrega)").css("color", "var(--terciaria)");
        $(".icone-entrega").css("color", "white");
        $(".icone-entrega").attr("onclick", "alterarFormSacola(2);");
        $(".icone-entrega").css("cursor", "pointer");
        $(".icone-sacola").attr("onclick", "alterarFormSacola(1);");
        $(".icone-sacola").css("cursor", "pointer");
        $("#prosseguir").removeClass("invisivel");


    } else if (form == 3) {
        $("#form-3").removeClass("invisivel");
        $("#voltar").attr("onclick", "alterarFormSacola(2)");
        $("#voltar").removeClass("invisivel");
        $("#prosseguir").addClass("invisivel");
        $(".icones label:not(.icone-pagamento)").css("color",  "var(--terciaria)");
        $(".icone-pagamento").css("color", "white");
        $(".icone-entrega").attr("onclick", "alterarFormSacola(2);");
        $(".icone-entrega").css("cursor", "pointer");
        $(".icone-pagamento").attr("onclick", "alterarFormSacola(3);");
        $(".icone-pagamento").css("cursor", "pointer");
    } 
}

function visibilidadeModalEndereco(visib) {
    if (visib) {
        $(".modal-editar-endereco").css("bottom", "10vh");
        $(".bg-alvorecer").removeClass("invisivel");
    } else {
        $(".modal-editar-endereco").css("bottom", "-100%");
        $(".bg-alvorecer").addClass("invisivel");
    }
}

function mudarPagamento(form) {
    $(".pagar-cartao").attr("onclick", "");
    $(".pagar-dinheiro").attr("onclick", "");

    if (form == 1) {
        $("#pagar-dinheiro").hide("slow");
        $("#seta-dinheiro").attr("class", "fas fa-chevron-right");
        
        if ($("#pagar-cartao").is(":hidden")) {
            $("#pagar-cartao").show("slow");
            $("#seta-cartao").attr("class", "fas fa-chevron-down");
            $("html,body").animate({scrollTop: $(".pagar-cartao").offset().top},"slow");   
        } else {
            $("#pagar-cartao").hide("slow");
            $("#seta-cartao").attr("class", "fas fa-chevron-right");
        }
        
        $(".pagar-cartao").css("border-bottom", "0");
        $("#ncartao").attr("disabled", false);
        $("#titular").attr("disabled", false);
        $("#validade").attr("disabled", false);
        $("#ano_validade").attr("disabled", false);
        $("#cvv").attr("disabled", false);
        $("#salvarcartao").attr("disabled", false);




    } else if (form == 2) {
        
        $("#pagar-cartao").hide("slow");
        $("#seta-cartao").attr("class", "fas fa-chevron-right");
        
        if ($("#pagar-dinheiro").is(":hidden")) {
            $("#pagar-dinheiro").show("slow");
            $("#seta-dinheiro").attr("class", "fas fa-chevron-down");
            $("html,body").animate({scrollTop: $(".pagar-dinheiro").offset().top},"slow");
        } else {
            $("#pagar-dinheiro").hide("slow");
            $("#seta-dinheiro").attr("class", "fas fa-chevron-right");
        }

        $("#ncartao").attr("disabled", true);
        $("#titular").attr("disabled", true);
        $("#validade").attr("disabled", true);
        $("#ano_validade").attr("disabled", true);
        $("#cvv").attr("disabled", true);
        $("#salvarcartao").attr("disabled", true);

    }

    setTimeout(function(){
        $(".pagar-cartao").attr("onclick", "mudarPagamento(1)");
        $(".pagar-dinheiro").attr("onclick", "mudarPagamento(2)");
    }, 250);
}

function atualizarTroco() {
    if (document.getElementById("troco").checked) {
        $("#grana").removeClass("invisivel");
        $("#lbl-grana").removeClass("invisivel");
        $("#grana").attr("disabled", false);
        document.getElementById("grana").focus();
    } else {
        $("#grana").addClass("invisivel");
        $("#lbl-grana").addClass("invisivel");
        $("#grana").val("");
        $("#valor-grana").html("");
        $("#grana").attr("disabled", true);
    }
}

function atualizarValorTroco() {
    valor = $("#grana").val();

    if (valor.length > 2) {
        let grana = valor.replace(".", "").replace(".", "").replace(".", "").replace(",", ".");
        total = parseFloat(document.getElementById("v-total").value);

        valor = parseFloat(grana);
    
        let troco = valor - total;
        
    
        if (troco >= 0) {
            let resultado = troco.toLocaleString("pt-br", {minimumFractionDigits: 2});
            document.getElementById("valor-grana").innerHTML = "<span class='devolucao'>Devolução:</span> R$" + resultado;
            $("#ipt-troco").val(troco);
        } else {
            document.getElementById("valor-grana").innerHTML = "<span class='invalid'>Valor insuficiente!</span>";
        }
    } else 
        document.getElementById("valor-grana").innerHTML = "";

}