
<?php
// Configurações de ambiente
define('APP_NAME', 'Clínica Saúde+');
define('BASE_URL', 'http://localhost/cizagui');

// Configurações do banco de dados
define('DB_HOST', 'localhost');
define('DB_NAME', 'clinica_db');
define('DB_USER', 'root');
define('DB_PASS', '');

// Conexão com o banco de dados usando PDO
try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec("SET NAMES 'utf8'");
} catch (PDOException $e) {
    die("Erro na conexão com o banco de dados: " . $e->getMessage());
}

// Configurações de fuso horário
date_default_timezone_set('America/Sao_Paulo');
