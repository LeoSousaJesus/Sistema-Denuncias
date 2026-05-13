<?php

session_start();

if(isset($_POST['usuario'])){

    $usuario = $_POST['usuario'];
    $senha = $_POST['senha'];

    if($usuario == "admin" && $senha == "Ecoalert123"){

        $_SESSION['admin'] = true;

        header("Location: painel.php");

    }else{

        $erro = "Usuário ou senha inválidos.";

    }

}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Login Admin</title>

    <link rel="stylesheet" href="../style.css">
</head>
<body>

<div class="container">

    <h1>Área Administrativa</h1>

    <form method="POST">

        <input
            type="text"
            name="usuario"
            placeholder="Usuário"
            required
        >

        <br><br>

        <input
            type="password"
            name="senha"
            placeholder="Senha"
            required
        >

        <br><br>

        <button type="submit" class="botao">
            Entrar
        </button>

    </form>

    <br>

<?php

if(isset($erro)){
    echo "<p>$erro</p>";
}

?>

</div>

</body>
</html>