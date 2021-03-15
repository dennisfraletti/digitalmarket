<?php
    
    session_start();

    if (!$_POST || !isset($_GET["idproduto"])) {
        echo "<script>window.history.go(-1)</script>"; 
        die;
    }
      

    require_once "../../classes/Pedido_Detalhes.php";
    $objPedido_Detalhes = new Pedido_Detalhes();

    $objPedido_Detalhes->setFkProduto($_GET["idproduto"]);
    $qtd = $objPedido_Detalhes->selecionar()["qtd"];

    
    if ($qtd > 0) { 
        echo "<script>window.location = '../../vendas'; alert('Esse produto não pode ser editado porque já foi comprado por alguns usuários!');</script>";
        die;  
    }


    require_once "../../classes/Produto.php";

    if (isset($_POST["descricao"])) goto descricao;


    require_once "../../classes/Produto_Imagem.php";


    $objProduto = new Produto();
    $objProduto->setIdProduto($_GET["idproduto"]);
    $produto = $objProduto->selecionar();

    if ($produto["fkmercado"] != $_SESSION["id"]) {
        echo "<script>window.history.go(-1)</script>"; 
        die;
    }    

    foreach ($_POST as $key => $value)
        $$key = $value;

        
    function fazerUpload($tmp_name, $name, $size, $idproduto) {
        
        $formatos = array("jpg", "jpeg", "png", "bmp", "gif", "jfif");
        $extensao = pathinfo($name, PATHINFO_EXTENSION); 
        
        if (in_array($extensao, $formatos) && $size <= 1000000) {
            $nome = pathinfo($name, PATHINFO_FILENAME);
            $nomejpg = $nome.".jpg";
    
            $obj = new Conexao();
            $conexao = $obj->conectar();
    
            $query = "SELECT COUNT(*) AS 'qtd_imgproduto' FROM produto_imagem WHERE imagem = :imgproduto OR imagem LIKE :prodlike";
            
            $comando = $conexao->prepare($query);
            $comando->bindparam(":imgproduto", $nomejpg);
            $prodlike = $nome ."%.jpg";
            $comando->bindparam(":prodlike", $prodlike);
            $comando->execute();
            $resultado = $comando->fetch();
            
            $imgsecundaria = ($resultado["qtd_imgproduto"] == 0) ? $nomejpg : "{$nome} ({$resultado["qtd_imgproduto"]}).jpg";
                
            $produto_imagem = new Produto_Imagem();
                
            $produto_imagem->setFkProduto($idproduto);
            $produto_imagem->setImagem($imgsecundaria);
            $produto_imagem->cadastrar();
        
            $prod_img = $imgsecundaria;
        
            move_uploaded_file($tmp_name, "../../uploads/mercado/" . $_SESSION["id"] . "/produtos/".$prod_img);           
    
        }
    }

    $prosseguir = false;

    foreach ($_FILES["img"]["size"] as $file){
        if ($file > 0)
            $prosseguir = true;
    }


    if ($prosseguir) {
        
        $img = $_FILES["img"];
        
        $produto_imagem = new Produto_Imagem();
        $produto_imagem->setFkProduto($_GET["idproduto"]);
        $imagens = $produto_imagem->selecionar();
        

        foreach ($imagens as $imagem)
            unlink("../../uploads/mercado/". $_SESSION["id"] ."/produtos/". $imagem["imagem"]);
    
        $produto_imagem->deletar();
        
        if (isset($file0))
            fazerUpload($img["tmp_name"][0], $img["name"][0], $img["size"][0], $_GET["idproduto"]);
            
        if (isset($file1))
            fazerUpload($img["tmp_name"][1], $img["name"][1], $img["size"][1], $_GET["idproduto"]);
    
        if (isset($file2))
            fazerUpload($img["tmp_name"][2], $img["name"][2], $img["size"][2], $_GET["idproduto"]);
    
        if (isset($file3))
            fazerUpload($img["tmp_name"][3], $img["name"][3], $img["size"][3], $_GET["idproduto"]);
    
        if (isset($file4))
            fazerUpload($img["tmp_name"][4], $img["name"][4], $img["size"][4], $_GET["idproduto"]);
    
        if (isset($file5))
            fazerUpload($img["tmp_name"][5], $img["name"][5], $img["size"][5], $_GET["idproduto"]);
    
        if (isset($file6))
            fazerUpload($img["tmp_name"][6], $img["name"][6], $img["size"][6], $_GET["idproduto"]);
            
        if (isset($file7))
            fazerUpload($img["tmp_name"][7], $img["name"][7], $img["size"][7], $_GET["idproduto"]);    
    }


    if ($qtdrm > 0 && !$prosseguir) {

        function deletarImagem($id) {
        
            $objImagem = new Produto_Imagem();
            $objImagem->setIdImagem($id);
            $imagem = $objImagem->selecionar();    

            unlink("../../uploads/mercado/". $_SESSION["id"] ."/produtos/". $imagem["imagem"]);
            $objImagem->setIdImagem($imagem["idimagem"]);
            $objImagem->deletar();        
        }

        $produto_imagem = new Produto_Imagem();
        $produto_imagem->setFkProduto($_GET["idproduto"]);
        $imagens = $produto_imagem->selecionar();
    

        if (!isset($file0) && isset($imagens[0]))
            deletarImagem($imagens[0]["idimagem"]);

        if (!isset($file1) && isset($imagens[1]))
            deletarImagem($imagens[1]["idimagem"]);
            
        if (!isset($file2) && isset($imagens[2]))
            deletarImagem($imagens[2]["idimagem"]);
        
        if (!isset($file3) && isset($imagens[3]))
            deletarImagem($imagens[3]["idimagem"]);

        if (!isset($file4) && isset($imagens[4]))
            deletarImagem($imagens[4]["idimagem"]);
            
        if (!isset($file5) && isset($imagens[5]))
            deletarImagem($imagens[5]["idimagem"]);

        if (!isset($file6) && isset($imagens[6]))
            deletarImagem($imagens[6]["idimagem"]);

        if (!isset($file7) && isset($imagens[7]))
            deletarImagem($imagens[7]["idimagem"]);            
    }

    $preco = str_replace(".", "", $preco);
    $preco = str_replace(",", ".", $preco);
    
    

    if (!empty($precofinal)) {
        $precofinal = str_replace(".", "", $precofinal);
        $precofinal = str_replace(",", ".", $precofinal);
    } else
        $precofinal = $preco;


    if (isset($desconto)) {
        $desconto = str_replace("%", "", $desconto);
        if (!($desconto > 0 && $desconto <= 100))  $precofinal = $preco;
    }

    if (!isset($_POST["descricao"])) {
        $produto = new Produto();
        $produto->setTitulo($titulo);
        $produto->setDescricao(null);
        $produto->setPreco($preco);
        $produto->setPrecoDesconto($precofinal);
        $produto->setFkCategoria($categoria);
        $produto->setEstoque($estoque);
        $produto->setFkMercado($_SESSION["id"]);
        $produto->setIdProduto($_GET["idproduto"]);
        
        $produto->editar();

        header("Location: ../../vendas");
        die;
    } 
    
    descricao: 

    $produto = new Produto();
    $produto->setIdProduto($_GET["idproduto"]);
    $resultado = $produto->selecionar();
    $produto->setTitulo($resultado["titulo"]);
    $produto->setDescricao($_POST["descricao"]);
    $produto->setPreco($resultado["preco"]);
    $produto->setPrecoDesconto($resultado["precodesconto"]);
    $produto->setFkCategoria($resultado["fkcategoria"]);
    $produto->setEstoque($resultado["estoque"]);
    $produto->setFkMercado($resultado["fkmercado"]);
    $produto->setIdProduto($_GET["idproduto"]);

    $produto->editar();

    header("Location: ../../produto?idproduto=" . $resultado["idproduto"]);


