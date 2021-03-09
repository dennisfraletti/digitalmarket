$(document).ready(function() {   
    listar();

    //if para corrigir o estilo da sidebar quando houver mais de quatro itens
    if ($(".item").length > 4) 
        $(".item").css("height", "104px");

    $(".botao-visibilidade").click(function() {
        let classe = $("#visibilidade").attr("class");
        
        let visibilidade = (classe == "far fa-eye") ? true : false;
        visibilidadeSenha(visibilidade);
    });
});

//função mudar o icone visualizar e trocar o tipo do input
function visibilidadeSenha(aparecer){
    let senha = $(".senha");

    if (aparecer) {
        $("#visibilidade").removeClass("fa-eye");
        $("#visibilidade").addClass("fa-eye-slash"); 
        senha.attr("type", "text");
    
    } else {
        $("#visibilidade").removeClass("fa-eye-slash");
        $("#visibilidade").addClass("fa-eye");
        senha.attr("type", "password");
    }
}

//função mostrar ou ocultas sidebar
function visibilidadeSidebar(aparecer) {
    if (aparecer) {
        $(".sidebar").css("transform", "translateX(-365px)");
        $(".bg-escurecer").removeClass("invisivel");

    } else {
        $(".sidebar").css("transform", "translateX(365px)");
        $(".bg-escurecer").addClass("invisivel");
    }
} 

function esconderBalao() {
    if ($("#balao").attr("class") != "invisivel") 
        $("#balao").addClass("invisivel");
}

function listar() {
    $.ajax({
        url: "/digitalmarket/lib/ajax/listar",
        dataType: "text",
        cache: false,
        contentType: false,
        processData: false,
        success: function(retorno){ 
            $("#itens").html(retorno);
        },

        timeout: 5000
    });
}

function adicionar(idproduto) {
    var dados = new FormData();
    dados.append("idproduto", idproduto);

    $.ajax({
        url: "/digitalmarket/lib/ajax/adicionar",
        dataType: "text",
        cache: false,
        contentType: false,
        processData: false,
        method: "post",
        data: dados,
        success: function(retorno){ 
            if (retorno)
                visibilidadeSidebar(true);
                
            $(".side-label-valor").html(retorno);
            $(".side-botao").attr("disabled", false);
            $(".side-label-total").removeClass("invisivel");
            $(".side-label-valor").html("R$" + retorno);
            $(".side-label-valor").removeClass("invisivel");
        },

        timeout: 5000
    });

    listar();
}

 
function remover(idcarrinho, link) {
    var dados = new FormData();
    dados.append("idcarrinho", idcarrinho);

    $.ajax({
        url: "/digitalmarket/lib/ajax/remover",
        dataType: "text",
        cache: false,
        contentType: false,
        processData: false,
        method: "post",
        data: dados,
        success: function(retorno) {
            let listRetorno = retorno.split("<><>");
            
            if (link == "1" || link == 1) {
                if (parseInt(listRetorno[1]) == 0) 
                    $(".conteudo-sacola").html('<h1 class="carrinho-vazio">CARRINHO VAZIO! </h1><span class="adicione">Adicione produtos a sua sacola e finalize aqui!</span><a href="/digitalmarket/" class="voltar-compras"><i class="fas fa-chevron-left"></i>&nbsp;&nbsp; Voltar as compras.</a>');
                else {
                    listarTarefa();    
                    $(".side-label-valor").html("R$" + listRetorno[0])
                }
            } else {
                $(".side-label-valor").html("R$" + listRetorno[0]);

                if (parseInt(listRetorno[1]) == 0) {
                    $(".side-label-valor").html("");
                    $("#itens").html("<span class='side-text-aviso'>Sacola vazia! Adicione produtos a sua sacola, e visualize aqui! </span>");
                    $(".side-botao").attr("disabled", true);
                    $(".side-label-total").addClass("invisivel");
                }              
                
                listar();

                if (link == 1) {
                    $("#txt-subtotal").html("R$ " + listRetorno[0]);
                    $("#txt-total").html("R$" + listRetorno[2]);
                    $("#txt-total-a").html("R$ " + listRetorno[2] + " á vista");
                    $("#v-total").val(listRetorno[3]);
                    $("#grana").addClass("invisivel");
                    $("#lbl-grana").addClass("invisivel");
                    $("#grana").val("");
                    $("#valor-grana").html("");
                    $("#grana").attr("disabled", true);
                    $("#troco").prop("checked", false);
                }
            }
        },

        timeout: 5000
    });
}

function alterar(idcarrinho, qtd, link) {
    var dados = new FormData();
    dados.append("idcarrinho", idcarrinho);

    dados.append("operacao", qtd);

    $.ajax({
        url: "/digitalmarket/lib/ajax/alterar",
        dataType: "text",
        cache: false,
        contentType: false,
        processData: false,
        method: "post",
        data: dados,

        success: function(retorno){
            let listRetorno = retorno.split("<><>"); 
            
            $("#qtd-" + idcarrinho).val(listRetorno[0]);
            
            if (listRetorno[1] != listRetorno[2]) {
                $("#p-real-" + idcarrinho).html("R$" + listRetorno[1]);
                $("#p-final-" + idcarrinho).html("R$" + listRetorno[2]);
            } else
                $("#preco-sdesconto-" + idcarrinho).html("R$" + listRetorno[1]);
        
            
            if (parseInt(listRetorno[0]) <= 1) {
                if (link == 1 || link == "1")
                    document.getElementById("diminuir-" + idcarrinho).innerHTML = "<button class='btn-retirar' onclick='remover(" + idcarrinho + ", 1);'><i class='fas fa-trash-alt lixo'></i></button>";   
                else 
                    document.getElementById("diminuir-" + idcarrinho).innerHTML = "<button class='btn-retirar' onclick='remover(" + idcarrinho + ", 0);'><i class='fas fa-trash-alt lixo'></i></button>";
            
            } else {

                if (link == 1 || link == "1") 
                    document.getElementById("diminuir-" + idcarrinho).innerHTML = "<button class='btn-retirar' onclick='alterar(" + idcarrinho + ", -1, 1);'>-</button>";   
                else
                    document.getElementById("diminuir-" + idcarrinho).innerHTML = "<button class='btn-retirar' onclick='alterar(" + idcarrinho + ", -1, 0);'>-</button>";           
            }

            
            if (listRetorno[0] == $("#qtd-" + idcarrinho).attr("max"))
                $("#btn-add-" + idcarrinho).attr("disabled", true);
            else
                $("#btn-add-" + idcarrinho).attr("disabled", false);

            $(".side-label-valor").html("R$" + listRetorno[3]);

            $("#preco-sdesconto-" + idcarrinho).removeClass("invisivel");
            $("#preco-sdesconto-" + idcarrinho).html("R$" + listRetorno[2]);

            if (parseInt(listRetorno[0]) == 1)
                $("#preco-sdesconto-" + idcarrinho).addClass("invisivel");


            if (link == 1) {
                $("#txt-subtotal").html("R$ " + listRetorno[3]);
                $("#txt-total").html("R$ " + listRetorno[4]);
                $("#txt-total-a").html("R$ "+ listRetorno[4] + " á vista");

                $("#v-total").val(listRetorno[5]);
                $("#grana").addClass("invisivel");
                $("#lbl-grana").addClass("invisivel");
                $("#grana").val("");
                $("#valor-grana").html("");
                $("#grana").attr("disabled", true);
                $("#troco").prop("checked", false);
            }
        },

        timeout: 5000
    });
}

function atualizarImagemCard(id, imagem, idmercado) {
    setTimeout(function(){ $("#" + id).attr("src", "uploads/mercado/" + idmercado + "/produtos/" + imagem); }, 125);
}

// function sugestoesPesquisa(abrir) {
     
//     if (abrir) {
        
//         var dados = new FormData();
        
//         dados.append("pesquisa", document.getElementById("query").value);


//         $.ajax({

//             url: "/digitalmarket/lib/ajax/pesquisa",
//             dataType: "text",
//             cache: false,
//             contentType: false,
//             processData: false,
//             method: "post",
//             data: dados,
//             success: function(retorno){ 
//                 // alert(retorno);
//                 if (retorno.length > 0) {
//                     $(".txtpesquisa").css("border-bottom-left-radius", "0rem"); 
//                     $(".txtpesquisa").css("border-top-left-radius", "1.75rem");
                    
//                     $(".btnpesquisa").css("border-bottom-right-radius", "0rem"); 
//                     $(".btnpesquisa").css("border-top-right-radius", "1.75rem");
                    
//                     $("#list-pesquisas").html(retorno);
//                     $("#list-pesquisas").removeClass("invisivel");
//                 }

//             },
    
//             timeout: 5000
//         });
        
        
        
//     } else {
//         $("#list-pesquisas").addClass("invisivel");
    
//         $(".txtpesquisa").css("border-bottom-left-radius", "3.5rem"); 
//         $(".txtpesquisa").css("border-top-left-radius", "3.5rem");

//         $(".btnpesquisa").css("border-bottom-right-radius", "3.5rem"); 
//         $(".btnpesquisa").css("border-top-right-radius", "3.5rem");

//     }
    
// }