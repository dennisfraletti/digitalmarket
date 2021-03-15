<?php
    session_start();

    if (isset($_SESSION["id"])) {
        echo "<script>window.history.go(-1)</script>"; 
        die;
    }
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Venda conosco | Digital Market</title>

    <!-- arquivo .css bootstrap -->
    <link rel="stylesheet" href="../css/bootstrap/bootstrap.css"/>

    <!-- arquivo de icones da font awesome -->
    <link rel="stylesheet" href="../font/css/all.css"/>

    <link rel="stylesheet" href="../css/cores.css"/>

    <link rel="stylesheet" href="../css/cadastrar.css"/>

    <link rel="stylesheet" href="../css/radio.css">

    <link rel="shortcut icon" href="../img/dm.ico"/>
</head>
<body>
    <!-- navbar -->
    <nav class="nav-menu">
        <div class="nav-cima">
            <div class="nav-logo">
                <a href="../"><i class="fas fa-shopping-cart"></i> Digital Market</a>
            </div>

            <div class="nav-links">
                <a href="cliente">Cliente</a>
                <span>Mercado</span>
                <!-- <a href="entregador">Entregador</a> -->
            </div>
        </div>

        <div class="nav-voltar">
            <a class="link-voltar" href="../entrar/">
                <span class="span-voltar">Já tenho uma conta<i class="fas fa-chevron-right seta-d"></i></span>
            </a>
        </div>
    </nav>

    <!-- pagina -->
    <div class="pagina">
        <h1 class="titulo-form" id="tt-cad">Cadastre-se</h1>
        <form action="../lib/mercado/cadastrar" id="formmercado" method="post" enctype="multipart/form-data">
            <div class="duas-colunas">
                <div class="inputs-email">
                    <div class="campo">
                        <label for="email" class="label-text">*&nbsp; E-mail </label>

                        <div class="input-email">
                            <input type="email" name="email" class="textbox" id="email" maxlength="70" autocomplete="off" tabindex="1" placeholder="exemplo@endereco.com" onblur="validarEmail(this.value);" required/>

                            <div class="alerta invisivel" id="alert-email"></div>                            
                        </div>
                    </div>

                    <div class="espaco-email"></div>

                    <div class="campo">
                        <label for="confirmar_email" class="label-text">*&nbsp; Confirmar E-mail </label>
                        
                        <div class="input-confirmar_email">
                            <input type="email" name="confirmar_email" class="textbox" id="confirmar_email" maxlength="70" autocomplete="off" tabindex="3" placeholder="Digite seu e-mail novamente" onpaste="return false" ondrop="return false" onblur="validarConfirmarEmail(this.value);" disabled required/>
                            
                            <div class="alerta invisivel" id="alert-confirmar_email"></div>                            
                        </div>
                    </div>
                </div>

                <div class="inputs-senha">
                    <div class="campo">
                        <label for="senha" class="label-text">*&nbsp; Senha </label>
                    
                        <div class="textbox-senha">
                            <input type="password" name="senha" class="textbox senha" id="senha" minlength="8" maxlength="40" onkeyup="forcaSenha();" placeholder="Mínimo de 8 caracteres" autocomplete="off" tabindex="2" onblur="validarSenha(this.value);" required/>
                                        
                            <button type="button" class="botao-visibilidade">
                                <i id="visibilidade" class="far fa-eye"></i>
                            </button>
                                            
                            <div class="forca">
                                <div class="progresso"></div>
                            </div>                   
                            
                            <div class="alerta invisivel" id="alert-senha"></div>                            
                        </div>
                    </div>

                    <div class="campo">
                        <label for="confirmar_senha" class="label-text">*&nbsp; Confirmar Senha </label>
                        
                        <div class="input-confirmar_senha">
                            <input type="password" name="confirmar_senha" class="textbox" id="confirmar_senha" minlength="8" maxlength="40" autocomplete="off" tabindex="4" placeholder="Digite sua senha novamente" onpaste="return false" ondrop="return false" onblur="validarConfirmarSenha(this.value);" disabled required/>
                        
                            <div class="alerta invisivel" id="alert-confirmar_senha"></div>                            
                        </div>
                    </div>
                </div>
            </div>    
            
            <div class="duas-colunas">
                <div class="dados-pessoais">

                    <h1 class="titulo" id="tt-ddp">Dados proprietário</h1>

                    <div class="campo">
                        <label for="nome" class="label-text">*&nbsp; Nome </label>

                        <div class="input-nome">
                            <input name="nome" class="textbox" id="nome" maxlength="50" autocomplete="off" tabindex="5" placeholder="Nome completo" onblur="validarNome(this.value); atualizarTipoConta(this.name, this.value);" required/>

                            <div class="alerta invisivel" id="alert-nome"></div>                            
                        </div>
                    </div>

                    <div class="campo">
                        <label for="cpf" class="label-text">*&nbsp; CPF </label>

                        <div class="input-cpf">
                            <input name="cpf" class="textbox" id="cpf" maxlength="14" autocomplete="off" tabindex="6" placeholder="000.000.000-00" onblur="validarCpf(this.value); atualizarTipoConta(this.name, this.value);" required/>

                            <div class="alerta invisivel" id="alert-cpf"></div>                            
                        </div>
                    </div>

                    <div class="campo">
                        <label for="rg" class="label-text">*&nbsp; RG </label>

                        <div class="input-rg">
                            <input name="rg" class="textbox" id="rg" maxlength="11" autocomplete="off" tabindex="7" placeholder="00.000.000-0" onblur="validarRg(this.value);" required/>
                            
                            <div class="alerta invisivel" id="alert-rg"></div>
                        </div>
                    </div>


                    <h1 class="titulo" id="tt-dm">Dados mercado</h1>

                    <div class="campo">
                        <label for="razaosocial" class="label-text">*&nbsp; Razão Social </label>

                        <div class="input-razaosocial">
                            <input name="razaosocial" class="textbox" id="razaosocial" maxlength="50" autocomplete="off" tabindex="8" placeholder="Razão Social" onblur="validarRazaoSocial(this.value); atualizarTipoConta(this.name, this.value);" required/>
                            
                            <div class="alerta invisivel" id="alert-razaosocial"></div>
                        </div>
                    </div>


                    <div class="campo">
                        <label for="nomefantasia" class="label-text">*&nbsp; Nome Fantasia </label>

                        <div class="input-nomefantasia">
                            <input name="nomefantasia" class="textbox" id="nomefantasia" maxlength="50" autocomplete="off" tabindex="9" placeholder="Esse nome estará visivel a todos" onblur="validarNomeFantasia(this.value);" required/>
                            
                            <div class="alerta invisivel" id="alert-nomefantasia"></div>
                        </div>
                    </div>


                    <div class="campo">
                        <label for="cnpj" class="label-text">*&nbsp; CNPJ </label>

                        <div class="input-cnpj">
                            <input name="cnpj" class="textbox" id="cnpj" maxlength="18" autocomplete="off" tabindex="10" placeholder="00.000.000/0000-00" onblur="validarCnpj(this.value); atualizarTipoConta(this.name, this.value);" required/>
                            
                            <div class="alerta invisivel" id="alert-cnpj"></div>
                        </div>
                    </div>


                    <div class="campo c-celular">
                        <label for="celular" class="label-text">*&nbsp; Celular </label>

                        <div class="celular-direita">
                            <input name="celular" class="textbox" id="celular" maxlength="14" autocomplete="off" tabindex="11" placeholder="(00) 00000-000" onblur="validarCelular('celular', this.value);" required/>
                            <button type="button" class="btn-celular" id="add-celular" onclick="adicionarCelular();"><span class="plus">+</span></button>
                            
                            <div class="alerta invisivel" id="alert-celular"></div>                            
                        </div>
                    </div>

                    <div class="campo c-celular2" id="invisivel">
                        <label for="celular2" class="label-text">*&nbsp; Celular 2 </label>

                        <div class="celular-direita">
                            <input name="celular2" class="textbox" id="celular2" maxlength="14" autocomplete="off" tabindex="12" placeholder="(00) 00000-000" onblur="validarCelular('celular2', this.value);" disabled required/>
                            <button type="button" class="btn-celular" id="rm-celular2" onclick="removerCelular(2);"><span><i class="fas fa-trash-alt lixo"></i></span></button>
                            
                            <div class="alerta invisivel" id="alert-celular2"></div>
                        </div>
                    </div>

                    <div class="campo c-celular3" id="invisivel">
                        <label for="celular3" class="label-text">*&nbsp; Celular 3</label>

                        <div class="celular-direita">
                            <input name="celular3" class="textbox" id="celular3" maxlength="14" autocomplete="off" tabindex="13" placeholder="(00) 00000-000" onblur="validarCelular('celular3', this.value);" disabled required/>
                            <button type="button" class="btn-celular" id="rm-celular3" onclick="removerCelular(3);"><span><i class="fas fa-trash-alt lixo"></i></span></button>
                            
                            <div class="alerta invisivel" id="alert-celular3"></div>
                        </div>
                    </div>


                    <div class="campo">
                        <label class="label-text">*&nbsp; Logo </label>

                        <div class="input-logo">
                            <img class="invisivel" id="imglogo"/>
                            <button type="button" class="rm-img invisivel" onclick="apagaLogo();">&times;</button>

                            <input type="file" name="logo" id="logo" accept="image/png, image/jpeg, image/jpg, image/bmp, image/gif, image/jfif"/>
                            
                            <label for="logo" class="lbl-addlogo" tabindex="14">
                                <i class="fas fa-paperclip"></i>&nbsp; <span>Adicionar logo</span>
                            </label>
                            
                            <div class="alerta invisivel" id="alert-logo"></div>
                        </div>
                    </div>

                    <!-- <div class="campo" style="margin-top: 25px;">
                        <label class="label-text">*&nbsp; Plano </label>

                        <div class="input-plano">
                            <input name="plano" id="plano"/>

                            <div class="bloco-plano bloco-basico" id="sombra" onclick="alterarPlano('basico');">                                
                                <div class="cabecalho-bloco amarelo">
                                    <h1 class="titulo-bloco">Plano básico</h1>  
                                    
                                    <i class="fas fa-check selecionado invisivel"></i>
                                </div>

                                <div class="corpo-bloco">
                                    <span><i class="fas fa-check-circle"></i> Entrega feita pelo mercado. </span>

                                    <span><i class="fas fa-check-circle"></i> Comissão de 5% das vendas mais mensalidade de R$ 100,00. </span>
                                
                                    <span><i class="fas fa-check-circle"></i> Cancele o plano a qualquer momento. </span>
                                </div>

                                <button type="button" class="btn-escolher" id="btn-basico">Escolher</button>
                            </div>

                            <br/>

                            <div class="bloco-plano bloco-entrega" id="sombra" onclick="alterarPlano('entrega');">
                                
                                <div class="cabecalho-bloco roxo">
                                    <h1 class="titulo-bloco">Plano entrega</h1>

                                    <i class="fas fa-check selecionado invisivel"></i>
                                </div>

                                <div class="corpo-bloco">
                                    <span><i class="fas fa-check-circle"></i> Entrega pelos entregadores do Digital Market. </span>

                                    <span><i class="fas fa-check-circle"></i> Acione entregadores pela região a qualquer instante. </span>

                                    <span><i class="fas fa-check-circle"></i> Comissão de 12% mais mensalidade de R$ 200,00. </span>

                                    <span><i class="fas fa-check-circle"></i> Cancele o plano a qualquer momento. </span>
                                </div>

                                <button type="button" class="btn-escolher" id="btn-entrega">Escolher</button>
                            </div>


                            <div class="alerta invisivel" id="alert-plano" style="margin-top: 15px;"></div>
                        </div>
                    </div> -->
                </div> 

                <div class="endereco">

                    <h1 class="titulo" id="tt-e">Endereço</h1>

                    <div class="campo">
                        <label for="cep" class="label-text">*&nbsp; CEP </label>
                    
                        <div class="cep-direita">
                            <input name="cep" class="textbox" id="cep" maxlength="9" autocomplete="off" tabindex="15" placeholder="00000-000" onblur="pesquisacep(this.value)" required/>
                            
                            <div class="alerta invisivel" id="alert-cep"></div>

                            <a href="http://www.buscacep.correios.com.br/sistemas/buscacep/" target="_blank" class="link-cep">Não sei meu CEP</a>
                        </div>    
                    </div>

                    <div class="campo">
                        <label for="rua" class="label-text">*&nbsp; Rua </label>

                        <div class="input-rua">
                            <input name="rua" class="textbox" id="rua" maxlength="50" autocomplete="off" tabindex="16" placeholder="Digite sua rua" onblur="validarCamposEndereco('rua', this.value);" disabled required/>

                            <div class="alerta invisivel" id="alert-rua"></div>
                        </div>
                    </div>

                    <div class="campo">
                        <label for="numero" class="label-text"><span class="asterisco">*</span>&nbsp; Número</label>
                        <div class="numero-direita">
                            <input name="numero" class="textbox" id="numero" maxlength="8" placeholder="Digite somente números" autocomplete="off" tabindex="17" onblur="validarCamposEndereco('numero', this.value);" disabled required/>

                            <input type="checkbox" name="semnumero" id="semnumero" tabindex="14" disabled/>
                            <label for="semnumero" id="text-sn">&nbsp;Endereço sem número</label>  
                    
                            <div class="alerta invisivel" id="alert-numero"></div>
                        </div> 
                    </div>
                    
                    <div class="campo">
                        <label for="bairro" class="label-text">*&nbsp; Bairro </label>
                        
                        <div class="input-bairro">
                            <input name="bairro" class="textbox" id="bairro" maxlength="50" placeholder="Digite seu bairro" autocomplete="off" tabindex="18" onblur="validarCamposEndereco('bairro', this.value);" disabled required/>

                            <div class="alerta invisivel" id="alert-bairro"></div>
                        </div>
                    </div>

                    <div class="campo">
                        <label for="cidade" class="label-text">*&nbsp; Cidade </label>
                        
                        <div class="input-cidade">
                            <input name="cidade" class="textbox" id="cidade" maxlength="50" placeholder="Digite sua cidade" autocomplete="off" tabindex="19" onblur="validarCamposEndereco('cidade', this.value);" disabled required/>

                            <div class="alerta invisivel" id="alert-cidade"></div>
                        </div>
                    </div>
 
                    <div class="campo">
                        <label for="estado" class="label-text">*&nbsp; Estado </label>
                        
                        <div class="input-estado">
                            <input name="estado" class="textbox" id="estado" maxlength="20" placeholder="Digite seu estado" autocomplete="off" tabindex="20" onblur="validarCamposEndereco('estado', this.value);" disabled required/>

                            <div class="alerta invisivel" id="alert-estado"></div>
                        </div>
                    </div> 

                    <div class="campo c-complemento">
                        <label for="complemento" class="label-text">Complemento</label>
                        <input name="complemento" class="textbox" id="complemento" maxlength="80" placeholder="Digite um complemento (opcional)" autocomplete="off" tabindex="21" disabled/>
                    </div> 



                    <h1 class="titulo" id="tt-ddb">Dados bancários</h1>

                    <div class="campo">
                        <label for="tipoconta" class="label-text">*&nbsp; Tipo de Conta </label>

                        <div class="input-tipoconta">
                            <label class="lbl-tipoconta-checked lbl-pf" for="pf">
                                <input type="radio" name="tipoconta" id="pf" value="pf" tabindex="22" onchange="atualizarRadioTipoConta();" checked/>
                                
                                <div id="rd-pf" class="radio-design-checked"></div>

                                <span class="span-pf">&nbsp; Pessoa Física</span>

                                <div class="radio-partes">
                                    <div>
                                        <span class="desc">Nome</span> <span id="span-nome"></span>
                                    </div>
                                    
                                    <div>
                                        <span class="desc">CPF</span> <span id="span-cpf"></span>
                                    </div>
                                </div>
                            </label>
                                

                            <label class="lbl-tipoconta lbl-pj" for="pj">
                                <input type="radio" name="tipoconta" id="pj" value="pj" onchange="atualizarRadioTipoConta();" tabindex="23"/>

                                <div id="rd-pj" class="radio-design"></div>
                                <span class="span-pj">&nbsp; Pessoa Juridica</span>

                                <div class="radio-partes">
                                    <div>
                                        <span class="desc">Razão Social</span> <span id="span-razaosocial"></span>
                                    </div>
                                    
                                    <div>
                                        <span class="desc">CNPJ</span> <span id="span-cnpj"></span>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>
                    

                    <div class="campo">
                        <label for="banco" class="label-text">*&nbsp; Banco </label>

                        <div class="input-banco">
                            <input name="banco" class="textbox" id="banco" maxlength="50" autocomplete="off" tabindex="24" placeholder="Aonde seu negócio possui conta?" onblur="validarBanco(this.value);" required/>
                            
                            <div class="alerta invisivel" id="alert-banco"></div>
                        </div>
                    </div>

                    <div class="campo">
                        <label for="agencia" class="label-text">*&nbsp; Agência </label>

                        <div class="input-agencia">
                            <input name="agencia" class="textbox" id="agencia" maxlength="7" autocomplete="off" tabindex="25" placeholder="Agência do seu negócio" onblur="validarAgencia(this.value);" required/>
                            
                            <div class="alerta invisivel" id="alert-agencia"></div>
                        </div>
                    </div>

                    <div class="campo">
                        <label for="conta" class="label-text">*&nbsp; Conta </label>

                        <div class="input-conta">
                            <div class="conta-row">
                                <input name="conta" class="textbox" id="conta" maxlength="10" autocomplete="off" tabindex="26" placeholder="Conta do seu negócio" onblur="validarConta(this.value);" required/>
                                    
                                <input name="digito" class="textbox" id="digito" maxlength="1" autocomplete="off" tabindex="27" placeholder="Dígito" onblur="validarDigito(this.value);" required/>
                            </div>

                            <div class="alerta invisivel" id="alert-conta"></div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="espaco"></div>

            <button type="submit" class="btn-cadastrar" tabindex="28">
                Cadastrar &nbsp;<i class="fas fa-chevron-right"></i>
            </button>
        </form>
    </div>

        
    <!-- arquivo .js jquery -->
    <script src="../js/jquery/jquery.js"></script>

    <script src="../js/jquery/jquery.mask.min.js"></script>

    <!-- arquivo .js bootstrap -->
    <script src="../js/bootstrap/bootstrap.js"></script>

    <script src="../js/requisicoes.js"></script>

    <script src="../js/verificadores.js"></script>

    <script src="../js/cadastrar.js"></script>

    <script src="../js/cadmercado.js"></script>

    <script src="../js/cep.js"></script>
</body>
</html>