<?php
// ==========================================
// PÁGINA INICIAL (HOME)
// ==========================================
// Esta é a porta de entrada principal do sistema.
// Apresenta a iniciativa e oferece links diretos 
// para as funcionalidades principais.

// Inclui o cabeçalho padrão (HTML head, navegação superior, etc.)
include 'includes/header.php';
?>

<div class="container" style="text-align: center; margin-top: 60px; padding: 50px 30px;">
    
    <!-- Ícone temático -->
    <div style="font-size: 64px; margin-bottom: 20px;">🌍</div>
    
    <h1>Denúncia de Queimadas e Descarte</h1>

    <p style="font-size: 18px; color: var(--text-main); margin-bottom: 30px;">
        Proteja o meio ambiente da sua cidade. <br>
        Denuncie focos de incêndio e descarte irregular de lixo <strong>de forma 100% anônima e segura</strong>.
    </p>

    <!-- Botões de Ação Principais -->
    <div style="display: flex; flex-direction: column; gap: 15px; max-width: 400px; margin: 0 auto;">
        
        <a href="denuncia.php" class="botao">
            🚨 Fazer Nova Denúncia
        </a>
        
        <a href="status.php" class="botao" style="background: var(--secondary-color); color: var(--text-main); border: 1px solid var(--border-color); box-shadow: none;">
            🔍 Consultar Denúncia Existente
        </a>

        <a href="mapa.php" class="botao" style="background: var(--secondary-color); color: var(--text-main); border: 1px solid var(--border-color); box-shadow: none;">
            🗺️ Ver Mapa de Ocorrências
        </a>

    </div>

</div>

<?php
// Inclui o rodapé padrão (Fechamento de tags HTML, copyright)
include 'includes/footer.php';
?>