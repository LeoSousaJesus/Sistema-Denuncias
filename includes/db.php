<?php
// ==========================================
// ARQUIVO DE CONEXÃO COM O BANCO DE DADOS
// ==========================================
// Este arquivo é responsável por estabelecer
// a comunicação entre a aplicação PHP e o 
// banco de dados MySQL.

// Configurações do servidor e banco de dados
$host = "localhost";       // Endereço do servidor MySQL (geralmente localhost)
$usuario = "root";         // Usuário de acesso ao banco (padrão do XAMPP/WAMP)
$senha = "";               // Senha do banco (em branco por padrão no XAMPP)
$banco = "sistema_denuncias"; // Nome da base de dados utilizada

// Instancia um novo objeto MySQLi para a conexão
$conn = new mysqli($host, $usuario, $senha, $banco);

// Verifica se ocorreu algum erro durante a tentativa de conexão
if ($conn->connect_error) {
    // Interrompe a execução e exibe a mensagem de erro
    die("Erro na conexão com o banco de dados: " . $conn->connect_error);
}

// Opcional: Definir o charset para UTF-8 para evitar problemas de acentuação
$conn->set_charset("utf8mb4");

?>
