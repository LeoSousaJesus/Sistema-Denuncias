<?php
// ==========================================
// PÁGINA DE SUCESSO (PRG Pattern)
// ==========================================
include 'includes/header.php';

$protocolo = isset($_GET['protocolo']) ? htmlspecialchars($_GET['protocolo']) : '';
$erro = isset($_GET['erro']) ? htmlspecialchars($_GET['erro']) : '';
?>

<div class="topbar">
    <a href="index.php" class="logo">🌿 EcoAlert</a>
    <div style="font-size:12px; color:#757575;">
        <?= $protocolo ? 'Denúncia registrada com sucesso' : 'Aviso do Sistema' ?>
    </div>
</div>

<?php if($erro): ?>
    <div style="padding:40px 32px; text-align:center; background:#fff; border-bottom:0.5px solid #E0E0E0; min-height: 70vh; display: flex; flex-direction: column; justify-content: center; align-items: center;">
        <div style="width:64px; height:64px; background:#FFEBEE; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:30px; margin:0 auto 16px;">❌</div>
        <div style="font-family:'DM Serif Display',serif; font-size:26px; color:#C62828; margin:0 0 8px;">Erro ao Registrar</div>
        <div style="font-size:14px; color:#4A4A4A; max-width:400px; margin:0 auto 24px; line-height:1.65;"><?= $erro ?></div>
        <a href="denuncia.php" class="btn-primary" style="border: 1px solid #E0E0E0;">Tentar Novamente</a>
    </div>
<?php elseif($protocolo): ?>
    <div style="padding:40px 32px; text-align:center; background:#fff; border-bottom:0.5px solid #E0E0E0;">
        <div style="width:64px; height:64px; background:#E8F5E9; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:30px; margin:0 auto 16px;">✅</div>
        <div style="font-family:'DM Serif Display',serif; font-size:26px; color:#1B5E20; margin:0 0 8px;">Denúncia Registrada!</div>
        <div style="font-size:14px; color:#4A4A4A; max-width:400px; margin:0 auto 24px; line-height:1.65;">Sua ocorrência foi recebida com sucesso e já está em análise pela nossa equipe de fiscalização. Guarde seu protocolo abaixo.</div>
        
        <div style="background:#F1F8E9; border:1.5px solid #A5D6A7; border-radius:12px; padding:20px 32px; display:inline-block; margin:0 0 24px;">
            <div style="font-size:11px; font-weight:600; letter-spacing:.1em; text-transform:uppercase; color:#757575; margin:0 0 6px;">Número de Protocolo</div>
            <div style="font-family:'DM Serif Display',serif; font-size:36px; color:#1B5E20; letter-spacing:.04em;" id="protocol-text"><?= $protocolo ?></div>
            <div style="font-size:12px; color:#4A4A4A; margin-top:6px;">Registrado em <?= date('d/m/Y \à\s H:i') ?></div>
        </div>
        
        <div style="display:flex; gap:12px; justify-content:center; flex-wrap:wrap;">
            <div onclick="navigator.clipboard.writeText('<?= $protocolo ?>'); alert('Protocolo copiado!');" style="background:#2E7D32; color:#fff; padding:11px 22px; border-radius:8px; font-size:13px; font-weight:700; cursor:pointer;">📋 Copiar protocolo</div>
            <a href="status.php?protocolo=<?= $protocolo ?>" style="background:#fff; border:0.5px solid #E0E0E0; color:#4A4A4A; padding:11px 20px; border-radius:8px; font-size:13px; cursor:pointer; text-decoration:none;">🔍 Consultar status</a>
            <a href="mapa.php" style="background:#fff; border:0.5px solid #E0E0E0; color:#4A4A4A; padding:11px 20px; border-radius:8px; font-size:13px; cursor:pointer; text-decoration:none;">🗺️ Ver no mapa</a>
        </div>
    </div>

    <!-- Próximos passos -->
    <div style="padding:40px 32px; max-width: 800px; margin: 0 auto;">
        <div style="font-size:14px; font-weight:600; color:#1C1C1C; margin:0 0 12px; text-align: center;">O que acontece agora?</div>
        <div style="display:grid; grid-template-columns:1fr 1fr 1fr; gap:12px;">
            <div style="background:#fff; border:0.5px solid #E0E0E0; border-radius:8px; padding:16px;">
                <div style="font-size:16px; margin:0 0 6px;">📥</div>
                <div style="font-size:12px; font-weight:600; color:#1C1C1C; margin:0 0 3px;">Recebida</div>
                <div style="font-size:11px; color:#757575;">Sua denúncia foi registrada no sistema com sucesso.</div>
            </div>
            <div style="background:#fff; border:0.5px solid #E0E0E0; border-radius:8px; padding:16px;">
                <div style="font-size:16px; margin:0 0 6px;">🔍</div>
                <div style="font-size:12px; font-weight:600; color:#1C1C1C; margin:0 0 3px;">Em análise</div>
                <div style="font-size:11px; color:#757575;">Nossa equipe verifica as informações em até 48 horas.</div>
            </div>
            <div style="background:#fff; border:0.5px solid #E0E0E0; border-radius:8px; padding:16px;">
                <div style="font-size:16px; margin:0 0 6px;">✅</div>
                <div style="font-size:12px; font-weight:600; color:#1C1C1C; margin:0 0 3px;">Resolução</div>
                <div style="font-size:11px; color:#757575;">Ações de fiscalização são tomadas e o status é atualizado.</div>
            </div>
        </div>
    </div>
<?php else: ?>
    <!-- Acesso direto indevido -->
    <div style="padding:40px; text-align:center;">
        <p>Acesso inválido.</p>
        <a href="index.php">Voltar ao Início</a>
    </div>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>
