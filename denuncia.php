<?php
// ==========================================
// PÁGINA DE NOVA DENÚNCIA
// ==========================================
// Exibe o formulário para o usuário enviar uma nova
// denúncia. Captura a localização via JavaScript.

include 'includes/header.php';
?>
<!-- Inclusão do CSS e JS do Leaflet -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

<div class="container" style="margin-top: 40px;">

    <h1 style="text-align: left; display: flex; align-items: center; gap: 10px;">
        <span>📝</span> Registrar Denúncia
    </h1>
    <p style="text-align: left;">Forneça o máximo de detalhes possível para ajudar nossa equipe de fiscalização. O envio de fotos é opcional, mas recomendado.</p>

    <hr style="margin: 25px 0;">

    <!-- Formulário apontando para o arquivo de processamento em process/salvar_denuncia.php -->
    <form action="process/salvar_denuncia.php" method="POST" enctype="multipart/form-data">

        <label for="descricao" style="display: block; font-weight: 500; margin-bottom: 8px;">Descrição do problema *</label>
        <textarea 
            name="descricao"
            id="descricao"
            placeholder="Ex: Foco de queimada em terreno baldio na rua X..."
            required
        ></textarea>

        <label for="imagem" style="display: block; font-weight: 500; margin-bottom: 8px;">Anexar Foto (Opcional)</label>
        <div style="border: 1px dashed var(--border-color); padding: 15px; border-radius: var(--radius-sm); margin-bottom: 20px; background: var(--bg-color);">
            <input type="file" name="imagem" id="imagem" accept="image/*" style="margin: 0; width: 100%;">
        </div>

        <!-- Campos ocultos para armazenar a localização do usuário -->
        <?php
        // Verifica se a latitude e longitude vieram via URL (clique no mapa.php)
        $lat = isset($_GET['lat']) ? htmlspecialchars($_GET['lat']) : '';
        $lng = isset($_GET['lng']) ? htmlspecialchars($_GET['lng']) : '';
        ?>
        <input type="hidden" name="latitude" id="latitude" value="<?= $lat ?>">
        <input type="hidden" name="longitude" id="longitude" value="<?= $lng ?>">
        <input type="hidden" name="endereco" id="endereco" value="">

        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px;">
            <label style="font-weight: 500; margin: 0;">Localização *</label>
            <button type="button" id="btn-show-map" style="background: var(--secondary-color); border: 1px solid var(--border-color); color: var(--text-main); padding: 6px 12px; border-radius: var(--radius-sm); cursor: pointer; font-size: 13px; font-weight: 500; transition: 0.3s;">
                🗺️ Escolher local no mapa
            </button>
        </div>

        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 15px; padding: 15px; background: #E8F5E9; border-radius: var(--radius-sm); color: #2E7D32;">
            <span style="font-size: 20px;">📍</span>
            <p id="localizacao" style="margin: 0; text-align: left; font-size: 14px; font-weight: 500;">
                <?php if($lat && $lng): ?>
                    Localização selecionada pelo mapa! Pronta para envio.
                <?php else: ?>
                    Obtendo sua localização automática para enviar à fiscalização...
                <?php endif; ?>
            </p>
        </div>
        
        <div id="map-container" style="display: none; margin-bottom: 25px;">
            <div id="map-picker" style="height: 300px; border-radius: var(--radius-sm); border: 1px solid var(--border-color); z-index: 1;"></div>
            <p style="font-size: 12px; color: var(--text-light); text-align: center; margin-top: 8px;">Clique em qualquer ponto do mapa para definir o local exato da ocorrência.</p>
        </div>

        <button type="submit" class="botao">
            Enviar Denúncia Anonimamente
        </button>

    </form>

</div>

<!-- Script JavaScript para capturar a localização via GPS do navegador e Endereço -->
<script>
// Função para buscar o nome da rua baseado na latitude e longitude
function buscarEndereco(lat, lng) {
    document.getElementById("localizacao").innerHTML = "Buscando endereço detalhado...";
    // API Nominatim do OpenStreetMap para Reverse Geocoding
    fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&zoom=18&addressdetails=1`)
        .then(response => response.json())
        .then(data => {
            if(data && data.display_name) {
                document.getElementById("endereco").value = data.display_name;
                document.getElementById("localizacao").innerHTML = data.display_name;
            } else {
                document.getElementById("localizacao").innerHTML = "Localização capturada (Endereço aproximado).";
            }
        })
        .catch(err => {
            document.getElementById("localizacao").innerHTML = "Localização capturada pelas coordenadas.";
        });
}

// Verifica se a latitude e longitude já vieram do clique no mapa
var latValue = document.getElementById("latitude").value;
var lngValue = document.getElementById("longitude").value;

if (latValue !== "" && lngValue !== "") {
    // Se veio do mapa, já busca o endereço correspondente
    buscarEndereco(latValue, lngValue);
} else {
    // Senão, tenta pegar a localização via GPS
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            function(position) {
                // Sucesso: preenche os inputs hidden com as coordenadas
                var lat = position.coords.latitude;
                var lng = position.coords.longitude;
                document.getElementById("latitude").value = lat;
                document.getElementById("longitude").value = lng;
                
                // Busca o endereço em texto
                buscarEndereco(lat, lng);
            },
            function() {
                // Falha: Usuário negou permissão ou ocorreu um erro
                document.getElementById("localizacao").innerHTML = "Não foi possível obter sua localização. Você pode tentar escolher no mapa.";
                document.getElementById("localizacao").parentElement.style.background = "#FFEBEE";
                document.getElementById("localizacao").parentElement.style.color = "#C62828";
            }
        );
    } else {
        document.getElementById("localizacao").innerHTML = "Geolocalização não suportada pelo seu navegador.";
    }
}

// Lógica de exibição e interação com o mapa embarcado
var mapPicker;
var markerPicker;

document.getElementById("btn-show-map").addEventListener("click", function() {
    var container = document.getElementById("map-container");
    if (container.style.display === "none") {
        container.style.display = "block";
        
        // Inicializa o mapa apenas na primeira vez
        if (!mapPicker) {
            // Usa as coordenadas já pegas (GPS) ou um valor padrão (ex: Brasília)
            var lat = document.getElementById("latitude").value || -15.646;
            var lng = document.getElementById("longitude").value || -47.789;
            
            mapPicker = L.map('map-picker').setView([lat, lng], 14);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap'
            }).addTo(mapPicker);
            
            if (document.getElementById("latitude").value !== "") {
                markerPicker = L.marker([lat, lng]).addTo(mapPicker);
            }
            
            // Evento de clique para escolher novo local
            mapPicker.on('click', function(e) {
                var newLat = e.latlng.lat;
                var newLng = e.latlng.lng;
                
                document.getElementById("latitude").value = newLat;
                document.getElementById("longitude").value = newLng;
                
                if (markerPicker) {
                    mapPicker.removeLayer(markerPicker);
                }
                markerPicker = L.marker([newLat, newLng]).addTo(mapPicker);
                
                buscarEndereco(newLat, newLng);
            });
        } else {
            // Se já inicializado, apenas centraliza no marcador atual se houver
            var currentLat = document.getElementById("latitude").value;
            var currentLng = document.getElementById("longitude").value;
            if(currentLat && currentLng) {
                mapPicker.setView([currentLat, currentLng], 15);
            }
        }
        
        // Força atualização de tamanho do mapa porque ele estava escondido (display:none)
        setTimeout(function() {
            mapPicker.invalidateSize();
        }, 100);
        
        this.innerHTML = "Ocultar mapa";
    } else {
        container.style.display = "none";
        this.innerHTML = "🗺️ Escolher local no mapa";
    }
});
</script>

<?php include 'includes/footer.php'; ?>
