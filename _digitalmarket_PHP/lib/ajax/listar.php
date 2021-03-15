<?php
    session_start();

    if (isset($_SESSION["id"]) && (isset($_SESSION["tipo"]) && $_SESSION["tipo"] == 0)) {
        
        require_once "../../classes/Carrinho.php";
        $objCarrinho = new Carrinho();

        $objCarrinho->setFkCliente($_SESSION["id"]);
        $itens = $objCarrinho->selecionar(false);

        require_once "../../classes/Produto.php";
        $objProduto = new Produto();

        require_once "../../classes/Produto_Imagem.php";
        $objProduto_Imagem = new Produto_Imagem();

        if (count($itens) == 0) 
            echo "<span class='side-text-aviso'>Sacola vazia! Adicione produtos a sua sacola, e visualize aqui! </span>";


        $i = 0;
        foreach ($itens as $item) { 
            $ultimacs = ($i == count($itens) - 1) ? "ultimo" : "";
            $objProduto->setIdProduto($item["fkproduto"]);
            $produto = $objProduto->selecionar();

            $objProduto_Imagem->setFkProduto($item["fkproduto"]);
            $imagem = $objProduto_Imagem->selecionar()[0]["imagem"];
    
?>
        
            <div class="item <?= $ultimacs ?>" id="item-<?= $item["idcarrinho"] ?>">
                <img class="img-produto" src="uploads/mercado/<?= $produto["fkmercado"] ?>/produtos/<?= $imagem ?>" onclick="location.href='produto?idproduto=<?= $item['fkproduto'] ?>'"/>
                
                <a href="produto?idproduto=<?= $item['fkproduto'] ?>" class="titulo"><?= $produto["titulo"] ?></a>
                
                <div class="preco">
                    <div class="label-precos">
                        <?php 
                            if ($produto["preco"] != $produto["precodesconto"]) {
                                echo "<label id='p-real-{$item["idcarrinho"]}' class='preco-real'>R$". number_format($produto["preco"] * $item["qtd"], 2, ",", ".") ."</label>";
                                echo "<label id='p-final-{$item["idcarrinho"]}' class='preco-desconto'>R$". number_format($produto["precodesconto"] * $item["qtd"], 2, ",", ".")."</label>";
                            } else 
                                echo "<label id='preco-sdesconto-{$item["idcarrinho"]}' class='preco-desconto'>R$". number_format($produto["preco"] * $item["qtd"], 2, ",", ".") ."</label>";
                        ?>
                    </div>
                </div>

                <div class="qtd">
                    <?php $disabled = ($item["qtd"] == $produto["estoque"]) ? "disabled" : ""; ?>

                    <button class="btn-adicionar" id="btn-add-<?= $item["idcarrinho"] ?>" onclick="alterar(<?= $item['idcarrinho'] ?>, 1, 0);" <?= $disabled ?>>+</button>
                    
                    <input type="number" id="qtd-<?= $item["idcarrinho"] ?>" class="input-qtd" min="1" value="<?= $item["qtd"] ?>" max="<?= $produto["estoque"] ?>" disabled>
                    
                    <div id="diminuir-<?= $item["idcarrinho"] ?>">
                        <?php
                            if ($item["qtd"] == 1) 
                                echo "<button class='btn-retirar' onclick='remover({$item["idcarrinho"]}, 0);'><i class='fas fa-trash-alt lixo'></i></button>";
                            else
                                echo "<button class='btn-retirar' onclick='alterar({$item["idcarrinho"]}, -1, 0);'>-</button>";
                        ?>
                    </div>
                </div>
            </div>
    
    <?php
                $i++;
            }
        
        } else 
            echo "<a href='entrar/' class='side-text-aviso'>Entre com uma conta para adicionar itens ao carrinho</a>";
    ?>
</div>          