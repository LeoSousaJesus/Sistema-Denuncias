<?php
include 'includes/db.php';
$conn->query("ALTER TABLE denuncias ADD COLUMN is_anonimo TINYINT(1) DEFAULT 1");
$conn->query("ALTER TABLE denuncias ADD COLUMN nome VARCHAR(100)");
$conn->query("ALTER TABLE denuncias ADD COLUMN sobrenome VARCHAR(100)");
$conn->query("ALTER TABLE denuncias ADD COLUMN telefone VARCHAR(20)");
$conn->query("ALTER TABLE denuncias ADD COLUMN email VARCHAR(100)");
echo "Atualizado com sucesso.";
