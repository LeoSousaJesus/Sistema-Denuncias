<?php
// ==========================================
// PÁGINA DE CONSULTA DE STATUS
// ==========================================
// Permite ao usuário verificar o andamento de
// sua denúncia através do número do protocolo.

// Inclusão da conexão com banco e cabeçalho visual
include 'includes/db.php';
include 'includes/header.php';
?>

<div class="container" style="margin-top: 40px;">

    <h1 style="text-align: left; display: flex; align-items: center; gap: 10px;">
        <span>🔍</span> Acompanhar Denúncia
    </h1>
    <p style="text-align: left;">Digite o número do protocolo gerado no momento da denúncia para verificar o status de atendimento.</p>

    <!-- Formulário de busca via GET -->
    <form method="GET" action="status.php" style="display: flex; gap: 10px;">

        <input
            type="text"
            name="protocolo"
            placeholder="Ex: DEN-2023-1234"
            required
            style="margin-bottom: 0; flex: 1;"
            value="<?php echo isset($_GET['protocolo']) ? htmlspecialchars($_GET['protocolo']) : ''; ?>"
        >

        <button type="submit" class="botao" style="width: auto; margin-bottom: 0;">
            Consultar
        </button>

    </form>

    <?php
    // Processamento da busca: se houver o parâmetro 'protocolo' na URL
    if(isset($_GET['protocolo'])){
        // Limpa a entrada para evitar injeção SQL
        $protocolo = $conn->real_escape_string($_GET['protocolo']);

        // Busca no banco o protocolo exato
        $sql = "SELECT * FROM denuncias WHERE protocolo = '$protocolo'";
        $resultado = $conn->query($sql);

        // Se encontrar 1 ou mais resultados (deveria ser apenas 1 pela lógica do sistema)
        if($resultado->num_rows > 0){
            // Extrai a linha do banco como um array associativo
            $dados = $resultado->fetch_assoc();

            // Lógica de cores baseada no status
            $statusCor = "#666";
            if($dados['status'] == 'Recebida') $statusCor = "#F57F17";
            if($dados['status'] == 'Em análise') $statusCor = "#0288D1";
            if($dados['status'] == 'Fiscalização enviada') $statusCor = "#388E3C";
            if($dados['status'] == 'Resolvida') $statusCor = "#2E7D32";

            echo "
            <div style='margin-top: 30px;'>
                <h2 style='font-size: 18px; border-bottom: 1px solid var(--border-color); padding-bottom: 10px;'>Resultado da Busca:</h2>

                <div class='status-box'>
                    <div style='display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 15px;'>
                        <div>
                            <p style='margin: 0; font-size: 14px; color: var(--text-light); text-transform: uppercase;'>Protocolo:</p>
                            <p style='margin: 0; font-size: 20px; font-weight: 600; color: var(--text-main);'>{$dados['protocolo']}</p>
                        </div>
                        <div style='text-align: right;'>
                            <p style='margin: 0; font-size: 14px; color: var(--text-light); text-transform: uppercase;'>Status Atual:</p>
                            <p class='status' style='margin: 0; color: $statusCor;'>{$dados['status']}</p>
                        </div>
                    </div>

                    <div style='background: var(--white); padding: 15px; border-radius: var(--radius-sm); border: 1px solid var(--border-color);'>
                        <p style='margin: 0 0 5px 0; font-size: 14px; color: var(--text-light); font-weight: 600;'>Descrição registrada:</p>
                        <p style='margin: 0; font-size: 15px; line-height: 1.5;'>" . nl2br(htmlspecialchars($dados['descricao'])) . "</p>
                    </div>
                </div>
            </div>
            ";

        } else {
            // Caso não encontre nenhum registro com o protocolo informado
            echo "
            <div style='margin-top: 30px; background: #FFEBEE; color: #C62828; padding: 20px; border-radius: var(--radius); text-align: center;'>
                <h3 style='margin-bottom: 5px;'>Protocolo não encontrado</h3>
                <p style='margin: 0;'>Verifique se você digitou o número corretamente e tente de novo.</p>
            </div>";
        }
    }
    ?>

</div>

<?php include 'includes/footer.php'; ?>