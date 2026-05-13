<?php
// ==========================================
// PÁGINA DE SUCESSO
// ==========================================
// Exibe a confirmação de que a denúncia foi 
// salva com sucesso (Padrão PRG).

include 'includes/header.php';

$protocolo = isset($_GET['protocolo']) ? htmlspecialchars($_GET['protocolo']) : '';
$erro = isset($_GET['erro']) ? htmlspecialchars($_GET['erro']) : '';
?>

<?php if($protocolo): ?>
    <div class='container' style='margin-top: 40px; text-align: center; padding: 40px 30px;'>
        <div style='font-size: 70px; margin-bottom: 15px;'>🌿</div>
        <h1 style='font-size: 26px; margin-bottom: 10px;'>Denúncia Registrada!</h1>
        <p style='font-size: 16px; margin-bottom: 30px;'>Agradecemos a sua colaboração. Nossa equipe de fiscalização já foi notificada.</p>
        
        <div style='background: var(--bg-color); border: 1px solid var(--border-color); padding: 25px; border-radius: var(--radius); margin-bottom: 30px;'>
            <p style='margin-bottom: 8px; font-size: 13px; font-weight: 600; text-transform: uppercase; letter-spacing: 1.5px; color: var(--text-light);'>Número de Protocolo</p>
            <h2 style='font-size: 32px; margin: 0; color: var(--primary-color); letter-spacing: 1px;'><?= $protocolo ?></h2>
        </div>
        
        <p style='font-size: 14px; margin-bottom: 30px;'>Guarde este número para consultar o andamento da sua denúncia posteriormente.</p>
        
        <a href='status.php?protocolo=<?= $protocolo ?>' class='botao' style='margin-bottom: 20px;'>Acompanhar Status Agora</a>
        <a href='index.php' style='display: inline-block; color: var(--text-light); text-decoration: none; font-weight: 500; font-size: 15px; transition: color 0.3s;'>&larr; Voltar à página inicial</a>
    </div>

<?php elseif($erro): ?>
    <div class='container' style='margin-top: 40px; text-align: center; padding: 40px 30px;'>
        <div style='font-size: 70px; margin-bottom: 15px;'>⚠️</div>
        <h1 style='font-size: 26px; color: #D32F2F; margin-bottom: 10px;'>Erro ao Registrar</h1>
        <p style='font-size: 16px; margin-bottom: 30px;'>Infelizmente, ocorreu um erro ao salvar sua denúncia. Por favor, tente novamente.</p>
        <div style='background: #ffebee; border: 1px solid #ffcdd2; padding: 15px; border-radius: var(--radius-sm); margin-bottom: 30px; text-align: left;'>
            <p style='margin: 0; font-size: 13px; color: #c62828; font-family: monospace;'>Detalhes: <?= $erro ?></p>
        </div>
        <a href='denuncia.php' class='botao'>Tentar Novamente</a>
        <a href='index.php' style='display: inline-block; margin-top: 20px; color: var(--text-light); text-decoration: none; font-weight: 500; font-size: 15px;'>&larr; Voltar à página inicial</a>
    </div>

<?php else: ?>
    <!-- Acesso direto indevido -->
    <script>window.location.href = 'index.php';</script>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>
