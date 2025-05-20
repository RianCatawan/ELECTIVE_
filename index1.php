<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
  <div class="container mt-5" style="max-width: 400px;">
    <h3 class="text-center mb-4">Login</h3>
    <form action="login.php" method="POST">
      <div class="mb-3">
        <label>Username</label>
        <input type="text" name="username" class="form-control" required />
      </div>
      <div class="mb-3">
        <label>Password</label>
        <input type="password" name="password" class="form-control" required />
      </div>
      <button type="submit" class="btn btn-primary w-100">Log In</button>
      <div class="mt-3 text-center">
        <a href="register.php">Don't have an account? Register</a>
      </div>
    </form>
  </div>
</body>
</html>

