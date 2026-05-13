<?php
// ==========================================
// COMPONENTE: CABEÇALHO (HEADER)
// ==========================================
// Este arquivo contém a estrutura base do topo
// de todas as páginas públicas do sistema.
// Facilita a manutenção e garante consistência.
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EcoAlert - Sistema de Denúncias</title>
    
    <!-- Google Fonts: Inter para um design mais limpo e legível -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Folha de estilo principal (CSS) - Ajustado para pasta assets -->
    <link rel="stylesheet" href="assets/css/style.css">
    
</head>
<body>

<!-- Navegação principal / Topo -->
<header class="main-header">
    <div class="header-container">
        <!-- Logotipo ou Nome do Sistema -->
        <a href="index.php" class="logo">
            <span class="icon">🌿</span> EcoAlert
        </a>
        
        <!-- Menu de navegação -->
        <nav class="main-nav">
            <a href="denuncia.php">Nova Denúncia</a>
            <a href="status.php">Consultar</a>
            <a href="mapa.php">Ver Mapa</a>
            <a href="admin/login.php" class="btn-admin">Acesso Restrito</a>
        </nav>
    </div>
</header>

<!-- Área de conteúdo principal onde cada página será injetada -->
<main class="main-content">
