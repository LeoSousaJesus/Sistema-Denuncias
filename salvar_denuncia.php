<?php

include 'conexao.php';

$descricao = $_POST['descricao'];
$latitude = $_POST['latitude'];
$longitude = $_POST['longitude'];

$protocolo = "DEN-" . date("Y") . "-" . rand(1000,9999);

$imagemNome = "";

if(isset($_FILES['imagem']) && $_FILES['imagem']['error'] == 0){

    $pasta = "uploads/";

    $imagemNome = time() . "_" . $_FILES['imagem']['name'];

    move_uploaded_file(
        $_FILES['imagem']['tmp_name'],
        $pasta . $imagemNome
    );
}


$sql = "INSERT INTO denuncias (
            protocolo,
            descricao,
            imagem,
            latitude,
            longitude
        )
        VALUES (
            '$protocolo',
            '$descricao',
            '$imagemNome',
            '$latitude',
            '$longitude'
        )";

if($conn->query($sql) === TRUE){

    echo "
    <link rel='stylesheet' href='style.css'>

    <div class='container'>

        <h1>Denúncia enviada!</h1>

        <p>
            Sua denúncia foi registrada com sucesso.
        </p>

        <h2>$protocolo</h2>

        <a href='status.php' class='botao'>
            Acompanhar status
        </a>

    </div>
    ";

}else{

    echo "Erro: " . $conn->error;

}

?>