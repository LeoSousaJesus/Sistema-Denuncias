<?php
// ==========================================
// PAINEL DE GESTÃO DE DENÚNCIAS
// ==========================================
// Esta tela permite aos administradores visualizarem
// todas as denúncias registradas e alterar o status.

// Inclui a conexão com o banco de dados e inicia a sessão
include '../includes/db.php';
session_start();

// Verifica se a variável de sessão 'admin' não está configurada
// Se não estiver logado, redireciona para a página de login
if(!isset($_SESSION['admin'])){
    header("Location: login.php");
    exit;
}

// Verifica se uma requisição POST de atualização de status foi feita
if(isset($_POST['id'])){
    // Evita injeção de SQL usando cast para inteiro no ID
    $id = intval($_POST['id']);
    // Limpa a string de status recebida
    $status = $conn->real_escape_string($_POST['status']);

    // Prepara e executa a query de atualização do status
    $sqlUpdate = "UPDATE denuncias SET status = '$status' WHERE id = $id";
    $conn->query($sqlUpdate);
}

// Busca todas as denúncias na base de dados
// Ordena pelas mais recentes (DESC na data_criacao)
$sql = "SELECT * FROM denuncias ORDER BY data_criacao DESC";
$resultado = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel de Gestão - EcoAlert</title>
    
    <!-- Link para a folha de estilo e fontes -->
    <link rel="stylesheet" href="../assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Estilos específicos para o painel -->
    <style>
        .painel-container { width: 95%; max-width: 1200px; margin: 40px auto; background: var(--white); padding: 30px; border-radius: var(--radius); box-shadow: var(--shadow); }
        .painel-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; border-bottom: 1px solid var(--border-color); padding-bottom: 15px; }
        .btn-logout { background: #e53935; color: white; padding: 10px 20px; border-radius: 6px; text-decoration: none; font-weight: 500; transition: 0.3s; }
        .btn-logout:hover { background: #c62828; }
        select { margin-bottom: 0; padding: 8px; width: auto; display: inline-block; font-size: 14px; }
        .btn-update { background: var(--primary-color); color: white; border: none; padding: 8px 14px; border-radius: 6px; cursor: pointer; font-weight: 500; }
        .btn-update:hover { background: var(--primary-hover); }
        .badge { display: inline-block; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 600; background: #e0e0e0; }
        .badge.recebida { background: #FFF9C4; color: #F57F17; }
        .badge.analise { background: #E1F5FE; color: #0288D1; }
        .badge.enviada { background: #E8F5E9; color: #388E3C; }
        .badge.resolvida { background: #C8E6C9; color: #2E7D32; }
    </style>
</head>
<body style="background-color: var(--secondary-color);">

<div class="painel-container">
    <!-- Cabeçalho do Painel -->
    <div class="painel-header">
        <div>
            <h1 style="margin: 0; text-align: left;">Painel de Gestão - EcoAlert</h1>
            <p style="text-align: left; margin-bottom: 0; margin-top: 5px;">Acompanhamento de queimadas e descarte irregular</p>
        </div>
        <a href="logout.php" class="btn-logout">Sair do Sistema</a>
    </div>

    <!-- Tabela de Denúncias -->
    <table>
        <thead>
            <tr>
                <th>Protocolo</th>
                <th>Descrição</th>
                <th>Anexo</th>
                <th>Localização</th>
                <th>Ação / Status</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Verifica se a query retornou dados
            if ($resultado && $resultado->num_rows > 0) {
                // Laço de repetição para percorrer cada linha (denúncia)
                while($dados = $resultado->fetch_assoc()){
                    echo "<tr>";
                    
                    // Coluna 1: Protocolo e Data
                    echo "<td>
                            <strong>{$dados['protocolo']}</strong><br>
                            <small style='color: #888;'>Data: " . (isset($dados['data_criacao']) ? date("d/m/Y H:i", strtotime($dados['data_criacao'])) : "N/D") . "</small>
                          </td>";
                    
                    // Coluna 2: Descrição do problema
                    echo "<td><div style='max-width: 300px; white-space: normal;'>" . htmlspecialchars($dados['descricao']) . "</div></td>";
                    
                    // Coluna 3: Tratamento e exibição da Imagem (se existir)
                    echo "<td>";
                    if(!empty($dados['imagem'])){
                        echo "<a href='../uploads/{$dados['imagem']}' target='_blank' style='color: var(--primary-color); font-weight: 500; text-decoration: none;'>Ver Imagem 📸</a>";
                    } else {
                        echo "<span style='color: #aaa;'>Sem anexo</span>";
                    }
                    echo "</td>";
                    
                    // Coluna 4: Link para o Google Maps utilizando Latitude e Longitude
                    echo "<td>";
                    if(!empty($dados['latitude']) && !empty($dados['longitude'])) {
                        echo "<a href='https://www.google.com/maps?q={$dados['latitude']},{$dados['longitude']}' target='_blank' style='color: var(--primary-color); font-weight: 500; text-decoration: none;'>Abrir Mapa 📍</a>";
                    } else {
                        echo "<span style='color: #aaa;'>Local não capturado</span>";
                    }
                    echo "</td>";
                    
                    // Coluna 5: Formulário para gerenciar o Status da denúncia
                    echo "<td>";
                    echo "<form method='POST' style='display: flex; gap: 8px; align-items: center;'>";
                    echo "<input type='hidden' name='id' value='{$dados['id']}'>";
                    echo "<select name='status'>";
                    
                    // Lista das opções de fluxo de status possíveis
                    $opcoes_status = ['Recebida', 'Em análise', 'Fiscalização enviada', 'Resolvida', 'Descartada'];
                    foreach ($opcoes_status as $opcao) {
                        // Verifica o status atual para deixar selecionado (selected) no menu
                        $selected = ($dados['status'] == $opcao) ? "selected" : "";
                        echo "<option value='$opcao' $selected>$opcao</option>";
                    }
                    
                    echo "</select>";
                    echo "<button type='submit' class='btn-update'>Atualizar</button>";
                    echo "</form>";
                    echo "</td>";
                    
                    echo "</tr>";
                }
            } else {
                // Exibe caso a tabela esteja vazia
                echo "<tr><td colspan='5' style='text-align: center; padding: 30px; color: #666;'>Nenhuma denúncia cadastrada até o momento.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

</body>
</html>
