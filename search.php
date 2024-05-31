<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header('Content-Type: application/json');

include __DIR__ . '/database.php';

$response = ['status' => 'error', 'message' => ''];

try {
    // Obtener el token del header
    $headers = getallheaders();
    if (!isset($headers['Authorization'])) {
        throw new Exception('Authorization header not found');
    }

    $token = str_replace('Bearer ', '', $headers['Authorization']);

    // Validar el token de sesión
    $stmt = $pdo->prepare('SELECT * FROM sessions WHERE token = ?');
    $stmt->execute([$token]);
    $session = $stmt->fetch();

    if (!$session) {
        throw new Exception('Invalid token');
    }

    // Verificar el método de solicitud y parámetro de búsqueda
    if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['q'])) {
        $query = $_GET['q'];
        $stmt = $pdo->prepare('SELECT * FROM movies WHERE title LIKE ?');
        $stmt->execute(["%$query%"]);
        $movies = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $response['status'] = 'success';
        $response['movies'] = $movies;
    } else {
        $response['message'] = 'Invalid request method or parameters';
    }
} catch (Exception $e) {
    $response['message'] = 'Exception: ' . $e->getMessage();
}

echo json_encode($response);