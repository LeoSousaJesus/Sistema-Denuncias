<?php
// ==========================================
// PÁGINA DO MAPA INTERATIVO
// ==========================================
include 'includes/db.php';
include 'includes/header.php';

$sql = "SELECT * FROM denuncias";
$resultado = $conn->query($sql);
?>

<!-- Inclusão do CSS e JS do Leaflet -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

<div class="topbar">
    <a href="index.php" class="logo">🌿 EcoAlert</a>
    <div class="breadcrumb">Início <span>›</span> Mapa Público</div>
</div>

<div style="max-width: 1000px; margin: 40px auto; padding: 0 20px;">
    
    <div style="display:flex; align-items:center; gap:12px; margin-bottom:8px;">
        <span style="font-size:28px;">🗺️</span>
        <div style="font-family:'DM Serif Display',serif; font-size:24px; color:#1C1C1C;">Mapa de Ocorrências</div>
    </div>
    <p style="font-size:14px; color:#757575; line-height:1.6; margin-bottom:24px;">Acompanhe visualmente onde estão concentradas as denúncias de queimadas, desmatamento e descarte irregular na região.</p>

    <!-- Contêiner do Mapa -->
    <div id="map" style="height: 500px; border-radius: 12px; border: 0.5px solid #E0E0E0; z-index: 1; box-shadow: 0 4px 12px rgba(0,0,0,0.05);"></div>

</div>

<script>
// Inicializa o mapa focado nas coordenadas padrão (Ex: Brasília/DF)
var map = L.map('map').setView([-15.646, -47.789], 12);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; OpenStreetMap contributors'
}).addTo(map);

<?php
if ($resultado && $resultado->num_rows > 0) {
    while($dados = $resultado->fetch_assoc()){
        if(!empty($dados['latitude']) && !empty($dados['longitude'])){
            
            $statusSafe = htmlspecialchars($dados['status'], ENT_QUOTES);
            $protocoloSafe = htmlspecialchars($dados['protocolo'], ENT_QUOTES);
            $enderecoSafe = !empty($dados['endereco']) ? htmlspecialchars($dados['endereco'], ENT_QUOTES) : 'Endereço não registrado';
            
            $emoji = "📝";
            if($dados['categoria'] == 'Foco de Incêndio') $emoji = "🔥";
            if($dados['categoria'] == 'Desmatamento') $emoji = "🌳";
            if($dados['categoria'] == 'Resíduos') $emoji = "🗑️";
            
            $statusColor = "#757575";
            if(strpos(strtolower($statusSafe), 'resolvida') !== false) $statusColor = "#2E7D32";
            if(strpos(strtolower($statusSafe), 'análise') !== false) $statusColor = "#F57F17";

            echo "
            L.marker([{$dados['latitude']}, {$dados['longitude']}])
             .addTo(map)
             .bindPopup(`
                <div style='text-align:left; font-family:\"DM Sans\",sans-serif; min-width:200px;'>
                    <div style='font-size:12px; font-weight:600; color:#1B5E20; margin-bottom:4px;'>{$protocoloSafe}</div>
                    <div style='font-size:11px; font-weight:600; margin-bottom:4px;'>{$emoji} {$dados['categoria']}</div>
                    <div style='color:#757575; font-size:10px; margin-bottom:8px; line-height:1.4;'>{$enderecoSafe}</div>
                    <div style='background:#F5F5F5; padding:4px 8px; border-radius:4px; font-size:10px; font-weight:600; color:{$statusColor}; display:inline-block;'>Status: {$statusSafe}</div>
                </div>
             `);
            ";
        }
    }
}
?>

var markerTemp;

map.on('click', function(e) {
    var lat = e.latlng.lat;
    var lng = e.latlng.lng;
    
    if (markerTemp) {
        map.removeLayer(markerTemp);
    }
    
    markerTemp = L.marker([lat, lng]).addTo(map);
    
    markerTemp.bindPopup(`
        <div style='text-align:center; padding: 10px; font-family:\"DM Sans\",sans-serif;'>
            <div style='font-size:12px; font-weight: 600; margin-bottom:12px; color:#1C1C1C;'>Localização Selecionada</div>
            <a href='denuncia.php?lat=${lat}&lng=${lng}' style='background:#2E7D32; color:#fff; padding: 8px 16px; border-radius:8px; font-size: 11px; font-weight:600; text-decoration:none; display:inline-block;'>
                Registrar Denúncia Aqui
            </a>
        </div>
    `).openPopup();
});
</script>

<?php include 'includes/footer.php'; ?>