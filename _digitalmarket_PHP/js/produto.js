function atualizarImagem(idmercado, imagem) {
    $(".img-prod").attr("src", "uploads/mercado/" + idmercado + "/produtos/" + imagem);
}

function adicionarDescricao(mostrar) {
    if (mostrar) {
        $(".add-desc").addClass("invisivel");
        $("#descricao").removeClass("invisivel");
        $("#salvar-desc").removeClass("invisivel");
        $("#fechar-desc").removeClass("invisivel");
        $("#espaco").removeClass("invisivel");
        $("#qtd-caracteres").removeClass("invisivel");
        atualizarQtdInput("atualizar-qtd", document.getElementById("descricao").value);
        document.getElementById("descricao").focus();
    } else {
        $(".add-desc").removeClass("invisivel");
        $("#descricao").addClass("invisivel");
        $("#salvar-desc").addClass("invisivel");
        $("#fechar-desc").addClass("invisivel");
        $("#espaco").addClass("invisivel");
        $("#qtd-caracteres").addClass("invisivel");
        $("#descricao").val("");
    }
}

function atualizarQtdInput(span_qtd, campo) {
    $("#" + span_qtd).html(campo.length);
}
