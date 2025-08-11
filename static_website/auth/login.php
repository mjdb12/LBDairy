<?php
session_start();
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);

if (!isset($input['username']) || !isset($input['password']) || !isset($input['user_type'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing required fields']);
    exit;
}

$username = trim($input['username']);
$password = $input['password'];
$user_type = $input['user_type'];

// Validate user type
$allowed_types = ['farmer', 'admin', 'superadmin'];
if (!in_array($user_type, $allowed_types)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid user type']);
    exit;
}

try {
    // Check if user exists
    $stmt = $conn->prepare("SELECT id, username, password, email, user_type, first_name, last_name, status FROM users WHERE username = ? AND user_type = ?");
    $stmt->bind_param("ss", $username, $user_type);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        http_response_code(401);
        echo json_encode(['error' => 'Invalid credentials']);
        exit;
    }
    
    $user = $result->fetch_assoc();
    
    // Check if user is active
    if ($user['status'] !== 'active') {
        http_response_code(401);
        echo json_encode(['error' => 'Account is not active']);
        exit;
    }
    
    // Verify password
    if (!password_verify($password, $user['password'])) {
        http_response_code(401);
        echo json_encode(['error' => 'Invalid credentials']);
        exit;
    }
    
    // Create session
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['user_type'] = $user['user_type'];
    $_SESSION['first_name'] = $user['first_name'];
    $_SESSION['last_name'] = $user['last_name'];
    
    // Log the login
    $ip_address = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';
    
    $audit_stmt = $conn->prepare("INSERT INTO audit_logs (user_id, action, table_name, ip_address, user_agent) VALUES (?, 'login', 'users', ?, ?)");
    $audit_stmt->bind_param("iss", $user['id'], $ip_address, $user_agent);
    $audit_stmt->execute();
    
    // Return user data (without password)
    unset($user['password']);
    
    echo json_encode([
        'success' => true,
        'message' => 'Login successful',
        'user' => $user,
        'redirect' => $user_type === 'superadmin' ? 'superadmin.html' : 
                    ($user_type === 'admin' ? 'admin.html' : 'users.html')
    ]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Server error: ' . $e->getMessage()]);
}

$stmt->close();
$conn->close();
?> 