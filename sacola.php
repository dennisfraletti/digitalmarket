<?php
    session_start();

    if (!isset($_SESSION["id"]) || !isset($_SESSION["tipo"]) || $_SESSION["tipo"] != 0) {
        echo "<script>window.history.go(-1)</script>";
        die;
    }

    if (isset($_SESSION["id"])) {
        require_once "classes/Pessoa.php";
        $pessoa = new Pessoa();
        $pessoa->setId($_SESSION["id"]);
        $pessoa = $pessoa->selecionar();

        require_once "classes/Cliente.php";
        $objCliente = new Cliente();
        $objCliente->pessoa->setId($_SESSION["id"]);
        $cliente = $objCliente->selecionar();
    }
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Sacola de compras | Digital Market</title>

    <!-- arquivo .css bootstrap -->
    <link rel="stylesheet" href="css/bootstrap/bootstrap.css"/>

    <!-- arquivo de icones da font awesome -->
    <link rel="stylesheet" href="font/css/all.css"/>

    <link rel="stylesheet" href="css/cores.css"/>
    
    <link rel="stylesheet" href="css/sacola.css"/>

    <link rel="shortcut icon" href="img/dm.ico"/>
</head>
<body>
    <div class="bg-alvorecer invisivel" onclick="visibilidadeModalEndereco(false);"></div>

    <!-- navbar -->
    <nav class="nav-menu">
        <div class="nav-logo">
            <a href="index"><i class="fas fa-shopping-cart"></i> Digital Market</a>
        </div>

        <div class="nav-caminho">
            <div class="icones">
                <label class="icone-sacola" onclick="">
                    <i class="fas fa-shopping-bag"></i><span class="span-sacola">&nbsp;Sacola</span>
                </label>

                <!-- <label class="icone-identificacao" onclick="">
                    <i class="fas fa-user"></i><span>&nbsp;Identificação</span>
                </label> -->

                <label class="icone-entrega" onclick="">
                    <i class="fas fa-truck"></i><span>&nbsp;Entrega</span>
                </label>

                <label class="icone-pagamento" onclick="">
                    <i class="far fa-money-bill-alt"></i><span>&nbsp;Pagamento</span>
                </label>       
            </div>
        </div>
    </nav>

    <div style="height: 22px"></div>


    <div class="conteudo-sacola">
        <?php
            require_once "classes/Carrinho.php";
            $objCarrinho = new Carrinho();
        
            $objCarrinho->setFkCliente($_SESSION["id"]);    
        
            $carrinho = $objCarrinho->selecionar(false);

            if (count($carrinho) > 0) {
        ?>
                <div id="form-1" class="">
                    <span class="finalize-pedido">Finalize seu pedido, <span><?= $pessoa["nome"] ?></span></span>
    
                    <table class="tabela">
                        <thead>
                            <tr class="tabela-linha" style="height: 42.5px !important;">
                                <th class="col-1" style="padding-left: 22px;">Produto</th>
                                <th class="col-2">Quantidade</th>
                                <th class="col-3">Preço</th>
                            </tr>
                        </thead>

                        <tbody id="tabela-carrinho">
                        
                        </tbody>
                    </table>


                    <div class="exibicao-total">
                        <div class="total">
                            <span>Total</span>
                        </div>
                        
                        <div class="sp-total">
                            <span class="side-label-valor">
                                <?php                        
                                    echo "R$ ".number_format($objCarrinho->selecionar(true)["soma"], 2, ",", ""); 
                                ?>
                            </span>
                        </div>
                    </div>                
                </div>

                <!-- <div id="form-2" class="invisivel">
                    
                </div> -->

                <form action="lib/pedido/cadastrar" method="post">

                    <div id="form-2" class="invisivel">
                        <span class="opcoes-entrega">Opções de entrega</span>

                        <span class="endereco">Endereço</span>
                        

                        <span class="dados-endereco">
                            <?php
                                echo "CEP: " . $cliente["cep"].", ".$cliente["rua"].", ".$cliente["numero"].", ".$cliente["bairro"].", ".$cliente["cidade"]." - ".$cliente["estado"]; 
                                if(!empty($cliente["complemento"])) echo ", ". $cliente["complemento"]; 
                            ?>
                        </span> 

                        <button class="trocar-endereco" onclick="visibilidadeModalEndereco(true);">Trocar endereço</button>


                        <div class="detalhes-entrega">
                            <?php
                                $qtd = 0;
                                foreach ($carrinho as $item) $qtd += $item["qtd"];
                            
                            ?>
                            <span class="sp-qtd"><?= $qtd . " produtos" ?></span>

                            <ul class="list-produtos">
                                <?php
                                    require_once "classes/Produto.php";
                                    $objProduto = new Produto();

                                    foreach ($carrinho as $item) {
                                        $objProduto->setIdProduto($item["fkproduto"]);
                                        $produto = $objProduto->selecionar();

                                        echo "<li> {$produto["titulo"]} ({$item["qtd"]})</li>";
                                    }
                                ?>
                            </ul>

                            
                            <div class="radios">
                                <!-- <div class="radio">
                                    <label for="estabelecimento" class="label"></label>                                
                                    <input type="radio" name="entrega" id="estabelecimento" value="0"/>
                                    <div id="rd-estabelecimento" class="radio-design"><div></div></div>
                                    <label for="estabelecimento" class="text-rd">Retirar no estabelecimento <span>Apartir de 1 dia útil</span></label>
                                    <label class="frete gratis" for="estabelecimento">Frete grátis</label>
                                </div> -->
                                
                                <div class="radio" style="border-bottom: 0;">
                                    <label for="padrao" class="label"></label>                            
                                    <input type="radio" name="entrega" id="padrao" value="1" checked/>
                                    <div id="rd-padrao" class="radio-design"><div></div></div>
                                    <label for="padrao" class="text-rd">Padrão <span>Apartir de 10 dias úteis</span></label>
                                    <label class="frete" for="padrao">R$ 9,99</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="form-3" class="invisivel">
                        <div class="colunas">
                            <div class="coluna c-1">
                                <span class="opcoes-entrega" style="margin-bottom: 2px">Revisão do pedido</span>

                                <span class="pagamento-entrega" id="aa">Entrega em até 10 dias úteis</span>

                                <?php
                                    foreach ($carrinho as $item) {
                                        $objProduto->setIdProduto($item["fkproduto"]);
                                        $produto = $objProduto->selecionar();

                                        echo "<span class='pagamento-produtos'>&bull;&nbsp; {$produto["titulo"]} ({$item["qtd"]})</span>";
                                    }
                                ?>                        

                                <div style="height: 8px;"></div>

                                <span class="editar-sacola" onclick="alterarFormSacola(1);">Editar sacola</span>
                            </div>


                            <div class="coluna c-2">
                                <span class="opcoes-entrega" id="bb" style="margin-bottom: 5px;">Endereço</span>

                                <span class="pagamento-entrega" id="cc">
                                    <?php
                                        echo "CEP: " . $cliente["cep"].", ".$cliente["rua"].", ".$cliente["numero"].", ".$cliente["bairro"].", ".$cliente["cidade"]." - ".$cliente["estado"]; 
                                        if(!empty($cliente["complemento"])) echo ", ". $cliente["complemento"]; 
                                    ?>
                                </span> 

                                <span class="editar-endereco" onclick="visibilidadeModalEndereco(true);">Editar endereço</span>                        
                            </div>
                        </div>

                        <br/>

                       
                        <div class="linha-valor">
                            <span class="info">Sub-total</span>
                            <span class="valor" id="txt-subtotal"><?= "R$ ".number_format($objCarrinho->selecionar(true)["soma"], 2, ",", ""); ?></span>                   
                        </div>
                        
                        <div class="linha-valor">
                            <span class="info">Frete</span>
                            <span class="valor">R$ 9,99</span>
                        </div>

                        <div class="linha-valor">
                            <span class="info">Total</span>
                            <span class="valor" id="txt-total"><?= "R$ ".number_format($objCarrinho->selecionar(true)["soma"] + 9.99, 2, ",", ""); ?></span>                   
                        </div>
                       

                        <span class="opcoes-entrega" style="margin-top: 40px; margin-bottom: 15px;">Escolha a forma de pagamento</span>

                    
                        <div class="jumbotron">
                            <label class="pagar pagar-cartao" onclick="mudarPagamento(1);" for="cartao">
                                <input type="radio" name="pagamento" id="cartao" value="cartao" checked/>
                                <label for="cartao">
                                    &nbsp;<i class="far fa-credit-card"></i>
                                    &nbsp; Novo cartão de crédito&nbsp;
                                    <span id="mover">&nbsp;&nbsp;&nbsp;</span>
                                    <i id="seta-cartao" class="fas fa-chevron-down"></i>
                                    <!-- <i class="fas fa-chevron-right"></i> -->
                                </label>
                            </label>
                            
                            <div id="pagar-cartao" class="">
                                <?php
                                    if (!empty($cliente["fkdcartao"])) {
                                        require_once "classes/Dados_Cartao.php";
                                        $objDados_Cartao = new Dados_Cartao();

                                        $objDados_Cartao->setIdDCartao($cliente["fkdcartao"]);
                                        $dados_cartao = $objDados_Cartao->selecionar();
                                    }
                                ?>

                                <div class="campo-cartao">
                                    <label for="ncartao" class="label-text">Número </label>                        
                                    <input name="ncartao" class="textbox-cartao" id="ncartao" maxlength="19" autocomplete="off" tabindex="19" placeholder="Insira somente números" onblur="validarCartao(this.value);" value="<?php if (!empty($dados_cartao["ncartao"])) echo $dados_cartao["ncartao"]; ?>" required/>                    
                                </div>
        
        
                                <div class="campo-cartao">
                                    <label for="titular" class="label-text">Titular </label>
                                    <input name="titular" class="textbox-cartao" id="titular" maxlength="50" autocomplete="off" tabindex="20" placeholder="Nome impresso no cartão" onblur="validarTitular(this.value);" value="<?php if (!empty($dados_cartao["titular"])) echo $dados_cartao["titular"]; ?>" required/>
                                </div>
        
                                
                                <div class="campo-cartao">
                                    <label for="validade" class="label-text">Validade </label>                        
                                    
                                    <div class="selects">
                                        <select name="mes_validade" class="textbox-cartao" id="validade" autocomplete="off" tabindex="21" onblur="validarValidadeMes(this.value);" required>
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
        
                                        
                                        <select name="ano_validade" class="textbox-cartao" id="ano_validade" autocomplete="off" tabindex="22" onblur="validarValidadeAno(this.value);" required>
                                            <option value="">Ano</option>
                                            <?php
                                                for ($i = 2020; $i <= 2030; $i++) 
                                                    echo "<option value='{$i}'>{$i}</option>";
                                            ?>
                                        </select>    
                                    </div>
                                </div>
        
                                <div class="campo-cartao">
                                    <label for="cvv" class="label-text">CVV </label>
                                    <input name="cvv" class="textbox-cartao" id="cvv" maxlength="3" autocomplete="off" tabindex="23" placeholder="Número atrás do cartão" onblur="validarCvv(this.value);" value="<?php if (!empty($dados_cartao["cvv"])) echo $dados_cartao["cvv"]; ?>" required/>                    
                                </div>

                                <div class="campo-cartao" style="margin-bottom: 0px;">
                                    <span></span>
                                    <div>
                                        <input type="checkbox" name="salvarcartao" id="salvarcartao" checked/>
                                        <label for="salvarcartao">&nbsp; Guardar cartão para as próximas compras</label>
                                    </div>
                                </div>

                                <div class="campo-cartao">
                                    <span></span>
                                    <button type="submit" class="concluir">Concluir pedido</button>
                                </div>
                                
                            </div>
                            

                            <label class="pagar pagar-dinheiro" onclick="mudarPagamento(2);" for="dinheiro">
                                <input type="radio" name="pagamento" id="dinheiro" value="dinheiro"/>
                                <label for="dinheiro">
                                    &nbsp;<i class="far fa-money-bill-alt"></i>
                                    &nbsp; Pagar com dinheiro&nbsp;
                                    <i id="seta-dinheiro" class="fas fa-chevron-right"></i>
                                    <!-- <i class="fas fa-chevron-down"></i> -->
                                </label>
                            </label>
                            
                            <div id="pagar-dinheiro" style="display: none;">
                                <span></span>

                                <div>
                                    <div class="linha-valor">
                                        <span class="info aabb">Total</span>
                                        <span class="valor bbcc" id="txt-total-a"><?= "R$ ".number_format($objCarrinho->selecionar(true)["soma"] + 9.99, 2, ",", ""). " à vista"; ?></span>                   
                                    </div>
           
                                    <br>

                                    <input type="checkbox" id="troco" onchange="atualizarTroco();"/>
                                    <label for="troco" class="lb-troco">&nbsp; Precisa de troco? </label>

                                    <input type="hidden" id="v-total" name="precototal" value="<?= $objCarrinho->selecionar(true)['soma'] + 9.99 ?>">
                                    <input name="grana" class="textbox-cartao invisivel" id="grana" style="width: 100%;" maxlength="15" autocomplete="off" tabindex="23" placeholder="Dinheiro em mãos" onkeyup="atualizarValorTroco()" disabled required/>                    
                                    <label id="lbl-grana" class="invisivel"><span id='valor-grana'></span></label>

                                    <input type="hidden" name="troco" id="ipt-troco"/>

                                    <button type="submit" class="concluir" style="width: 100%;">Concluir pedido</button>

                                </div>

                                <span></span>
                            </div>
                        </div>            
                    </div>
            
                    <div class="botoes">
                        <button id="voltar" onclick="alterarFormSacola(1);" class="invisivel" type="button"> 
                            <i class="fas fa-chevron-left"></i>
                        </button>


                        <button id="prosseguir" onclick="alterarFormSacola(2);" class="" type="button">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>
                </form>

        <?php
            } else {
        ?>
                <h1 class="carrinho-vazio">CARRINHO VAZIO! </h1>

                <span class="adicione">Adicione produtos a sua sacola e finalize aqui!</span>

                <a href="/digitalmarket/" class="voltar-compras"><i class="fas fa-chevron-left"></i>&nbsp;&nbsp; Voltar as compras.</a>
        <?php
            }
        ?>
    </div>




    <div class="modal-editar-endereco">
        <button class="fechar-editar" onclick="visibilidadeModalEndereco(false)">&times;</button>


        <form action="lib/endereco/editar" method="post" id="editarendereco">
            <h1 class="alterar-endereco">Alterar Endereço</h1>
            
            <div class="linha r-1">
                <div class="campo">
                    <label for="cep" class="lbl-input">CEP</label>
                    <input name="cep" class="textbox" id="cep" maxlength="9" value="<?= $cliente["cep"] ?>" onblur="pesquisacep(this.value)" autocomplete="off" tabindex="1" required/>
                    <div class="alerta invisivel" id="alert-cep"></div>
                </div>
    
                <div class="campo">
                    <label for="rua" class="lbl-input">Rua</label>
                    <input name="rua" class="textbox" id="rua" maxlength="50" value="<?= $cliente["rua"] ?>" onblur="validarCamposEndereco('rua', this.value);" autocomplete="off" tabindex="2" required/>
                    <div class="alerta invisivel" id="alert-rua"></div>
                </div>
            </div>
    
            <div class="linha r-2">
                <div class="campo">
                    <label for="numero" class="lbl-input">Número</label>
                    <input name="numero" class="textbox" id="numero" maxlength="14" value="<?= $cliente["numero"] ?>" onblur="validarCamposEndereco('numero', this.value);" autocomplete="off" tabindex="3" required/>
                    
                    <input type="checkbox" name="semnumero" id="semnumero"/>
                    <label for="semnumero" id="text-sn">&nbsp;Endereço sem número</label>  
                    
                    <div class="alerta invisivel" id="alert-numero"></div>
                </div>
    
                <div class="campo">
                    <label for="bairro" class="lbl-input">Bairro</label>
                    <input name="bairro" class="textbox" id="bairro" maxlength="50" value="<?= $cliente["bairro"] ?>" onblur="validarCamposEndereco('bairro', this.value);" autocomplete="off" tabindex="4" required/>
                    <div class="alerta invisivel" id="alert-bairro"></div>
                </div>
            </div>
    
            <div class="linha r-3">
                <div class="campo">
                    <label for="cidade" class="lbl-input">Cidade</label>
                    <input name="cidade" class="textbox" id="cidade" maxlength="50" value="<?= $cliente["cidade"] ?>" onblur="validarCamposEndereco('cidade', this.value);" autocomplete="off" tabindex="5" required/>
                    <div class="alerta invisivel" id="alert-cidade"></div>
                </div>
    
                <div class="campo">
                    <label for="estado" class="lbl-input">Estado</label>
                    <input name="estado" class="textbox" id="estado" maxlength="20" value="<?= $cliente["estado"] ?>" onblur="validarCamposEndereco('estado', this.value);" autocomplete="off" tabindex="6" required/>
                    <div class="alerta invisivel" id="alert-estado"></div>
                </div>
            </div>
    
            <div class="campo" style="margin-bottom: 10px">
                <label for="complemento" class="lbl-input">Complemento</label>
                <input name="complemento" class="textbox" id="complemento" maxlength="80" value="<?= $cliente["complemento"] ?>" autocomplete="off" tabindex="7"/>
            </div>

            <input type="hidden" name="idendereco" value="<?= $cliente["idendereco"] ?>"/>

            <button type="submit" class="form-botao">Mudar</button>
        </form>    
    </div>



    <!-- arquivo .js jquery -->
    <script src="js/jquery/jquery.js"></script>

    <script src="js/jquery/jquery.mask.min.js"></script>

    <!-- arquivo .js bootstrap -->
    <script src="js/bootstrap/bootstrap.js"></script>

    <script src="js/requisicoes.js"></script>

    <script src="js/sacola.js"></script>

    <script src="js/cep.js"></script>

    <script src="js/verificadores.js"></script>

    <script src="js/cadastrar.js"></script>


    <script>
        <?php
            if (isset($_SESSION["sacola"])) {
                echo "alterarFormSacola(2);";
                unset($_SESSION["sacola"]);
            }

            if ($cliente["numero"] == "S/N") {
                echo "$('#numero').attr('disabled', true);";
                echo "$('#semnumero').attr('checked', true);";
            }

            if (isset($dados_cartao["validade"]) && !empty($dados_cartao["validade"])) {
                $listValidade = explode("-", $dados_cartao["validade"]);

                echo "$('#validade').val(parseInt({$listValidade[1]}));";
                echo "$('#ano_validade').val('{$listValidade[0]}');";
            }
        ?>
    </script>
</body>
</html>