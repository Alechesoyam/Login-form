<?php
require_once '../../config/database.php';
require_once '../Models/User.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


// Auto login with remember me
if (!isset($_SESSION['user']) && isset($_COOKIE['remember_me'])) {
    $user = $userModel->findByRememberToken($_COOKIE['remember_me']);
    if ($user) {
        $_SESSION['user'] = [
            'id' => $user['id'],
            'username' => $user['username'],
            'role' => $user['role']
        ];
    }
}


// Redirect to login if not logged in
if (empty($_SESSION['user'])) {
    header('Location: Login.php');
    exit;
}

$username = $_SESSION['user']['username'];
$role = $_SESSION['user']['role'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Dashboard</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h1>Welcome, <?= htmlspecialchars($username) ?>!</h1>
    <p>Your role: <?= htmlspecialchars($role) ?></p>
   <a href="../Controllers/AuthController.php?action=logout&csrf_token=<?= $_SESSION['csrf_token'] ?>" class="btn btn-danger">Logout</a>
</div>
</body>
</html>
