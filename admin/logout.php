<?php
// ==========================================
// SCRIPT DE ENCERRAMENTO DE SESSÃO (LOGOUT)
// ==========================================
// Este arquivo é chamado quando o administrador
// clica em "Sair" no painel.

// Inicia ou retoma a sessão ativa
session_start();

// Remove todas as variáveis de sessão (desloga)
session_unset();

// Destrói completamente a sessão no servidor
session_destroy();

// Redireciona o usuário de volta para a tela de login
header("Location: login.php");
// Encerra o processamento do arquivo
exit;
?>
