<?php 
// Autoload do Composer
require_once __DIR__ . '/vendor/autoload.php';

require_once "src/views/clinica/cadastro.php";

// use App\Controllers\ProfissionalController;

// // Conexão com o banco de dados
// $pdo = new PDO("mysql:host=localhost;dbname=cizagui", "root", "");
// $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// // Instancia o controlador
// $profissionalController = new ProfissionalController($pdo);

// // Exemplo de uso: listar profissionais da clínica com ID 1
// $profissionais = $profissionalController->listarPorClinica(1);

// // Exibe os profissionais
// echo "<h1>Profissionais da Clínica</h1>";
// echo "<ul>";
// foreach ($profissionais as $prof) {
//     echo "<li>{$prof['profissional_nome']} - {$prof['profissional_email']}</li>";
// }
// echo "</ul>";