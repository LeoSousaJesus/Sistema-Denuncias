<?php
session_start();
include '../includes/db.php';

if(!isset($_SESSION['admin'])){
    header("Location: login.php");
    exit;
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if($id === 0 && !isset($_POST['id'])) {
    header("Location: painel.php");
    exit;
}
if(isset($_POST['id'])) $id = intval($_POST['id']);

// Ações
if(isset($_POST['action'])){
    if($_POST['action'] === 'update') {
        $status = $conn->real_escape_string($_POST['status']);
        $conn->query("UPDATE denuncias SET status = '$status' WHERE id = $id");
        header("Location: detalhe.php?id=$id&msg=sucesso");
        exit;
    } 
    elseif($_POST['action'] === 'delete') {
        $resImg = $conn->query("SELECT imagem FROM denuncias WHERE id = $id");
        if($resImg && $resImg->num_rows > 0) {
            $imgDados = $resImg->fetch_assoc();
            if(!empty($imgDados['imagem']) && file_exists("../uploads/".$imgDados['imagem'])) {
                unlink("../uploads/".$imgDados['imagem']);
            }
        }
        $conn->query("DELETE FROM denuncias WHERE id = $id");
        header("Location: painel.php?msg=excluido");
        exit;
    }
}

// Busca os dados da denúncia
$sql = "SELECT * FROM denuncias WHERE id = $id";
$resultado = $conn->query($sql);
if(!$resultado || $resultado->num_rows == 0){
    header("Location: painel.php");
    exit;
}
$dados = $resultado->fetch_assoc();

// Mapeamento visual
$statusClass = "s-recebida";
if($dados['status'] == 'Em Análise' || $dados['status'] == 'Em análise') $statusClass = "s-analise";
if(strpos(strtolower($dados['status']), 'fiscal') !== false) $statusClass = "s-enviada";
if($dados['status'] == 'Resolvida') $statusClass = "s-resolvida";
if($dados['status'] == 'Descartada') $statusClass = "s-descartada";

$emoji = "📝";
$prioHtml = "<span style='background:#F5F5F5; color:#757575; padding:2px 8px; border-radius:999px; font-size:10px; margin-left:4px;'>⚪ Baixa prioridade</span>";
if($dados['categoria'] == 'Foco de Incêndio'){
    $emoji = "🔥";
    $prioHtml = "<span style='background:#FFEBEE; color:#C62828; padding:2px 8px; border-radius:999px; font-size:10px; margin-left:4px;'>🔴 Alta prioridade</span>";
}
if($dados['categoria'] == 'Desmatamento'){
    $emoji = "🌳";
    $prioHtml = "<span style='background:#FFF8E1; color:#F57F17; padding:2px 8px; border-radius:999px; font-size:10px; margin-left:4px;'>🟡 Média prioridade</span>";
}
if($dados['categoria'] == 'Resíduos') $emoji = "🗑️";
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalhes da Denúncia - EcoAlert</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        body { margin: 0; padding: 0; }
        .status-radio { display: none; }
        .status-label { padding:8px 12px; border-radius:8px; font-size:12px; display:flex; align-items:center; gap:8px; cursor:pointer; border:0.5px solid #E0E0E0; color:#4A4A4A; }
        .status-radio:checked + .status-label { background:#E3F2FD; border:1.5px solid #90CAF9; color:#0D47A1; }
    </style>
</head>
<body>

<div class="admin-layout">
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sb-brand">
            <a href="painel.php" class="sb-logo">🌿 EcoAlert</a>
            <div class="sb-role">Painel Admin</div>
        </div>
        <div class="sb-nav">
            <a href="painel.php" class="sb-item"><i class="ti ti-layout-dashboard"></i> Dashboard</a>
            <a href="painel.php" class="sb-item active"><i class="ti ti-file-text"></i> Denúncias</a>
            <a href="../mapa.php" target="_blank" class="sb-item"><i class="ti ti-map-pin"></i> Mapa Público</a>
            <a href="logout.php" class="sb-item" style="margin-top:auto; color:#F28B82; border:none;"><i class="ti ti-logout"></i> Sair</a>
        </div>
    </div>
    
    <!-- Main -->
    <div class="admin-main">
        <div class="admin-topbar">
            <div class="flex items-center gap-2">
                <a href="painel.php" style="font-size:12px; color:#2E7D32; cursor:pointer; text-decoration:none;">← Voltar</a>
                <div class="page-title" style="margin-left: 10px;"><?= $dados['protocolo'] ?></div>
                <span class="status-pill <?= $statusClass ?>" style="margin-left: 10px;"><?= $dados['status'] ?></span>
            </div>
            <div class="flex gap-2">
                <form method="POST" onsubmit="return confirm('Tem certeza que deseja excluir esta denúncia definitivamente?');" style="margin:0;">
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="id" value="<?= $id ?>">
                    <button type="submit" style="background:#FFEBEE; color:#C62828; border:0.5px solid #FFCDD2; padding:7px 12px; border-radius:8px; font-size:11px; font-weight:600; cursor:pointer; display:flex; align-items:center; gap:4px;"><i class="ti ti-trash"></i> Excluir</button>
                </form>
            </div>
        </div>
        
        <div class="admin-body">
            <?php if(isset($_GET['msg']) && $_GET['msg'] == 'sucesso'): ?>
                <div style="background:#E8F5E9; color:#2E7D32; padding:10px; border-radius:8px; margin-bottom:16px; font-size:12px; font-weight:600; border:1px solid #A5D6A7;">Status atualizado com sucesso!</div>
            <?php endif; ?>

            <div style="display:grid; grid-template-columns:1.4fr .6fr; gap:16px;">
                <!-- Left: Info -->
                <div>
                    <div style="background:#fff; border:0.5px solid #E0E0E0; border-radius:10px; padding:16px; margin:0 0 12px;">
                        <div style="font-size:14px; font-weight:600; color:#1C1C1C; margin:0 0 16px; display:flex; align-items:center; gap:8px;">
                            <span style="font-size:18px;"><?= $emoji ?></span> <?= $dados['categoria'] ?> <?= $prioHtml ?>
                        </div>
                        
                        <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px; font-size:12px; margin:0 0 16px;">
                            <div><div style="color:#9E9E9E; margin:0 0 2px;">Data/Hora</div><div style="color:#1C1C1C; font-weight:500;"><?= date("d/m/Y \à\s H:i", strtotime($dados['data_criacao'])) ?></div></div>
                            <div><div style="color:#9E9E9E; margin:0 0 2px;">Anonimato</div><div style="color:#F57F17; font-weight:500;">✓ Denúncia anônima</div></div>
                            <div style="grid-column: span 2;">
                                <div style="color:#9E9E9E; margin:0 0 2px;">Localização Reportada</div>
                                <div style="color:#1565C0; font-weight:500;"><?= !empty($dados['endereco']) ? htmlspecialchars($dados['endereco']) : "Não informada" ?></div>
                            </div>
                            <div style="grid-column: span 2;">
                                <div style="color:#9E9E9E; margin:0 0 2px;">Coordenadas (GPS)</div>
                                <div style="color:#1C1C1C; font-size:11px;"><?= $dados['latitude'] ?>, <?= $dados['longitude'] ?></div>
                            </div>
                        </div>
                        
                        <div>
                            <div style="font-size:12px; font-weight:600; color:#4A4A4A; margin:0 0 6px;">Relato do denunciante</div>
                            <div style="background:#F5F5F5; border-radius:8px; padding:12px; font-size:13px; color:#1C1C1C; line-height:1.65; border-left:3px solid #C8E6C9;">"<?= nl2br(htmlspecialchars($dados['descricao'])) ?>"</div>
                        </div>
                    </div>
                    
                    <!-- Anexo -->
                    <div style="background:#fff; border:0.5px solid #E0E0E0; border-radius:10px; padding:16px;">
                        <div style="font-size:12px; font-weight:600; color:#1C1C1C; margin:0 0 10px;">Evidências Anexadas</div>
                        <?php if(!empty($dados['imagem'])): ?>
                            <div style="display:flex; gap:12px; align-items:center;">
                                <a href="../uploads/<?= $dados['imagem'] ?>" target="_blank" style="display:inline-block;">
                                    <div style="width:120px; height:80px; background:#E8F5E9; border-radius:6px; border:0.5px solid #A5D6A7; background-image:url('../uploads/<?= $dados['imagem'] ?>'); background-size:cover; background-position:center;"></div>
                                </a>
                                <a href="../uploads/<?= $dados['imagem'] ?>" target="_blank" download style="display:flex; align-items:center; gap:6px; padding:8px 12px; font-size:11px; font-weight:600; color:#1565C0; cursor:pointer; border:0.5px solid #E3F2FD; border-radius:6px; background:#E3F2FD; text-decoration:none;">
                                    <i class="ti ti-download"></i> Baixar Imagem
                                </a>
                            </div>
                        <?php else: ?>
                            <div style="font-size:12px; color:#9E9E9E; font-style:italic;">Nenhuma foto anexada pelo cidadão.</div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Right: Status & Actions -->
                <div>
                    <form method="POST" style="background:#fff; border:0.5px solid #E0E0E0; border-radius:10px; padding:16px; margin:0 0 12px;">
                        <input type="hidden" name="action" value="update">
                        <input type="hidden" name="id" value="<?= $id ?>">
                        
                        <div style="font-size:12px; font-weight:600; color:#1C1C1C; margin:0 0 12px;">Atualizar Status</div>
                        
                        <div style="display:flex; flex-direction:column; gap:6px;">
                            <?php 
                            $opcoes = [
                                'Recebida' => '#0D47A1',
                                'Em análise' => '#F9A825',
                                'Fiscalização enviada' => '#9C27B0',
                                'Resolvida' => '#2E7D32',
                                'Descartada' => '#C62828'
                            ];
                            foreach($opcoes as $op => $color): 
                                $isChecked = ($dados['status'] == $op) ? "checked" : "";
                            ?>
                                <label>
                                    <input type="radio" name="status" value="<?= $op ?>" class="status-radio" <?= $isChecked ?>>
                                    <div class="status-label">
                                        <div style="width:10px; height:10px; border-radius:50%; background:<?= $color ?>; flex-shrink:0;"></div>
                                        <?= $op ?> <?= $isChecked ? '<span style="margin-left:auto; font-size:10px; font-weight:600; text-transform:uppercase;">atual</span>' : '' ?>
                                    </div>
                                </label>
                            <?php endforeach; ?>
                        </div>
                        
                        <button type="submit" style="width:100%; background:#2E7D32; color:#fff; padding:12px; border:none; border-radius:8px; font-size:12px; font-weight:700; text-align:center; margin-top:16px; cursor:pointer;">Salvar Alteração</button>
                    </form>
                    
                    <!-- Ver no Maps -->
                    <?php if(!empty($dados['latitude']) && !empty($dados['longitude'])): ?>
                    <a href="https://www.google.com/maps?q=<?= $dados['latitude'] ?>,<?= $dados['longitude'] ?>" target="_blank" style="display:block; text-decoration:none; background:#E3F2FD; border:0.5px solid #90CAF9; border-radius:10px; padding:16px; text-align:center; cursor:pointer; transition:0.3s;">
                        <div style="font-size:24px; margin:0 0 6px;">🗺️</div>
                        <div style="font-size:13px; font-weight:600; color:#0D47A1;">Abrir no Google Maps</div>
                        <div style="font-size:11px; color:#1565C0; margin-top:2px;">Ver localização exata da ocorrência</div>
                    </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
