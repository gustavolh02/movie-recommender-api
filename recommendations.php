<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header('Content-Type: application/json');

include __DIR__ . '/database.php';

$response = ['status' => 'error', 'message' => ''];

try {
    // Obtener el token del encabezado
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

    // Obtener el ID del usuario
    $user_id = $session['user_id'];

    // Hacer la solicitud a la API de recomendaciones
    $url = 'http://localhost:5000/recommend';
    $options = [
        'http' => [
            'header'  => "Content-type: application/json\r\n",
            'method'  => 'POST',
            'content' => json_encode(['user_id' => $user_id]),
        ],
    ];
    $context  = stream_context_create($options);
    $result = file_get_contents($url, false, $context);
    if ($result === FALSE) {
        throw new Exception('Error fetching recommendations');
    }

    $response['status'] = 'success';
    $response['recommendations'] = json_decode($result, true);
} catch (Exception $e) {
    $response['message'] = 'Exception: ' . $e->getMessage();
}

echo json_encode($response);