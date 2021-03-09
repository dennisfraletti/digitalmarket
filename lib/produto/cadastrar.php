<?php
    
    session_start();

    if (!$_POST || !isset($_SESSION["id"])) {
        echo "<script>window.history.go(-1)</script>"; 
        die;
    }
    
    foreach ($_POST as $key => $value)
        $$key = $value;

    
    $img = $_FILES["img"];
    
    require_once "../../classes/Produto.php";
    require_once "../../classes/Produto_Imagem.php";

    $produto = new Produto();
    
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
    
    // insert pessoa
    $produto->setTitulo($titulo);
    $produto->setPreco($preco);
    $produto->setPrecoDesconto($precofinal);
    $produto->setFkCategoria($categoria);
    $produto->setEstoque($estoque);
    $produto->setFkMercado($_SESSION["id"]);
    $produto = $produto->cadastrar();

    $id = $produto["idproduto"];

    if (isset($file0))
        fazerUpload($img["tmp_name"][0], $img["name"][0], $img["size"][0], $id);
        
    if (isset($file1))
        fazerUpload($img["tmp_name"][1], $img["name"][1], $img["size"][1], $id);

    if (isset($file2))
        fazerUpload($img["tmp_name"][2], $img["name"][2], $img["size"][2], $id);

    if (isset($file3))
        fazerUpload($img["tmp_name"][3], $img["name"][3], $img["size"][3], $id);

    if (isset($file4))
        fazerUpload($img["tmp_name"][4], $img["name"][4], $img["size"][4], $id);

    if (isset($file5))
        fazerUpload($img["tmp_name"][5], $img["name"][5], $img["size"][5], $id);

    if (isset($file6))
        fazerUpload($img["tmp_name"][6], $img["name"][6], $img["size"][6], $id);
        
    if (isset($file7))
        fazerUpload($img["tmp_name"][7], $img["name"][7], $img["size"][7], $id);
    

    header("Location: ../../vendas");
