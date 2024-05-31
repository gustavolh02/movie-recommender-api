<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header('Content-Type: application/json');

include __DIR__ . '/database.php';

$response = ['status' => 'error', 'message' => ''];

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = isset($_POST['username']) ? $_POST['username'] : '';
        $password = isset($_POST['password']) ? $_POST['password'] : '';

        if (empty($username) || empty($password)) {
            $response['message'] = 'Username and password are required';
        } else {
            $stmt = $pdo->prepare('SELECT * FROM users WHERE username = ?');
            if ($stmt->execute([$username])) {
                $user = $stmt->fetch();
                if ($user && password_verify($password, $user['password'])) {
                    $token = bin2hex(random_bytes(16)); // Generar un token de sesión
                    $stmt = $pdo->prepare('INSERT INTO sessions (user_id, token) VALUES (?, ?)');
                    $stmt->execute([$user['id'], $token]);

                    $response['status'] = 'success';
                    $response['token'] = $token;
                } else {
                    $response['message'] = 'Invalid username or password';
                }
            } else {
                $response['message'] = 'Database error: ' . $stmt->errorInfo()[2];
            }
        }
    } else {
        $response['message'] = 'Invalid request method';
    }
} catch (Exception $e) {
    $response['message'] = 'Exception: ' . $e->getMessage();
}

echo json_encode($response);
