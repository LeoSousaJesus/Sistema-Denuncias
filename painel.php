<?php

include 'conexao.php';
session_start();

if(!isset($_SESSION['admin'])){

    header("Location: login.php");
    exit;

};

if(isset($_POST['id'])){

    $id = $_POST['id'];
    $status = $_POST['status'];

    $sqlUpdate = "UPDATE denuncias
                  SET status = '$status'
                  WHERE id = $id";

    $conn->query($sqlUpdate);
}



$sql = "SELECT * FROM denuncias
        ORDER BY data_criacao DESC";

$resultado = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Painel Administrativo</title>

    <link rel="stylesheet" href="../style.css">

    <style>
		
        table{
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td{
            border: 1px solid #ccc;
            padding: 10px;
            text-align: center;
        }

        th{
            background: #2E7D32;
            color: white;
        }

        select{
            padding: 5px;
        }

    </style>

</head>
<body>

<div class="container" style="max-width: 1200px;">

    <h1>Painel Administrativo</h1>
	

    <table>

        <tr>
            <th>ID</th>
            <th>Protocolo</th>
            <th>Descrição</th>
			<th>Status</th>
            <th>Imagem</th>
			<th>Localização</th>
			<th>Ação</th>
        </tr>
<a href="logout.php" class="botao">
    Sair
</a>
<?php

while($dados = $resultado->fetch_assoc()){

    echo "

    <tr>

        <td>$dados[id]</td>

        <td>$dados[protocolo]</td>

        <td>$dados[descricao]</td>

        <td>$dados[status]</td>

        <td>

    ";

    if($dados['imagem'] != ""){

        echo "
        <a href='../uploads/$dados[imagem]' target='_blank'>
            Ver imagem
        </a>
        ";

    }else{

        echo "Sem imagem";

    }
	echo "

<td>

    <a
        href='https://www.google.com/maps?q=$dados[latitude],$dados[longitude]'
        target='_blank'
        class='botao'
    >
        Ver localização
    </a>

</td>

";

    echo "

        </td>

        <td>

            <form method='POST'>

                <input type='hidden'
                       name='id'
                       value='$dados[id]'>

                <select name='status'>

                    <option>Recebida</option>
                    <option>Em análise</option>
                    <option>Fiscalização enviada</option>
                    <option>Resolvida</option>

                </select>

                <br><br>

                <button type='submit' class='botao'>
                    Atualizar
                </button>

            </form>

        </td>

    </tr>

    ";

}

?>

    </table>

</div>

</body>
</html>