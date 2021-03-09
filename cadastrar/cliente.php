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
    <title>Crie sua conta | Digital Market</title>

    <!-- arquivo .css bootstrap -->
    <link rel="stylesheet" href="../css/bootstrap/bootstrap.css"/>

    <!-- arquivo de icones da font awesome -->
    <link rel="stylesheet" href="../font/css/all.css"/>

    <link rel="stylesheet" href="../css/cores.css"/>

    <link rel="stylesheet" href="../css/cadastrar.css"/>

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
                <span>Cliente</span>
                <a href="mercado">Mercado</a>
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
        <form action="../lib/cliente/cadastrar" id="formcliente" method="post">
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
                    <h1 class="titulo" id="tt-ddp">Dados pessoais</h1>

                    <div class="campo">
                        <label for="nome" class="label-text">*&nbsp; Nome </label>

                        <div class="input-nome">
                            <input name="nome" class="textbox" id="nome" maxlength="50" autocomplete="off" tabindex="5" placeholder="Nome completo" onblur="validarNome(this.value);" required/>

                            <div class="alerta invisivel" id="alert-nome"></div>                            
                        </div>
                    </div>

                    <div class="campo c-celular">
                        <label for="celular" class="label-text">*&nbsp; Celular </label>

                        <div class="celular-direita">
                            <input name="celular" class="textbox" id="celular" maxlength="14" autocomplete="off" tabindex="6" placeholder="(00) 00000-000" onblur="validarCelular('celular', this.value);" required/>
                            <button type="button" class="btn-celular" id="add-celular" onclick="adicionarCelular();"><span class="plus">+</span></button>
                            
                            <div class="alerta invisivel" id="alert-celular"></div>                            
                        </div>
                    </div>

                    <div class="campo c-celular2" id="invisivel">
                        <label for="celular2" class="label-text">*&nbsp; Celular 2 </label>

                        <div class="celular-direita">
                            <input name="celular2" class="textbox" id="celular2" maxlength="14" autocomplete="off" tabindex="7" placeholder="(00) 00000-000" onblur="validarCelular('celular2', this.value);" disabled required/>
                            <button type="button" class="btn-celular" id="rm-celular2" onclick="removerCelular(2);"><span><i class="fas fa-trash-alt lixo"></i></span></button>
                            
                            <div class="alerta invisivel" id="alert-celular2"></div>
                        </div>
                    </div>

                    <div class="campo c-celular3" id="invisivel">
                        <label for="celular3" class="label-text">*&nbsp; Celular 3</label>

                        <div class="celular-direita">
                            <input name="celular3" class="textbox" id="celular3" maxlength="14" autocomplete="off" tabindex="8" placeholder="(00) 00000-000" onblur="validarCelular('celular3', this.value);" disabled required/>
                            <button type="button" class="btn-celular" id="rm-celular3" onclick="removerCelular(3);"><span><i class="fas fa-trash-alt lixo"></i></span></button>
                            
                            <div class="alerta invisivel" id="alert-celular3"></div>
                        </div>
                    </div>

                    <div class="campo">
                        <label for="cpf" class="label-text">*&nbsp; CPF </label>

                        <div class="input-cpf">
                            <input name="cpf" class="textbox" id="cpf" maxlength="14" autocomplete="off" tabindex="9" placeholder="000.000.000-00" onblur="validarCpf(this.value);" required/>

                            <div class="alerta invisivel" id="alert-cpf"></div>                            
                        </div>
                    </div>

                    <div class="campo">
                        <label for="rg" class="label-text">*&nbsp; RG </label>

                        <div class="input-rg">
                            <input name="rg" class="textbox" id="rg" maxlength="11" autocomplete="off" tabindex="10" placeholder="00.000.000-0" onblur="validarRg(this.value);" required/>
                            
                            <div class="alerta invisivel" id="alert-rg"></div>
                        </div>
                    </div>



                    <!-- <h1 class="titulo" id="tt-ddb">Dados bancários</h1>

                    <div class="campo">
                        <label for="ncartao" class="label-text">*&nbsp; Número do Cartão </label>
                        
                        <div class="input-ncartao">
                            <input name="ncartao" class="textbox" id="ncartao" maxlength="19" autocomplete="off" tabindex="19" placeholder="Insira somente números" onblur="validarCartao(this.value);" required/>

                            <div class="alerta invisivel" id="alert-ncartao"></div>
                        </div>
                    </div>

                    <div class="campo">
                        <label for="titular" class="label-text">*&nbsp; Titular </label>
                        
                        <div class="input-titular">
                            <input name="titular" class="textbox" id="titular" maxlength="50" autocomplete="off" tabindex="20" placeholder="Nome impresso no cartão" onblur="validarTitular(this.value);" required/>

                            <div class="alerta invisivel" id="alert-titular"></div>
                        </div>
                    </div>

                    <div class="campo">
                        <label for="validade" class="label-text">*&nbsp; Validade </label>
                        
                        <div class="input-data">
                            <div class="input-mes-validade">
                                <select name="mes_validade" class="textbox mes_validade" id="validade" autocomplete="off" tabindex="21" onblur="validarValidadeMes(this.value);" required>
                                    <option value="">Mês</option>
                                    <?php
                                        $meses = array("Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho", "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro");  
                                        $cont = 1;

                                        foreach ($meses as $mes) {
                                            echo "<option value='{$cont}'>{$mes}</option>";
                                            $cont++;
                                        }
                                    ?>
                                </select>

                                <div class="alerta invisivel" id="alert-mes_validade"></div>                                
                            </div>

                            <div class="input-ano-validade">
                                <select name="ano_validade" class="textbox ano_validade" id="ano_validade" autocomplete="off" tabindex="22" onblur="validarValidadeAno(this.value);" required>
                                    <option value="">Ano</option>
                                    <?php
                                        for ($i = 2020; $i <= 2030; $i++) 
                                            echo "<option value='{$i}'>{$i}</option>";
                                    ?>
                                </select>

                                <div class="alerta invisivel" id="alert-ano_validade"></div>
                            </div>
                        </div>
                    </div>

                    <div class="campo">
                        <label for="cvv" class="label-text">*&nbsp; CVV </label>

                        <div class="input-cvv">
                            <input name="cvv" class="textbox" id="cvv" maxlength="3" autocomplete="off" tabindex="23" placeholder="Número atrás do cartão" onblur="validarCvv(this.value);" required/>

                            <div class="alerta invisivel" id="alert-cvv"></div>
                        </div>
                    </div> -->
                </div> 

                <div class="endereco">
                    <h1 class="titulo" id="tt-e">Endereço</h1>

                    <div class="campo">
                        <label for="cep" class="label-text">*&nbsp; CEP </label>
                    
                        <div class="cep-direita">
                            <input name="cep" class="textbox" id="cep" maxlength="9" autocomplete="off" tabindex="11" placeholder="00000-000" onblur="pesquisacep(this.value)" required/>
                            
                            <div class="alerta invisivel" id="alert-cep"></div>

                            <a href="http://www.buscacep.correios.com.br/sistemas/buscacep/" target="_blank" class="link-cep">Não sei meu CEP</a>
                        </div>    
                    </div>

                    <div class="campo">
                        <label for="rua" class="label-text">*&nbsp; Rua </label>

                        <div class="input-rua">
                            <input name="rua" class="textbox" id="rua" maxlength="50" autocomplete="off" tabindex="12" placeholder="Digite sua rua" onblur="validarCamposEndereco('rua', this.value);" disabled required/>

                            <div class="alerta invisivel" id="alert-rua"></div>
                        </div>
                    </div>

                    <div class="campo">
                        <label for="numero" class="label-text"><span class="asterisco">*</span>&nbsp; Número</label>
                        <div class="numero-direita">
                            <input name="numero" class="textbox" id="numero" maxlength="8" placeholder="Digite somente números" autocomplete="off" tabindex="13" onblur="validarCamposEndereco('numero', this.value);" disabled required/>

                            <input type="checkbox" name="semnumero" id="semnumero" tabindex="14" disabled/>
                            <label for="semnumero" id="text-sn">&nbsp;Endereço sem número</label>  
                    
                            <div class="alerta invisivel" id="alert-numero"></div>
                        </div> 
                    </div>
                    
                    <div class="campo">
                        <label for="bairro" class="label-text">*&nbsp; Bairro </label>
                        
                        <div class="input-bairro">
                            <input name="bairro" class="textbox" id="bairro" maxlength="50" placeholder="Digite seu bairro" autocomplete="off" tabindex="15" onblur="validarCamposEndereco('bairro', this.value);" disabled required/>

                            <div class="alerta invisivel" id="alert-bairro"></div>
                        </div>
                    </div>

                    <div class="campo">
                        <label for="cidade" class="label-text">*&nbsp; Cidade </label>
                        
                        <div class="input-cidade">
                            <input name="cidade" class="textbox" id="cidade" maxlength="50" placeholder="Digite sua cidade" autocomplete="off" tabindex="16" onblur="validarCamposEndereco('cidade', this.value);" disabled required/>

                            <div class="alerta invisivel" id="alert-cidade"></div>
                        </div>
                    </div>
 
                    <div class="campo">
                        <label for="estado" class="label-text">*&nbsp; Estado </label>
                        
                        <div class="input-estado">
                            <input name="estado" class="textbox" id="estado" maxlength="20" placeholder="Digite seu estado" autocomplete="off" tabindex="17" onblur="validarCamposEndereco('estado', this.value);" disabled required/>

                            <div class="alerta invisivel" id="alert-estado"></div>
                        </div>
                    </div> 

                    <div class="campo c-complemento">
                        <label for="complemento" class="label-text">Complemento</label>
                        <input name="complemento" class="textbox" id="complemento" maxlength="80" placeholder="Digite um complemento (opcional)" autocomplete="off" tabindex="18" disabled/>
                    </div> 

                </div>
            </div>
            
            <div class="espaco"></div>

            <button type="submit" class="btn-cadastrar" tabindex="24">
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

    <script src="../js/cep.js"></script>
</body>
</html>