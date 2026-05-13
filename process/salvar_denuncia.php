<?php
// ==========================================
// PROCESSADOR: SALVAR DENÚNCIA
// ==========================================
// Este script recebe os dados via POST do formulário
// (denuncia.php), processa uploads de imagem,
// gera o protocolo e insere os dados no banco.

// Inclui a conexão com o banco de dados
include '../includes/db.php';

// Recebe os dados do formulário via método POST
// Utilizamos o null coalescing operator (??) para evitar erros caso não seja enviado
$descricao = $_POST['descricao'] ?? '';
$latitude = $_POST['latitude'] ?? '';
$longitude = $_POST['longitude'] ?? '';
$endereco = $_POST['endereco'] ?? '';

// Proteção básica contra injeção de SQL (limpa as strings recebidas)
$descricao = $conn->real_escape_string($descricao);
$latitude = $conn->real_escape_string($latitude);
$longitude = $conn->real_escape_string($longitude);
$endereco = $conn->real_escape_string($endereco);

// Geração de um protocolo único para acompanhamento
// Formato: DEN-Ano-NumeroAleatorio (Ex: DEN-2023-4589)
$protocolo = "DEN-" . date("Y") . "-" . rand(1000, 9999);

$imagemNome = ""; // Variável para armazenar o nome da imagem, se houver

// Tratamento do upload de imagem
// Verifica se o arquivo foi enviado e se não houve erro no upload (error == 0)
if(isset($_FILES['imagem']) && $_FILES['imagem']['error'] == 0){
    // Define a pasta destino para os uploads (voltando um nível na pasta process)
    $pasta = "../uploads/";
    
    // Gera um nome único para a imagem usando o timestamp atual + nome original
    // Isso evita que arquivos com o mesmo nome sejam sobrescritos
    $imagemNome = time() . "_" . basename($_FILES['imagem']['name']);

    // Move o arquivo temporário (salvo pelo PHP) para a pasta destino oficial
    move_uploaded_file(
        $_FILES['imagem']['tmp_name'],
        $pasta . $imagemNome
    );
}

// Prepara a query SQL de inserção
$sql = "INSERT INTO denuncias (
            protocolo,
            descricao,
            imagem,
            latitude,
            longitude,
            endereco,
            status
        )
        VALUES (
            '$protocolo',
            '$descricao',
            '$imagemNome',
            '$latitude',
            '$longitude',
            '$endereco',
            'Recebida'
        )";

// Executa a query e verifica se o resultado foi um sucesso (TRUE)
if($conn->query($sql) === TRUE){
    // Redireciona para a página de sucesso (Padrão PRG)
    header("Location: ../sucesso.php?protocolo=$protocolo");
    exit;
} else {
    // Redireciona enviando o erro pela URL
    $erro = urlencode($conn->error);
    header("Location: ../sucesso.php?erro=$erro");
    exit;
}

// Encerra a conexão com o banco
$conn->close();
?>
