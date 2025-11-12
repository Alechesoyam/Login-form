<?php require_once '../../config/database.php';?>

<?php
  session_start();
  
  if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
  } 
    $token = $_SESSION['csrf_token']; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Form</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

  <div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="card shadow-sm" style="width: 350px;">
      <div class="card-body">

        <h3 class="card-title text-center mb-4">Login</h3>

        <!-- Status message -->
        <?php if (!empty($error)): ?>
          <div style="color:red;"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <?php if (!empty($success)): ?>
          <div style="color:green;"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>

        <form action="../Controllers/AuthController.php" method="POST">

        <!-- CSRF Token Field -->
        <input type="text" name="csrf_token" value="<?php $token;?>" hidden>

          <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" class="form-control" name="username" id="username" placeholder="Enter username" required>
          </div>
          <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" name="password" id="password" placeholder="Enter password" required>
          </div>
          <div class="d-grid">
            <button type="submit" name="login" class="btn btn-primary">Login</button>
          </div>
          <div class="mt-3 text-center">
            <a href="#">Forgot password?</a>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Bootstrap JS Bundle -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
