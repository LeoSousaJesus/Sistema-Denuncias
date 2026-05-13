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

// Proteção básica contra injeção de SQL (limpa as strings recebidas)
$descricao = $conn->real_escape_string($descricao);
$latitude = $conn->real_escape_string($latitude);
$longitude = $conn->real_escape_string($longitude);

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
            status
        )
        VALUES (
            '$protocolo',
            '$descricao',
            '$imagemNome',
            '$latitude',
            '$longitude',
            'Recebida'
        )";

// Executa a query e verifica se o resultado foi um sucesso (TRUE)
if($conn->query($sql) === TRUE){
    // Inclui o cabeçalho global
    include '../includes/header.php';
    
    // Se inserido com sucesso, exibe a tela de confirmação para o usuário
    echo "
    <div class='container' style='text-align: center; max-width: 500px;'>
        <div style='font-size: 60px; color: var(--primary-color); margin-bottom: 10px;'>✅</div>
        <h1>Denúncia Registrada!</h1>
        <p>Agradecemos a sua colaboração. Sua denúncia foi encaminhada para a nossa equipe de fiscalização ambiental.</p>
        
        <div style='background: var(--secondary-color); padding: 20px; border-radius: 8px; margin: 20px 0;'>
            <p style='margin-bottom: 5px; font-size: 14px; text-transform: uppercase; letter-spacing: 1px; color: var(--text-light);'>Seu número de protocolo:</p>
            <h2 style='font-size: 28px; margin: 0; color: var(--primary-color);'>$protocolo</h2>
        </div>
        
        <p style='font-size: 14px; color: #666;'>Guarde este número para consultar o andamento posteriormente.</p>
        
        <br>
        <a href='../status.php?protocolo=$protocolo' class='botao'>Acompanhar Status Agora</a>
        <a href='../index.php' style='display: block; margin-top: 15px; color: var(--text-light); text-decoration: none;'>Voltar à página inicial</a>
    </div>
    ";
    
    // Inclui o rodapé global
    include '../includes/footer.php';

} else {
    // Caso ocorra um erro na inserção (Ex: erro no banco), exibe a mensagem
    include '../includes/header.php';
    echo "
    <div class='container'>
        <h1 style='color: #D32F2F;'>Erro ao Registrar</h1>
        <p>Infelizmente, ocorreu um erro interno: " . $conn->error . "</p>
        <a href='../denuncia.php' class='botao'>Tentar Novamente</a>
    </div>
    ";
    include '../includes/footer.php';
}

// Encerra a conexão com o banco
$conn->close();
?>
