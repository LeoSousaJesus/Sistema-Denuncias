<?php
include 'includes/header.php';

// Valores vindos do mapa.php (se houver)
$lat = isset($_GET['lat']) ? htmlspecialchars($_GET['lat']) : '';
$lng = isset($_GET['lng']) ? htmlspecialchars($_GET['lng']) : '';
?>
<!-- Inclusão do CSS e JS do Leaflet -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

<div class="topbar">
    <a href="index.php" class="logo">🌿 EcoAlert</a>
    <div class="breadcrumb">Início <span>›</span> Nova Denúncia</div>
</div>

<!-- Stepper -->
<div class="stepper">
    <div class="step-item step-active">
        <div class="step-dot">1</div>
        <div class="step-name">Localização & Detalhes</div>
    </div>
    <div class="step-line"></div>
    <div class="step-item step-inactive">
        <div class="step-dot">2</div>
        <div class="step-name">Revisão & Envio</div>
    </div>
</div>

<!-- Form Body -->
<form action="process/salvar_denuncia.php" method="POST" enctype="multipart/form-data" class="form-body">
    <div class="form-main">
        <div class="form-title">Localização e Detalhes</div>
        <div class="form-sub">Nos ajude a identificar com precisão a ocorrência.</div>

        <!-- Categoria -->
        <div class="field-group">
            <label class="field-label">Tipo de Ocorrência <span class="field-req">*</span></label>
            <div class="field-row" style="grid-template-columns: repeat(4, 1fr);">
                <label class="radio-item selected" id="lbl-incendio">
                    <input type="radio" name="categoria" value="Foco de Incêndio" checked onchange="updateRadio(this)">
                    <div class="radio-dot"></div>
                    <div class="radio-text">🔥 Incêndio</div>
                </label>
                <label class="radio-item" id="lbl-desmatamento">
                    <input type="radio" name="categoria" value="Desmatamento" onchange="updateRadio(this)">
                    <div class="radio-dot"></div>
                    <div class="radio-text">🌳 Desmatamento</div>
                </label>
                <label class="radio-item" id="lbl-residuos">
                    <input type="radio" name="categoria" value="Resíduos" onchange="updateRadio(this)">
                    <div class="radio-dot"></div>
                    <div class="radio-text">🗑️ Resíduos</div>
                </label>
                <label class="radio-item" id="lbl-outros">
                    <input type="radio" name="categoria" value="Outros" onchange="updateRadio(this)">
                    <div class="radio-dot"></div>
                    <div class="radio-text">❓ Outros</div>
                </label>
            </div>
        </div>

        <!-- Localização -->
        <div class="field-group">
            <label class="field-label">Localização da Ocorrência <span class="field-req">*</span></label>
            
            <input type="hidden" name="latitude" id="latitude" value="<?= $lat ?>">
            <input type="hidden" name="longitude" id="longitude" value="<?= $lng ?>">
            <input type="hidden" name="endereco" id="endereco" value="">

            <div class="map-preview" id="btn-show-map">
                <span style="font-size:20px;">🗺️</span>
                <div style="font-size:12px; font-weight:600; color:#1B5E20;">Clique para abrir o mapa interativo</div>
                <div style="font-size:10px; color:#757575;">Ou permita acesso ao GPS para localização automática</div>
            </div>

            <!-- Div do mapa real (escondida inicialmente) -->
            <div id="map-container" style="display: none; margin-top: 10px;">
                <div id="map-picker" style="height: 250px; border-radius: 8px; border: 1px solid #A5D6A7; z-index: 1;"></div>
            </div>

            <div style="display:flex; gap:8px; margin-top:8px;">
                <input type="text" class="field-input" id="localizacao" readonly style="font-size:12px; color:#2E7D32; background:#F1F8E9; flex:1; border-color:#A5D6A7;" value="Obtendo sua localização...">
                <div id="btn-gps" style="background:#E8F5E9; border:0.5px solid #A5D6A7; border-radius:8px; padding:9px 12px; font-size:12px; color:#1B5E20; cursor:pointer; white-space:nowrap; display: flex; align-items: center;">📍 Usar GPS</div>
            </div>
        </div>

        <!-- Descrição + Upload -->
        <div class="field-row">
            <div class="field-group">
                <label class="field-label">Descrição da Ocorrência <span class="field-req">*</span></label>
                <textarea name="descricao" class="field-input field-textarea" placeholder="Descreva o que você observou com o máximo de detalhes possível…" required></textarea>
                <div class="field-hint">Mínimo 20 caracteres. Seja específico.</div>
            </div>
            <div class="field-group">
                <label class="field-label">Evidências (opcional)</label>
                <div class="upload-zone" id="upload-zone">
                    <input type="file" name="imagem" id="imagem" class="upload-input" accept="image/*">
                    <div class="upload-icon">📷</div>
                    <div class="upload-text" id="upload-text">Clique para anexar foto</div>
                    <div class="upload-hint">.jpg .png .webp · máx. 5MB</div>
                </div>
            </div>
        </div>

        <!-- Anonimato -->
        <div class="anon-toggle" id="anon-toggle">
            <div class="toggle-pill" id="toggle-pill"><div class="toggle-thumb" id="toggle-thumb"></div></div>
            <div>
                <div style="font-size:13px; font-weight:600; color:#F57F17;" id="anon-title">Denúncia Anônima ativada</div>
                <div style="font-size:11px; color:#F9A825; margin-top:2px;">Seus dados pessoais não serão vinculados a esta denúncia</div>
            </div>
            <input type="hidden" name="is_anonimo" id="is_anonimo" value="1">
        </div>

        <!-- Formulário de Dados Pessoais (Oculto por padrão) -->
        <div id="personal-data-form" style="display:none; background:#F5F5F5; border:0.5px solid #E0E0E0; border-radius:8px; padding:16px; margin-bottom:20px;">
            <div style="font-size:13px; font-weight:600; color:#1C1C1C; margin-bottom:12px;">Seus Dados (Confidenciais)</div>
            <div class="field-row" style="margin-bottom:12px;">
                <div>
                    <label class="field-label">Nome <span class="field-req">*</span></label>
                    <input type="text" name="nome" id="input_nome" class="field-input" placeholder="Seu nome">
                </div>
                <div>
                    <label class="field-label">Sobrenome <span class="field-req">*</span></label>
                    <input type="text" name="sobrenome" id="input_sobrenome" class="field-input" placeholder="Seu sobrenome">
                </div>
            </div>
            <div class="field-row" style="margin-bottom:12px;">
                <div>
                    <label class="field-label">Telefone <span class="field-req">*</span></label>
                    <input type="text" name="telefone" id="input_telefone" class="field-input" placeholder="(00) 00000-0000">
                </div>
                <div>
                    <label class="field-label">E-mail <span class="field-req">*</span></label>
                    <input type="email" name="email" id="input_email" class="field-input" placeholder="seu@email.com">
                </div>
            </div>
            <label style="display:flex; align-items:flex-start; gap:8px; font-size:11px; color:#757575; cursor:pointer;">
                <input type="checkbox" id="check_termos" style="margin-top:2px;">
                <span>Li e concordo com a política de privacidade. Meus dados poderão ser utilizados exclusivamente por fiscais caso precisem de mais informações sobre a denúncia.</span>
            </label>
        </div>

        <!-- Botões -->
        <div class="btn-row">
            <a href="index.php" class="btn-back">← Voltar</a>
            <button type="submit" class="btn-next">Enviar Denúncia →</button>
        </div>
    </div>

    <!-- Aside -->
    <div class="form-aside">
        <div class="aside-tip">
            <div class="tip-title">💡 Dica de localização</div>
            <div class="tip-text">Se não tiver GPS, use o mapa interativo: clique no local exato da ocorrência e o endereço será preenchido automaticamente.</div>
        </div>
        <div class="aside-tip" style="background:#FFF8E1; border-color:#F9CE6A;">
            <div class="tip-title" style="color:#B45309;">🔒 Sua privacidade</div>
            <div class="tip-text">A denúncia anônima garante que nenhum dado seu seja salvo. O protocolo é gerado sem vínculo pessoal.</div>
        </div>
        <div class="aside-tip" style="background:#E3F2FD; border-color:#90CAF9;">
            <div class="tip-title" style="color:#0D47A1;">📋 Protocolo gerado</div>
            <div class="tip-text">Após o envio, você receberá um número como:</div>
        </div>
        <div class="proto-preview">
            <div class="proto-label">Protocolo de exemplo</div>
            <div class="proto-num">DEN-2026-1847</div>
            <div style="font-size:11px; color:#9E9E9E; margin-top:4px;">Use este código para acompanhar o andamento</div>
        </div>
    </div>
</form>

<script>
// Lógica dos Radio Buttons customizados
function updateRadio(selectedInput) {
    document.querySelectorAll('.radio-item').forEach(el => el.classList.remove('selected'));
    selectedInput.closest('.radio-item').classList.add('selected');
}

// Lógica de visualização do Upload
document.getElementById('imagem').addEventListener('change', function(e) {
    if(this.files && this.files[0]) {
        document.getElementById('upload-text').textContent = this.files[0].name;
    } else {
        document.getElementById('upload-text').textContent = 'Clique para anexar foto';
    }
});

// Toggle de Anonimato
let isAnon = true;
document.getElementById('toggle-pill').addEventListener('click', function() {
    isAnon = !isAnon;
    const thumb = document.getElementById('toggle-thumb');
    const toggle = document.getElementById('anon-toggle');
    const title = document.getElementById('anon-title');
    
    const personalForm = document.getElementById('personal-data-form');
    const isAnonInput = document.getElementById('is_anonimo');
    const inputs = ['input_nome', 'input_sobrenome', 'input_telefone', 'input_email'];
    
    if(isAnon) {
        thumb.style.left = 'auto';
        thumb.style.right = '3px';
        toggle.style.background = '#FFF8E1';
        toggle.style.borderColor = '#F9A825';
        title.textContent = 'Denúncia Anônima ativada';
        title.style.color = '#F57F17';
        
        personalForm.style.display = 'none';
        isAnonInput.value = '1';
        inputs.forEach(id => document.getElementById(id).removeAttribute('required'));
        document.getElementById('check_termos').removeAttribute('required');
    } else {
        thumb.style.right = 'auto';
        thumb.style.left = '3px';
        toggle.style.background = '#F5F5F5';
        toggle.style.borderColor = '#E0E0E0';
        title.textContent = 'Denúncia Anônima desativada';
        title.style.color = '#757575';
        
        personalForm.style.display = 'block';
        isAnonInput.value = '0';
        inputs.forEach(id => document.getElementById(id).setAttribute('required', 'required'));
        document.getElementById('check_termos').setAttribute('required', 'required');
    }
});

// Lógica de Mapa e Geocodificação
function buscarEndereco(lat, lng) {
    document.getElementById("localizacao").value = "Buscando endereço...";
    fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&zoom=18&addressdetails=1`)
        .then(response => response.json())
        .then(data => {
            if(data && data.display_name) {
                document.getElementById("endereco").value = data.display_name;
                document.getElementById("localizacao").value = "📍 " + data.display_name;
            } else {
                document.getElementById("localizacao").value = "📍 Localização capturada (Endereço aproximado).";
            }
        })
        .catch(err => {
            document.getElementById("localizacao").value = "📍 Localização capturada pelas coordenadas.";
        });
}

function getLocationViaGPS() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            function(position) {
                var lat = position.coords.latitude;
                var lng = position.coords.longitude;
                document.getElementById("latitude").value = lat;
                document.getElementById("longitude").value = lng;
                buscarEndereco(lat, lng);
                
                // Atualiza o mapa se estiver aberto
                if(mapPicker && markerPicker) {
                    var newLatLng = new L.LatLng(lat, lng);
                    markerPicker.setLatLng(newLatLng);
                    mapPicker.setView(newLatLng, 15);
                }
            },
            function() {
                document.getElementById("localizacao").value = "Erro ao obter GPS. Tente pelo mapa.";
            }
        );
    }
}

// Verifica se a latitude e longitude já vieram do clique no mapa.php
var latValue = document.getElementById("latitude").value;
var lngValue = document.getElementById("longitude").value;

if (latValue !== "" && lngValue !== "") {
    buscarEndereco(latValue, lngValue);
} else {
    getLocationViaGPS();
}

document.getElementById('btn-gps').addEventListener('click', getLocationViaGPS);

// Inicialização do Mapa
var mapPicker;
var markerPicker;

document.getElementById("btn-show-map").addEventListener("click", function() {
    var container = document.getElementById("map-container");
    if (container.style.display === "none") {
        container.style.display = "block";
        
        if (!mapPicker) {
            var lat = document.getElementById("latitude").value || -15.646;
            var lng = document.getElementById("longitude").value || -47.789;
            
            mapPicker = L.map('map-picker').setView([lat, lng], 14);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap'
            }).addTo(mapPicker);
            
            if (document.getElementById("latitude").value !== "") {
                markerPicker = L.marker([lat, lng]).addTo(mapPicker);
            }
            
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
            var currentLat = document.getElementById("latitude").value;
            var currentLng = document.getElementById("longitude").value;
            if(currentLat && currentLng) {
                mapPicker.setView([currentLat, currentLng], 15);
            }
        }
        
        setTimeout(function() { mapPicker.invalidateSize(); }, 100);
        this.querySelector('div').textContent = "Clique novamente para esconder o mapa";
    } else {
        container.style.display = "none";
        this.querySelector('div').textContent = "Clique para abrir o mapa interativo";
    }
});
</script>

<?php include 'includes/footer.php'; ?>
