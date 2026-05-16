<?php
session_start();
include '../includes/db.php';

if(!isset($_SESSION['admin'])){
    header("Location: login.php");
    exit;
}

// Métricas Dinâmicas
$totalQuery = $conn->query("SELECT COUNT(*) as c FROM denuncias");
$total = $totalQuery->fetch_assoc()['c'];

$analiseQuery = $conn->query("SELECT COUNT(*) as c FROM denuncias WHERE status='Em análise' OR status='Em Análise'");
$analise = $analiseQuery->fetch_assoc()['c'];

$urgenteQuery = $conn->query("SELECT COUNT(*) as c FROM denuncias WHERE categoria='Foco de Incêndio' AND status NOT IN ('Resolvida', 'Descartada')");
$urgente = $urgenteQuery->fetch_assoc()['c'];

$resolvidasQuery = $conn->query("SELECT COUNT(*) as c FROM denuncias WHERE status='Resolvida'");
$resolvidas = $resolvidasQuery->fetch_assoc()['c'];
$taxa = $total > 0 ? round(($resolvidas / $total) * 100) : 0;

// Gráfico de Barras: Últimos 7 dias
$graficoDias = [];
for($i=6; $i>=0; $i--) {
    $dateStr = date('Y-m-d', strtotime("-$i days"));
    $graficoDias[$dateStr] = 0;
}
$sqlDias = "SELECT DATE(data_criacao) as d, COUNT(*) as c FROM denuncias WHERE data_criacao >= DATE_SUB(CURDATE(), INTERVAL 6 DAY) GROUP BY DATE(data_criacao)";
$resDias = $conn->query($sqlDias);
if($resDias){
    while($row = $resDias->fetch_assoc()){
        if(isset($graficoDias[$row['d']])) $graficoDias[$row['d']] = $row['c'];
    }
}
$maxDias = max($graficoDias);
if($maxDias == 0) $maxDias = 1;

// Gráfico Donut: Categorias
$graficoCat = [
    'Desmatamento' => ['count' => 0, 'color' => '#2E7D32'],
    'Resíduos' => ['count' => 0, 'color' => '#1565C0'],
    'Foco de Incêndio' => ['count' => 0, 'color' => '#F9A825'],
    'Outros' => ['count' => 0, 'color' => '#C62828']
];
$totalCat = 0;
$resCat = $conn->query("SELECT categoria, COUNT(*) as c FROM denuncias GROUP BY categoria");
if($resCat){
    while($row = $resCat->fetch_assoc()){
        $cat = $row['categoria'];
        if(isset($graficoCat[$cat])) {
            $graficoCat[$cat]['count'] += $row['c'];
        } else {
            $graficoCat['Outros']['count'] += $row['c'];
        }
        $totalCat += $row['c'];
    }
}
$conicParts = [];
$currentDeg = 0;
foreach($graficoCat as $name => &$data) {
    $pct = $totalCat > 0 ? round(($data['count'] / $totalCat) * 100) : 0;
    $data['pct'] = $pct;
    $deg = $totalCat > 0 ? ($data['count'] / $totalCat) * 360 : 0;
    if($deg > 0) {
        $conicParts[] = "{$data['color']} {$currentDeg}deg " . ($currentDeg + $deg) . "deg";
        $currentDeg += $deg;
    }
}
$conicGradient = empty($conicParts) ? "#E0E0E0 0deg 360deg" : implode(", ", $conicParts);

$sql = "SELECT * FROM denuncias ORDER BY data_criacao DESC";
$resultado = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel de Gestão - EcoAlert</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        body { margin: 0; padding: 0; }
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
            <a href="painel.php" class="sb-item active"><i class="ti ti-layout-dashboard"></i> Dashboard</a>
            <a href="painel.php" class="sb-item"><i class="ti ti-file-text"></i> Denúncias</a>
            <a href="../mapa.php" target="_blank" class="sb-item"><i class="ti ti-map-pin"></i> Mapa Público</a>
            <a href="logout.php" class="sb-item" style="margin-top:auto; color:#F28B82; border:none;"><i class="ti ti-logout"></i> Sair</a>
        </div>
    </div>
    
    <!-- Main -->
    <div class="admin-main">
        <div class="admin-topbar">
            <div class="page-title">Dashboard</div>
            <div class="flex items-center gap-2">
                <div style="font-size:11px; color:#757575;"><?= date('d/m/Y') ?></div>
                <div style="width:28px; height:28px; border-radius:50%; background:#E8F5E9; display:flex; align-items:center; justify-content:center; font-size:14px;">👤</div>
            </div>
        </div>
        
        <div class="admin-body">
            <!-- Métricas -->
            <div class="metrics">
                <div class="metric">
                    <div class="metric-num"><?= $total ?></div>
                    <div class="metric-label">Total de Denúncias</div>
                </div>
                <div class="metric">
                    <div class="metric-num" style="color:#F57F17;"><?= $analise ?></div>
                    <div class="metric-label">Em análise</div>
                </div>
                <div class="metric">
                    <div class="metric-num" style="color:#C62828;"><?= $urgente ?></div>
                    <div class="metric-label">Críticas (Incêndios)</div>
                </div>
                <div class="metric">
                    <div class="metric-num"><?= $taxa ?>%</div>
                    <div class="metric-label">Taxa de resolução</div>
                </div>
            </div>
            
            <!-- Gráficos Dinâmicos -->
            <div style="display:grid; grid-template-columns: 2.2fr 1fr; gap: 16px; margin-bottom: 24px;">
                <!-- Chart 1: Barras -->
                <div style="background:#fff; border:0.5px solid #E0E0E0; border-radius:10px; padding:20px;">
                    <div style="font-size:13px; font-weight:600; color:#4A4A4A; margin-bottom: 24px;">Denúncias por dia (últimos 7 dias)</div>
                    <div style="display:flex; align-items:flex-end; gap:8px; height: 120px;">
                        <?php
                        $diasSemana = ['D','S','T','Q','Q','S','S'];
                        foreach($graficoDias as $dateStr => $count): 
                            $h = ($count / $maxDias) * 100;
                            $diaNome = $diasSemana[date('w', strtotime($dateStr))];
                            $bg = ($dateStr == date('Y-m-d')) ? '#2E7D32' : '#81C784';
                        ?>
                        <div style="flex:1; display:flex; flex-direction:column; align-items:center; gap:8px; height:100%;">
                            <div style="width:100%; height:100px; display:flex; align-items:flex-end; position:relative;">
                                <div style="width:100%; background:<?= $bg ?>; height:<?= $h ?>%; border-radius:4px 4px 0 0; transition:0.3s;" title="<?= $count ?> denúncias"></div>
                            </div>
                            <div style="font-size:10px; color:#9E9E9E; font-weight:600;"><?= $diaNome ?></div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Chart 2: Donut Categorias -->
                <div style="background:#fff; border:0.5px solid #E0E0E0; border-radius:10px; padding:20px;">
                    <div style="font-size:13px; font-weight:600; color:#4A4A4A; margin-bottom: 24px;">Por categoria</div>
                    <div style="display:flex; align-items:center; gap: 24px;">
                        <div style="width:80px; height:80px; border-radius:50%; background: conic-gradient(<?= $conicGradient ?>); position:relative;">
                            <div style="position:absolute; top:18px; left:18px; right:18px; bottom:18px; background:#fff; border-radius:50%;"></div>
                        </div>
                        <div style="display:flex; flex-direction:column; gap:8px; flex:1;">
                            <?php foreach($graficoCat as $name => $data): ?>
                            <div style="display:flex; align-items:center; gap:8px; font-size:11px; color:#757575;">
                                <div style="width:10px; height:10px; border-radius:2px; background:<?= $data['color'] ?>;"></div>
                                <span><?= str_replace('Foco de ', '', $name) ?> &mdash; <?= $data['pct'] ?>%</span>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabela -->
            <div class="table-wrap">
                <div class="table-head">
                    <div class="th-cell">Protocolo</div>
                    <div class="th-cell">Categoria / Endereço</div>
                    <div class="th-cell">Data</div>
                    <div class="th-cell">Status</div>
                    <div class="th-cell">Prioridade</div>
                    <div class="th-cell">Ações</div>
                </div>
                
                <?php
                if ($resultado && $resultado->num_rows > 0) {
                    while($dados = $resultado->fetch_assoc()){
                        // Determinar visual do status
                        $statusClass = "s-recebida";
                        if($dados['status'] == 'Em Análise' || $dados['status'] == 'Em análise') $statusClass = "s-analise";
                        if(strpos(strtolower($dados['status']), 'fiscal') !== false) $statusClass = "s-enviada";
                        if($dados['status'] == 'Resolvida') $statusClass = "s-resolvida";
                        if($dados['status'] == 'Descartada') $statusClass = "s-descartada";
                        
                        // Emoji e Prioridade baseada na categoria
                        $emoji = "📝";
                        $prioHtml = "<span style='color:#4A4A4A; font-size:11px; font-weight:600;'>⚪ Baixa</span>";
                        if($dados['categoria'] == 'Foco de Incêndio'){
                            $emoji = "🔥";
                            $prioHtml = "<span style='color:#C62828; font-size:11px; font-weight:600;'>🔴 Alta</span>";
                        }
                        if($dados['categoria'] == 'Desmatamento'){
                            $emoji = "🌳";
                            $prioHtml = "<span style='color:#F57F17; font-size:11px; font-weight:600;'>🟡 Média</span>";
                        }
                        if($dados['categoria'] == 'Resíduos') $emoji = "🗑️";
                        
                        $end = !empty($dados['endereco']) ? htmlspecialchars($dados['endereco']) : "Local por coordenadas";
                        $dateStr = date("d/m/Y H:i", strtotime($dados['data_criacao']));

                        echo "
                        <div class='table-row'>
                            <div class='td-cell' style='font-weight:600; color:#1B5E20;'>{$dados['protocolo']}</div>
                            <div class='td-cell'>
                                <div class='type-tag'>{$emoji} {$dados['categoria']}</div>
                                <div class='td-muted' style='font-size:10px; margin-top:2px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;'>{$end}</div>
                            </div>
                            <div class='td-cell td-muted'>{$dateStr}</div>
                            <div class='td-cell'><span class='status-pill {$statusClass}'>{$dados['status']}</span></div>
                            <div class='td-cell'>{$prioHtml}</div>
                            <div class='td-cell'>
                                <a href='detalhe.php?id={$dados['id']}' style='color:#1565C0; cursor:pointer; font-size:11px; text-decoration:none; font-weight:600;'>Ver Detalhes →</a>
                            </div>
                        </div>";
                    }
                } else {
                    echo "<div style='padding:30px; text-align:center; color:#757575; font-size:13px;'>Nenhuma denúncia encontrada.</div>";
                }
                ?>
            </div>
        </div>
    </div>
</div>

</body>
</html>
