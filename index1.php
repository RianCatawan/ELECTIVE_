<?php
session_start();

$conn = new mysqli("localhost", "root", "", "user_login");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepare statement to get username, password (plain), and role by email
    $stmt = $conn->prepare("SELECT username, password, role FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($username_db, $password_db, $role);
        $stmt->fetch();

        // Direct comparison of plain text password
        if ($password === $password_db) {
            $_SESSION['username'] = $username_db;
            $_SESSION['role'] = $role;

            if ($role === 'admin' || $role === 'editor') {
                header("Location: dashboard.php");
            } else {
                header("Location: index.php");
            }
            exit();
        } else {
            echo "Invalid password.";
        }
    } else {
        echo "Email not found.";
    }
    $stmt->close();
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <style>
    body {
  font-family: Arial; background-color: #f2f2f2; padding: 40px;
        background-image: url(smoke.jpg);
 
      background-repeat: no-repeat;
      background-size: cover;
    
    }

    .login-box {
      background-image: url(''); /* Replace with your image */
      background-size: cover;
      background-position: center;
      padding: 30px;
      border-radius: 15px;
      box-shadow: 0 0 20px rgba(0, 0, 0, 0.4);
      backdrop-filter: blur(5px);
      background-color: rgba(15, 14, 14, 0.8); /* Semi-transparent overlay */
    }

    .login-box h3 {
      color: #000;
      font-weight: bold;
    }

    .form-control {
      background-color: rgba(255, 255, 255, 0.9);
    }

nav.navbar {
  position: fixed;
  top: 0;
  width: 100%;
  z-index: 99;

  margin-left: var(--navbar-offset, -40px);
}

body {
  padding-top: 90px;
}

.custom-brand {
  margin-left: var(--brand-offset, 100px);
}



  </style>
</head>

<body>
<!-- Header -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark w-100">
  <div class="container-fluid">
    <a class="navbar-brand custom-brand" href="#">Technest</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" 
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="index.php">Home</a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<br>

<!-- Login Form in a Box -->
<div class="container d-flex justify-content-center align-items-center" style="min-height: 60vh;">
  <div class="login-box" style="width: 100%; max-width: 450px;">
    <h4 class="text-center mb-4" style="color: #ffffff;">Login</h4>
    <form action="login.php" method="POST">
      <div class="mb-3">
        <label class="form-label" style="color: #ffffff;">Username</label>
        <input type="text" name="username" class="form-control" required />
      </div>
      <div class="mb-3">
        <label class="form-label" style="color: #ffffff;">Password</label>
        <input type="password" name="password" class="form-control" required />
      </div>
      <button type="submit" class="btn btn-primary w-100">Log In</button>
      <div class="mt-3 text-center">
        <a href="register.php" style="color: #ffffff;">Don't have an account? Register</a>
      </div>
    </form>
  </div>
</div>

</body>
</html>
