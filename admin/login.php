<?php
// ==========================================
// PÁGINA DE LOGIN ADMINISTRATIVO
// ==========================================
// Responsável por autenticar servidores e
// liberar o acesso ao painel de gestão.

// Inicia a sessão para controle de acesso
session_start();

// Verifica se o formulário foi enviado via POST
if(isset($_POST['usuario'])){
    $usuario = $_POST['usuario'];
    $senha = $_POST['senha'];

    // Validação de credenciais (Simulada para fins didáticos/protótipo)
    // Em um cenário real, deve-se consultar o banco de dados (tabela usuarios).
    if($usuario == "admin" && $senha == "Ecoalert123"){
        // Define a variável de sessão garantindo que o admin está autenticado
        $_SESSION['admin'] = true;
        // Redireciona para o painel principal
        header("Location: painel.php");
        exit;
    } else {
        // Define mensagem de erro para login inválido
        $erro = "Usuário ou senha inválidos. Tente novamente.";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acesso Restrito - EcoAlert</title>
    <!-- Ajusta o caminho do CSS para sair da pasta admin e entrar em assets -->
    <link rel="stylesheet" href="../assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body style="justify-content: center; align-items: center; background-color: var(--secondary-color);">

<div class="container" style="margin: auto;">
    <div style="text-align: center; margin-bottom: 20px;">
        <span style="font-size: 40px;">🌿</span>
        <h1 style="margin-bottom: 5px;">Área Administrativa</h1>
        <p>Acesso exclusivo para servidores da fiscalização</p>
    </div>

    <!-- Formulário de Autenticação -->
    <form method="POST">
        <label for="usuario" style="display: block; font-weight: 500; margin-bottom: 8px; text-align: left;">Usuário</label>
        <input type="text" name="usuario" id="usuario" placeholder="Digite seu usuário..." required>

        <label for="senha" style="display: block; font-weight: 500; margin-bottom: 8px; text-align: left;">Senha</label>
        <input type="password" name="senha" id="senha" placeholder="Sua senha de acesso..." required>

        <button type="submit" class="botao">
            Entrar no Painel
        </button>
        
        <div style="text-align: center; margin-top: 15px;">
            <a href="../index.php" style="color: var(--text-light); text-decoration: none; font-weight: 500; transition: color 0.3s;">← Voltar para o site</a>
        </div>
    </form>

    <?php
    // Se a variável $erro estiver definida, exibe o alerta visual
    if(isset($erro)){
        echo "<div style='background: #FFEBEE; color: #C62828; padding: 12px; border-radius: 8px; margin-top: 15px; text-align: center; font-weight: 500;'>$erro</div>";
    }
    ?>
</div>

</body>
</html>
