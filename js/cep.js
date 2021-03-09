function limpa_formulario_cep() {
    //Limpa valores do formulário de cep.
    document.getElementById("rua").value=("");
    document.getElementById("bairro").value=("");
    document.getElementById("cidade").value=("");
    document.getElementById("estado").value=("");    
}

function meu_callback(conteudo) {
    desabilitarInputsEndereco(true);
    
    if (document.getElementById("semnumero").checked) 
        $("#numero").attr("disabled", true);

    if (!("erro" in conteudo)) {
        //Atualiza os campos com os valores.
        document.getElementById("rua").value=(conteudo.logradouro);
        document.getElementById("bairro").value=(conteudo.bairro);
        document.getElementById("cidade").value=(conteudo.localidade);
        document.getElementById("estado").value=(converterEstado(conteudo.uf));        

        atualizarErro("cep", "", true);
        
    } //end if.
    else {
        //CEP não Encontrado.
        limpa_formulario_cep();
        atualizarErro("cep", "CEP não encontrado.", false);
    }
}
    
function pesquisacep(valor) {
    //Nova variável "cep" somente com dígitos.
    var cep = valor.replace(/\D/g, '');
    
    //Verifica se campo cep possui valor informado.
    if (cep != "") {
        //Expressão regular para validar o CEP.
        var validacep = /^[0-9]{8}$/;

        //Valida o formato do CEP.
        if(validacep.test(cep)) {      
            //Cria um elemento javascript.
            var script = document.createElement("script");

            //Sincroniza com o callback.
            script.src = "https://viacep.com.br/ws/"+ cep + "/json/?callback=meu_callback";

            //Insere script no documento e carrega o conteúdo.
            document.body.appendChild(script);

        } //end if.
        else {
            //cep é inválido.
            limpa_formulario_cep();
            atualizarErro("cep", "Formato de CEP inválido.", false);
        }
    } //end if.
    else {
        //cep sem valor, limpa formulário.
        limpa_formulario_cep();
        atualizarErro("cep", "Campo obrigatório.", false);
    }
};

// função que converte string uf para estado
function converterEstado(uf) {
    switch (uf) {
		case "AC" :	data = "Acre";					break;
		case "AL" :	data = "Alagoas";				break;
		case "AM" :	data = "Amazonas";				break;
		case "AP" :	data = "Amapá";					break;
		case "BA" :	data = "Bahia";					break;
		case "CE" :	data = "Ceará";					break;
		case "DF" :	data = "Distrito Federal";		break;
		case "ES" :	data = "Espírito Santo";		break;
		case "GO" :	data = "Goiás";					break;
		case "MA" :	data = "Maranhão";				break;
		case "MG" :	data = "Minas Gerais";			break;
		case "MS" :	data = "Mato Grosso do Sul";	break;
		case "MT" :	data = "Mato Grosso";			break;
		case "PA" :	data = "Pará";					break;
		case "PB" :	data = "Paraíba";				break;
		case "PE" :	data = "Pernambuco";			break;
		case "PI" :	data = "Piauí";					break;
		case "PR" :	data = "Paraná";				break;
		case "RJ" :	data = "Rio de Janeiro";		break;
		case "RN" :	data = "Rio Grande do Norte";	break;
		case "RO" :	data = "Rondônia";				break;
		case "RR" :	data = "Roraima";				break;
		case "RS" :	data = "Rio Grande do Sul";		break;
		case "SC" :	data = "Santa Catarina";		break;
		case "SE" :	data = "Sergipe";				break;
		case "SP" :	data = "São Paulo";				break;
        case "TO" :	data = "Tocantíns";				break;
    }
    
    return data;
}