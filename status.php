<?php
// ==========================================
// PÁGINA DE CONSULTA DE STATUS
// ==========================================
include 'includes/db.php';
include 'includes/header.php';
?>

<div class="topbar">
    <a href="index.php" class="logo">🌿 EcoAlert</a>
    <a href="index.php" style="font-size:12px; color:#2E7D32; text-decoration:none;">← Voltar ao início</a>
</div>

<div style="max-width: 480px; margin: 60px auto; padding: 0 20px;">
    
    <!-- Formulário de Consulta -->
    <div style="border:0.5px solid #C8E6C9; border-radius:12px; padding:24px; background:#F9FBF7; box-shadow: 0 4px 12px rgba(0,0,0,0.03);">
        <div style="font-size:28px; margin:0 0 8px;">🔍</div>
        <div style="font-family:'DM Serif Display',serif; font-size:20px; color:#1C1C1C; margin:0 0 6px;">Consultar Protocolo</div>
        <div style="font-size:13px; color:#757575; margin:0 0 20px; line-height:1.5;">Acompanhe o status da sua denúncia de forma anônima.</div>
        
        <form method="GET" action="status.php">
            <div style="margin:0 0 16px;">
                <label style="font-size:12px; font-weight:600; color:#4A4A4A; margin:0 0 6px; display:block;">Número do Protocolo</label>
                <input type="text" name="protocolo" required value="<?= isset($_GET['protocolo']) ? htmlspecialchars($_GET['protocolo']) : '' ?>" placeholder="Ex: DEN-2026-1234" style="width:100%; box-sizing:border-box; background:#fff; border:0.5px solid #C8E6C9; border-radius:8px; padding:12px 14px; font-size:14px; color:#1C1C1C; font-family:'DM Sans',sans-serif;">
            </div>
            <button type="submit" style="width:100%; background:#2E7D32; border:none; color:#fff; padding:12px; border-radius:8px; font-size:13px; font-weight:700; cursor:pointer;">Consultar Status</button>
        </form>
    </div>

    <!-- Resultado da Busca -->
    <?php
    if(isset($_GET['protocolo'])){
        $protocolo = $conn->real_escape_string($_GET['protocolo']);
        $sql = "SELECT * FROM denuncias WHERE protocolo = '$protocolo'";
        $resultado = $conn->query($sql);

        if($resultado->num_rows > 0){
            $dados = $resultado->fetch_assoc();
            
            // Lógica de classes de status compatível com a Tabela (Tela 5)
            $statusClass = "s-recebida";
            if($dados['status'] == 'Em Análise' || $dados['status'] == 'Em análise') $statusClass = "s-analise";
            if(strpos(strtolower($dados['status']), 'fiscal') !== false) $statusClass = "s-enviada";
            if($dados['status'] == 'Resolvida') $statusClass = "s-resolvida";
            if($dados['status'] == 'Descartada') $statusClass = "s-descartada";

            echo "
            <div style='margin-top: 24px; background:#fff; border:0.5px solid #E0E0E0; border-radius:12px; padding:20px;'>
                <div style='font-size:11px; font-weight:600; letter-spacing:.08em; text-transform:uppercase; color:#757575; margin:0 0 12px;'>Resultado da Busca</div>
                
                <div style='display:flex; justify-content:space-between; align-items:center; padding-bottom:16px; border-bottom:0.5px solid #F5F5F5; margin-bottom:16px;'>
                    <div>
                        <div style='font-size:11px; color:#9E9E9E; margin:0 0 2px;'>Protocolo</div>
                        <div style='font-family:\"DM Serif Display\",serif; font-size:20px; color:#1B5E20;'>{$dados['protocolo']}</div>
                    </div>
                    <div style='text-align:right;'>
                        <div style='font-size:11px; color:#9E9E9E; margin:0 0 4px;'>Status Atual</div>
                        <span class='status-pill {$statusClass}'>{$dados['status']}</span>
                    </div>
                </div>

                <div style='margin-bottom:12px;'>
                    <div style='font-size:11px; font-weight:600; color:#4A4A4A; margin:0 0 4px;'>Localização</div>
                    <div style='font-size:13px; color:#1565C0; background:#E3F2FD; padding:8px 12px; border-radius:8px;'>📍 " . (!empty($dados['endereco']) ? htmlspecialchars($dados['endereco']) : 'Localização via coordenadas') . "</div>
                </div>

                <div>
                    <div style='font-size:11px; font-weight:600; color:#4A4A4A; margin:0 0 4px;'>Categoria Registrada</div>
                    <div style='font-size:13px; color:#1C1C1C; background:#F5F5F5; padding:8px 12px; border-radius:8px;'>{$dados['categoria']}</div>
                </div>
            </div>";
        } else {
            echo "
            <div style='margin-top: 24px; background:#FFEBEE; border:0.5px solid #FFCDD2; color:#C62828; padding:16px; border-radius:12px; text-align:center;'>
                <div style='font-weight:600; font-size:14px; margin-bottom:4px;'>Protocolo não encontrado</div>
                <div style='font-size:12px;'>Verifique se digitou corretamente.</div>
            </div>";
        }
    }
    ?>

</div>

<?php include 'includes/footer.php'; ?>