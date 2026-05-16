<?php
// ==========================================
// PROCESSADOR: SALVAR DENÚNCIA
// ==========================================
include '../includes/db.php';

// Recebe os dados do formulário via método POST
$categoria = $_POST['categoria'] ?? 'Outros';
$descricao = $_POST['descricao'] ?? '';
$latitude = $_POST['latitude'] ?? '';
$longitude = $_POST['longitude'] ?? '';
$endereco = $_POST['endereco'] ?? '';

$is_anonimo = isset($_POST['is_anonimo']) ? (int)$_POST['is_anonimo'] : 1;
$nome = $_POST['nome'] ?? '';
$sobrenome = $_POST['sobrenome'] ?? '';
$telefone = $_POST['telefone'] ?? '';
$email = $_POST['email'] ?? '';

// Proteção básica contra injeção de SQL
$categoria = $conn->real_escape_string($categoria);
$descricao = $conn->real_escape_string($descricao);
$latitude = $conn->real_escape_string($latitude);
$longitude = $conn->real_escape_string($longitude);
$endereco = $conn->real_escape_string($endereco);
$nome = $conn->real_escape_string($nome);
$sobrenome = $conn->real_escape_string($sobrenome);
$telefone = $conn->real_escape_string($telefone);
$email = $conn->real_escape_string($email);

// Geração de um protocolo único para acompanhamento
$protocolo = "DEN-" . date("Y") . "-" . rand(1000, 9999);

$imagemNome = "";

// Tratamento do upload de imagem
if(isset($_FILES['imagem']) && $_FILES['imagem']['error'] == 0){
    $pasta = "../uploads/";
    $imagemNome = time() . "_" . basename($_FILES['imagem']['name']);
    move_uploaded_file($_FILES['imagem']['tmp_name'], $pasta . $imagemNome);
}

// Prepara a query SQL de inserção
$sql = "INSERT INTO denuncias (
            protocolo,
            categoria,
            descricao,
            imagem,
            latitude,
            longitude,
            endereco,
            status,
            is_anonimo,
            nome,
            sobrenome,
            telefone,
            email
        )
        VALUES (
            '$protocolo',
            '$categoria',
            '$descricao',
            '$imagemNome',
            '$latitude',
            '$longitude',
            '$endereco',
            'Recebida',
            $is_anonimo,
            '$nome',
            '$sobrenome',
            '$telefone',
            '$email'
        )";

if($conn->query($sql) === TRUE){
    // Redireciona para a página de sucesso (Padrão PRG)
    header("Location: ../sucesso.php?protocolo=$protocolo");
    exit;
} else {
    $erro = urlencode($conn->error);
    header("Location: ../sucesso.php?erro=$erro");
    exit;
}

$conn->close();
?>
