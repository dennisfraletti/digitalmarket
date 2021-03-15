<?php
    session_start();

    require_once "../../classes/Carrinho.php";
    $objCarrinho = new Carrinho();

    $objCarrinho->setFkCliente($_SESSION["id"]);
    $produtosCarrinho = $objCarrinho->selecionar(false);

    require_once "../../classes/Produto.php";
    $objProduto = new Produto();


    require_once "../../classes/Produto_Imagem.php";
    $objProduto_Imagem = new Produto_Imagem();


    foreach ($produtosCarrinho as $carrinho) {
        $objProduto->setIdProduto($carrinho["fkproduto"]);
        $produto = $objProduto->selecionar();

        $objProduto_Imagem->setFkProduto($produto["idproduto"]);
        $imagem = $objProduto_Imagem->selecionar()[0]["imagem"];
?>
        <tr class="tabela-linha">
            <td class="coluna-produtos">
                <img src="uploads/mercado/<?= $produto["fkmercado"] ?>/produtos/<?= $imagem ?>" alt="<?= $produto["titulo"] ?>" class="tabela-imagem"/>
                
                <div class="coluna-informacoes">
                    <div class="textos">
                        <span class="coluna-titulo"><?= $produto["titulo"] ?></span>
                        <span class="coluna-descricao"><?= $produto["descricao"] ?></span>
                    </div>
                </div>
            </td>

            <td class="coluna-qtd col-2">
                <div class="qtd">
                    <?php $disabled = ($carrinho["qtd"] == $produto["estoque"]) ? "disabled" : ""; ?>

                    <div id="diminuir-<?= $carrinho["idcarrinho"] ?>">
                        <?php
                            if ($carrinho["qtd"] == 1) 
                                echo "<button class='btn-retirar' onclick='remover({$carrinho["idcarrinho"]}, 1);'><i class='fas fa-trash-alt lixo'></i></button>";
                            else
                                echo "<button class='btn-retirar a-delete' onclick='alterar({$carrinho["idcarrinho"]}, -1, 1);'>-</button>";
                        ?>
                    </div>
                    
                    <input type="number" id="qtd-<?= $carrinho["idcarrinho"] ?>" class="input-qtd" min="1" value="<?= $carrinho["qtd"] ?>" max="<?= $produto["estoque"] ?>" disabled>
                    
                    <button class="btn-adicionar" id="btn-add-<?= $carrinho["idcarrinho"] ?>" onclick="alterar(<?= $carrinho['idcarrinho'] ?>, 1, 1);" <?= $disabled ?>>+</button>
                
                </div>
            </td>

            <td class="coluna-preco col-3">
                <div class="precos">
                    <?php
                        echo "<span class='sp-subtotal'>R$". number_format($produto["precodesconto"], 2, ",", "."). " un.</span>";

                        $visib = ($carrinho["qtd"] == 1) ? "invisivel" : "";
                            
                        echo "<span id='preco-sdesconto-{$carrinho["idcarrinho"]}' class='sp-subtotal {$visib}'>R$". number_format($produto["precodesconto"] * $carrinho["qtd"], 2, ",", ".") ."</span>";
                    ?>
                </div>
            </td>
        </tr>
<?php
    }
?>