<?php
// ==========================================
// PÁGINA INICIAL (HOME)
// ==========================================
include 'includes/header.php';
?>

<!-- Nav -->
<div class="nav-bar">
    <a href="index.php" class="nav-logo">🌿 EcoAlert</a>
    <div class="nav-links">
        <a href="#como-funciona" class="nav-link">Como funciona</a>
        <a href="status.php" class="nav-link">Consultar protocolo</a>
        <a href="mapa.php" class="nav-link">Mapa de ocorrências</a>
    </div>
    <a href="denuncia.php" class="nav-cta">FAZER DENÚNCIA</a>
</div>

<!-- Hero -->
<div class="hero">
    <div class="hero-eyebrow">🌍 Plataforma cidadã ambiental</div>
    <div class="hero-h1">Registre. Acompanhe.<br>Proteja o meio ambiente.</div>
    <div class="hero-sub">Denuncie focos de incêndio, desmatamento e descarte irregular de forma simples, rápida e segura. Seu relato faz diferença.</div>
    <div>
        <a href="denuncia.php" class="btn-primary">🚨 Fazer uma Denúncia</a>
        <a href="mapa.php" class="btn-ghost">Ver ocorrências no mapa</a>
    </div>
    <div class="hero-badge">🔒 Denúncia anônima disponível &nbsp;·&nbsp; Protocolo gerado automaticamente</div>
</div>

<!-- Stats & Features -->
<div class="section-pad">
    <div class="stat-row" style="margin:0 0 40px;">
        <div class="stat"><div class="stat-num">1.240</div><div class="stat-desc">Denúncias registradas</div></div>
        <div class="stat"><div class="stat-num">87%</div><div class="stat-desc">Taxa de resolução</div></div>
        <div class="stat"><div class="stat-num">48h</div><div class="stat-desc">Tempo médio de resposta</div></div>
        <div class="stat"><div class="stat-num">12</div><div class="stat-desc">Municípios atendidos</div></div>
    </div>
    
    <div id="como-funciona" style="padding-top: 20px;">
        <div class="section-title">Como o EcoAlert funciona</div>
        <div class="section-sub">Três etapas simples para registrar sua ocorrência ambiental.</div>
        <div class="cards-row">
            <div class="feature-card">
                <div class="fc-icon" style="background:#E8F5E9;">📝</div>
                <div class="fc-title">1. Registre a ocorrência</div>
                <div class="fc-desc">Descreva o problema, informe o local (via GPS ou mapa interativo) e adicione fotos como evidência.</div>
            </div>
            <div class="feature-card">
                <div class="fc-icon" style="background:#FFF3E0;">📋</div>
                <div class="fc-title">2. Receba seu protocolo</div>
                <div class="fc-desc">Um número de protocolo único é gerado imediatamente para você acompanhar a resolução.</div>
            </div>
            <div class="feature-card">
                <div class="fc-icon" style="background:#E3F2FD;">✅</div>
                <div class="fc-title">3. Acompanhe a solução</div>
                <div class="fc-desc">Nossa equipe de fiscalização analisa o caso e atualiza o status em tempo real até a resolução.</div>
            </div>
        </div>
    </div>
</div>

<!-- CTA Banner -->
<div style="background:#1B5E20; padding:32px 40px; display:flex; align-items:center; justify-content:space-between; margin-bottom:0;">
    <div>
        <div style="font-family:'DM Serif Display',serif; font-size:24px; color:#fff; margin-bottom:6px;">Viu algo irregular? Denuncie agora.</div>
        <div style="font-size:14px; color:#A5D6A7;">Sua identidade é protegida e leva menos de 3 minutos.</div>
    </div>
    <a href="sobre.php" style="background:#fff; color:#1B5E20; padding:14px 28px; border-radius:8px; font-size:14px; font-weight:700; text-decoration:none; display:inline-block;">Saiba mais sobre o projeto →</a>
</div>

<?php include 'includes/footer.php'; ?>