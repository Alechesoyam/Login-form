<?php
require_once '../../config/database.php';
require_once '../models/User.php';

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$userModel = new User($pdo);

// Generate CSRF token if not exists
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// ---------- REGISTER ----------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {

    // CSRF check
    if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        $_SESSION['error'] = "Invalid CSRF token";
        header('Location: ../views/register.php');
        exit;
    }

    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $confirm = $_POST['confirm_password'];

    // Password match check
    if ($password !== $confirm) {
        $_SESSION['error'] = "Passwords do not match.";
        header('Location: ../views/register.php');
        exit;
    }

    // Check if username exists
    if ($userModel->findByUsername($username)) {
        $_SESSION['error'] = "Username already taken.";
        header('Location: ../views/register.php');
        exit;
    }

    // Hash password and create user
    $userModel->create($username, $password); // create() hashes password inside
    $_SESSION['success'] = "Registration successful! You can now login.";
    header('Location: ../views/login.php');
    exit;
}

// ---------- LOGIN ----------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {

    // CSRF check
    if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        $_SESSION['error'] = "Invalid CSRF token";
        header('Location: ../views/login.php');
        exit;
    }

    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $remember = isset($_POST['remember']);

    $user = $userModel->findByUsername($username);

    if ($user && password_verify($password, $user['password'])) {

        session_regenerate_id(true);
        $_SESSION['user'] = [
            'id' => $user['id'],
            'username' => $user['username'],
            'role' => $user['role']
        ];

        // Remember Me
        if ($remember) {
            $token = bin2hex(random_bytes(16));
            setcookie("remember_me", $token, [
                'expires' => time() + (86400 * 30),
                'path' => '/',
                'secure' => true,
                'httponly' => true,
                'samesite' => 'Lax'
            ]);
            $userModel->updateRememberToken($user['id'], $token);
        }

        header('Location: ../views/dashboard.php');
        exit;
    } else {
        $_SESSION['error'] = "Invalid username or password.";
        header('Location: ../views/login.php');
        exit;
    }
}

// ---------- LOGOUT ----------
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    session_unset();
    session_destroy();
    setcookie("remember_me", "", time() - 3600, "/");
    header('Location: ../views/login.php');
    exit;
}

// Redirect any other access to login
header('Location: ../views/login.php');
exit;
?>
