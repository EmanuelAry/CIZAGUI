<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');

require_once __DIR__ . '/../../vendor/autoload.php';
// Simulação de conexão PDO - você deve substituir pela sua conexão real
try {
    $pdo = new \PDO("mysql:host=localhost;dbname=cizagui", "root", "");
} catch (\PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Erro de conexão: ' . $e->getMessage()]);
    exit;
}

$controller = new \App\Controllers\ClinicaController($pdo);

// Determinar o método da requisição
$method = $_SERVER['REQUEST_METHOD'];

// Obter dados da requisição
if ($method === 'POST' || $method === 'PUT') {
    $input = json_decode(file_get_contents('php://input'), true);
    $data = $input ?: [];
} else {
    $data = $_GET;
}

// Tratar a requisição
$response = $controller->handleRequest($method, $data);

echo json_encode($response);
?>