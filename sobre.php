<?php
// ==========================================
// PÁGINA SOBRE O PROJETO
// ==========================================
include 'includes/header.php';
?>

<!-- Nav -->
<div class="nav-bar">
    <a href="index.php" class="nav-logo">🌿 EcoAlert</a>
    <div class="nav-links">
        <a href="index.php#como-funciona" class="nav-link">Como funciona</a>
        <a href="status.php" class="nav-link">Consultar protocolo</a>
        <a href="mapa.php" class="nav-link">Mapa de ocorrências</a>
    </div>
    <a href="denuncia.php" class="nav-cta">FAZER DENÚNCIA</a>
</div>

<div style="max-width: 800px; margin: 60px auto 100px auto; padding: 40px; background: #fff; border-radius: 12px; border: 0.5px solid #E0E0E0; box-shadow: 0 4px 12px rgba(0,0,0,0.03);">
    
    <div style="text-align: center; margin-bottom: 40px;">
        <span style="font-size: 48px;">🌍</span>
        <div style="font-family:'DM Serif Display',serif; font-size:36px; color:#1B5E20; margin-top: 10px;">Sobre o EcoAlert</div>
        <div style="font-size:16px; color:#757575; margin-top: 8px;">Tecnologia cidadã a favor do meio ambiente.</div>
    </div>

    <div style="margin-bottom: 30px;">
        <div style="font-family:'DM Serif Display',serif; font-size:24px; color:#1C1C1C; margin-bottom: 12px; border-bottom: 2px solid #E8F5E9; padding-bottom: 8px;">Nossa Missão</div>
        <p style="font-size:15px; color:#4A4A4A; line-height:1.7;">
            O <strong>EcoAlert</strong> nasceu com a missão de empoderar o cidadão na fiscalização ambiental de sua cidade. Sabemos que o combate a focos de incêndio, desmatamento ilegal e descarte irregular de resíduos é um desafio constante para o poder público. Através desta plataforma, construímos uma ponte ágil, segura e 100% anônima entre quem vê o problema e quem tem o poder de resolvê-lo.
        </p>
    </div>

    <div style="margin-bottom: 30px;">
        <div style="font-family:'DM Serif Display',serif; font-size:24px; color:#1C1C1C; margin-bottom: 12px; border-bottom: 2px solid #E8F5E9; padding-bottom: 8px;">Tecnologia de Ponta</div>
        <p style="font-size:15px; color:#4A4A4A; line-height:1.7;">
            Nós utilizamos a poderosa biblioteca de mapas <em>Leaflet.js</em> aliada à precisão dos mapas do <em>OpenStreetMap</em>. Ao reportar uma ocorrência, o sistema é capaz de capturar automaticamente sua localização via GPS ou permitir que você selecione o ponto exato através de um mapa interativo. Toda a conversão de coordenadas para o nome real da rua é feita em tempo real pela <em>API Nominatim</em>.
        </p>
    </div>

    <div style="margin-bottom: 40px;">
        <div style="font-family:'DM Serif Display',serif; font-size:24px; color:#1C1C1C; margin-bottom: 12px; border-bottom: 2px solid #E8F5E9; padding-bottom: 8px;">Privacidade em Primeiro Lugar</div>
        <p style="font-size:15px; color:#4A4A4A; line-height:1.7;">
            Entendemos o receio de retaliação ao realizar certas denúncias. Por isso, a plataforma foi estruturada sobre o <strong>Princípio do Anonimato</strong>. Não exigimos nenhum dado pessoal, nome ou e-mail. Quando você realiza o envio, a denúncia é catalogada sob um código único (ex: <em>DEN-2026-ABCD</em>) que serve para que você, e apenas você, consiga acompanhar o desfecho do processo.
        </p>
    </div>

    <div style="background:#F1F8E9; border:0.5px solid #A5D6A7; border-radius:12px; padding:24px; text-align: center;">
        <div style="font-size:18px; font-weight:700; color:#1B5E20; margin-bottom: 8px;">Junte-se à causa ambiental!</div>
        <div style="font-size:14px; color:#2E7D32; margin-bottom: 20px;">Sua atitude transforma a realidade ao seu redor.</div>
        <a href="denuncia.php" style="background:#2E7D32; color:#fff; padding:12px 24px; border-radius:8px; font-size:14px; font-weight:700; text-decoration:none; display:inline-block;">Fazer uma Denúncia Agora</a>
    </div>

</div>

<?php include 'includes/footer.php'; ?>
