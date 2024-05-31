<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header('Content-Type: application/json');

include __DIR__ . '/database.php';

$response = ['status' => 'error', 'message' => ''];

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $fullName = isset($_POST['fullName']) ? $_POST['fullName'] : '';
        $username = isset($_POST['username']) ? $_POST['username'] : '';
        $password = isset($_POST['password']) ? $_POST['password'] : '';

        if (empty($fullName) || empty($username) || empty($password)) {
            $response['message'] = 'All fields are required';
        } elseif (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*(),.?":{}|<>]).{8,}$/', $password)) {
            $response['message'] = 'Password must be at least 8 characters long and include at least one uppercase letter, one lowercase letter, one number, and one special character';
        } else {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare('INSERT INTO users (full_name, username, password) VALUES (?, ?, ?)');
            if ($stmt->execute([$fullName, $username, $hashedPassword])) {
                $response['status'] = 'success';
                $response['message'] = 'Registration successful';
            } else {
                $errorInfo = $stmt->errorInfo();
                $response['message'] = 'Database insertion failed: ' . $errorInfo[2];
            }
        }
    } else {
        $response['message'] = 'Invalid request method';
    }
} catch (Exception $e) {
    $response['message'] = 'Exception: ' . $e->getMessage();
}

echo json_encode($response);