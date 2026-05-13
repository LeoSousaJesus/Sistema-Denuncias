<?php
// ==========================================
// PÁGINA DO MAPA INTERATIVO
// ==========================================
// Exibe um mapa público utilizando a biblioteca Leaflet,
// marcando as localizações das denúncias que possuem
// latitude e longitude salvas.

include 'includes/db.php';
include 'includes/header.php';

// Busca todas as denúncias no banco
$sql = "SELECT * FROM denuncias";
$resultado = $conn->query($sql);
?>

<div class="container" style="max-width: 1000px; margin-top: 40px;">

    <h1 style="text-align: left; display: flex; align-items: center; gap: 10px;">
        <span>🗺️</span> Mapa de Ocorrências
    </h1>
    <p style="text-align: left;">Acompanhe visualmente onde estão concentradas as denúncias de queimadas e descarte irregular na região.</p>

    <!-- Contêiner do Mapa -->
    <div id="map" style="height: 500px; border-radius: var(--radius); margin-top: 20px; border: 1px solid var(--border-color); z-index: 1;"></div>

</div>

<!-- Inclusão do JavaScript do Leaflet (Biblioteca de Mapas Open Source) -->
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<script>
// Inicializa o mapa focado nas coordenadas padrão (Ex: Brasília/DF)
// Você pode ajustar as coordenadas centrais [-15.646, -47.789] conforme a cidade alvo
var map = L.map('map').setView([-15.646, -47.789], 12);

// Adiciona a camada de mapa base (Tiles) fornecida pelo OpenStreetMap
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
}).addTo(map);

<?php
// Loop PHP para gerar os marcadores (Pins) no mapa via JavaScript
if ($resultado && $resultado->num_rows > 0) {
    while($dados = $resultado->fetch_assoc()){
        
        // Só adiciona o marcador se latitude e longitude existirem
        if(!empty($dados['latitude']) && !empty($dados['longitude'])){
            
            // Tratamento de segurança para não quebrar o script JS com aspas soltas na descrição
            $statusSafe = htmlspecialchars($dados['status'], ENT_QUOTES);
            $protocoloSafe = htmlspecialchars($dados['protocolo'], ENT_QUOTES);
            
            // Imprime o código JS para criar o marcador
            echo "
            L.marker([{$dados['latitude']}, {$dados['longitude']}])
             .addTo(map)
             .bindPopup(`
                <div style='text-align:center;'>
                    <b style='color: #2E7D32;'>{$protocoloSafe}</b><br>
                    <span style='color: #666; font-size: 12px;'>Status: {$statusSafe}</span>
                </div>
             `);
            ";
        }
    }
}
?>
</script>

<?php include 'includes/footer.php'; ?>