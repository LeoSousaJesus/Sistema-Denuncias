<?php
session_start();

if(isset($_POST['usuario'])){
    $usuario = $_POST['usuario'];
    $senha = $_POST['senha'];

    if($usuario == "admin" && $senha == "Ecoalert123"){
        $_SESSION['admin'] = true;
        header("Location: painel.php");
        exit;
    } else {
        $erro = "Credenciais inválidas. Tente novamente.";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acesso Restrito - EcoAlert</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<div class="topbar">
    <a href="../index.php" class="logo">🌿 EcoAlert</a>
    <a href="../index.php" style="font-size:12px; color:#2E7D32; text-decoration:none;">← Voltar ao início</a>
</div>

<div style="max-width: 480px; margin: 60px auto; padding: 0 20px;">
    <div style="border:0.5px solid #E0E0E0; border-radius:12px; padding:24px; background:#fff; box-shadow: 0 4px 12px rgba(0,0,0,0.03);">
        <div style="font-size:28px; margin:0 0 8px;">🛡️</div>
        <div style="font-family:'DM Serif Display',serif; font-size:20px; color:#1C1C1C; margin:0 0 6px;">Acesso Restrito</div>
        <div style="font-size:13px; color:#757575; margin:0 0 20px; line-height:1.5;">Área exclusiva para servidores e fiscais credenciados.</div>
        
        <form method="POST">
            <div style="margin:0 0 12px;">
                <label style="font-size:12px; font-weight:600; color:#4A4A4A; margin:0 0 6px; display:block;">Usuário</label>
                <input type="text" name="usuario" required placeholder="Digite seu usuário..." style="width:100%; box-sizing:border-box; background:#F5F5F5; border:0.5px solid #E0E0E0; border-radius:8px; padding:12px 14px; font-size:14px; color:#1C1C1C; font-family:'DM Sans',sans-serif;">
            </div>
            <div style="margin:0 0 20px;">
                <label style="font-size:12px; font-weight:600; color:#4A4A4A; margin:0 0 6px; display:block;">Senha</label>
                <input type="password" name="senha" required placeholder="Sua senha..." style="width:100%; box-sizing:border-box; background:#F5F5F5; border:0.5px solid #E0E0E0; border-radius:8px; padding:12px 14px; font-size:14px; color:#1C1C1C; font-family:'DM Sans',sans-serif;">
            </div>
            
            <button type="submit" style="width:100%; background:#1B5E20; border:none; color:#fff; padding:12px; border-radius:8px; font-size:13px; font-weight:700; cursor:pointer;">Entrar no Painel →</button>
            
            <?php if(isset($erro)): ?>
            <div style="font-size:12px; color:#C62828; text-align:center; margin-top:12px; background:#FFEBEE; padding:8px; border-radius:6px; border:1px solid #FFCDD2;">
                ⚠️ <?= $erro ?>
            </div>
            <?php endif; ?>
        </form>
    </div>
    
    <div style="margin-top:20px; padding:14px 20px; display:flex; align-items:center; gap:10px; background:#FFF8E1; border:0.5px solid #F9CE6A; border-radius:8px;">
        <span style="font-size:16px;">🔒</span>
        <div style="font-size:11px; color:#B45309; line-height:1.4;">Esta área é monitorada. Acessos não autorizados são registrados e sujeitos a penalidades legais.</div>
    </div>
</div>

</body>
</html>
