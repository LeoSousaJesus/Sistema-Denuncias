<?php
// ==========================================
// PÁGINA DE NOVA DENÚNCIA
// ==========================================
// Exibe o formulário para o usuário enviar uma nova
// denúncia. Captura a localização via JavaScript.

include 'includes/header.php';
?>

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
        <input type="hidden" name="latitude" id="latitude">
        <input type="hidden" name="longitude" id="longitude">

        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 25px; padding: 15px; background: #E8F5E9; border-radius: var(--radius-sm); color: #2E7D32;">
            <span style="font-size: 20px;">📍</span>
            <p id="localizacao" style="margin: 0; text-align: left; font-size: 14px; font-weight: 500;">
                Obtendo sua localização para enviar à fiscalização...
            </p>
        </div>

        <button type="submit" class="botao">
            Enviar Denúncia Anonimamente
        </button>

    </form>

</div>

<!-- Script JavaScript para capturar a localização via GPS do navegador -->
<script>
// Verifica se o navegador suporta geolocalização
if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(
        function(position) {
            // Sucesso: preenche os inputs hidden com as coordenadas
            document.getElementById("latitude").value = position.coords.latitude;
            document.getElementById("longitude").value = position.coords.longitude;
            document.getElementById("localizacao").innerHTML = "Localização capturada com sucesso.";
        },
        function() {
            // Falha: Usuário negou permissão ou ocorreu um erro
            document.getElementById("localizacao").innerHTML = "Não foi possível obter sua localização. A denúncia será enviada sem mapa.";
            document.getElementById("localizacao").parentElement.style.background = "#FFEBEE";
            document.getElementById("localizacao").parentElement.style.color = "#C62828";
        }
    );
} else {
    document.getElementById("localizacao").innerHTML = "Geolocalização não suportada pelo seu navegador.";
}
</script>

<?php include 'includes/footer.php'; ?>
