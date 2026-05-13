<?php

include 'conexao.php';

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Status da Denúncia</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">

    <h1>Acompanhar denúncia</h1>

    <form method="GET">

        <input
            type="text"
            name="protocolo"
            placeholder="Digite o protocolo"
            required
        >

        <br><br>

        <button type="submit" class="botao">
            Consultar
        </button>

    </form>

    <br>

<?php

if(isset($_GET['protocolo'])){

    $protocolo = $_GET['protocolo'];

    $sql = "SELECT * FROM denuncias
            WHERE protocolo = '$protocolo'";

    $resultado = $conn->query($sql);

    if($resultado->num_rows > 0){

        $dados = $resultado->fetch_assoc();

        echo "

        <h2>Status:</h2>

        <div class='status-box'>

            <p><strong>Protocolo:</strong></p>
            <p>$dados[protocolo]</p>

            <hr>

            <p><strong>Status atual:</strong></p>
            <p class='status'>$dados[status]</p>

            <hr>

            <p><strong>Descrição:</strong></p>
            <p>$dados[descricao]</p>

        </div>

        ";

    }else{

        echo "<p>Protocolo não encontrado.</p>";

    }

}

?>

</div>

</body>
</html>