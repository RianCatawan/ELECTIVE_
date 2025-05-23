<?php
session_start();

// MySQL database connection
$conn = new mysqli("localhost", "root", "", "user_registration");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fname = $_POST['first_name'];
    $lname = $_POST['last_name'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $birthdate = $_POST['birthdate'];
    $sex = $_POST['sex'];
    $role = $_POST['role'];

    //  Check if username already exists
    $checkUser = "SELECT * FROM users WHERE username = '$username'";
    $result = $conn->query($checkUser);

    if ($result->num_rows > 0) {
        // Username already exists
        echo "<script>
                alert('Username already taken. Please choose a different one.');
                window.location.href = 'register.php'; // Change to your actual registration form page
              </script>";
        exit();
    }

    //  Insert new user
    $sql = "INSERT INTO users (first_name, last_name, email, username, password, birthdate, sex, role)
            VALUES ('$fname', '$lname', '$email', '$username', '$password', '$birthdate', '$sex', '$role')";

    if ($conn->query($sql) === TRUE) {
        $_SESSION['username'] = $username;
        $_SESSION['role'] = $role;

        // Redirect based on role
        if ($role === 'admin' || $role === 'editor') {
            $_SESSION['temp_user_data'] = $_POST;
            header("Location: confirm_role.php");
        } else {
            header("Location: index.php");
        }
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Register</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #ffffff;
      background-image: url('smoke.jpg');

      background-repeat: no-repeat;
      background-size: cover;
    

    }
    .container {
      background: #fff;
      padding: 30px;
      max-width: 500px;
      margin: auto;
      border-radius: 10px;
  box-shadow: 0 0 20px rgba(0, 0, 0, 0.4);
      backdrop-filter: blur(5px);
      background-color: rgba(15, 14, 14, 0.8); 
    }
    h2 {
      text-align: center;
      margin-bottom: 20px;
    }
    .form-control {
      border-radius: 6px;
      margin-top: 10px;
    }
    .row > .col {
      padding-right: 5px;
      padding-left: 5px;
    }
    input[type="submit"] {
      margin-top: 20px;
      background-color: #1877f2;
      color: white;
      font-weight: bold;
      border: none;
    }
    input[type="submit"]:hover {
      background-color: #165ec9;
    }
 
nav.navbar {
  position: fixed;
  top: 0;
  width: 100%;
  z-index: 99;

  margin-left: var(--navbar-offset, -3px);
}

body {
  padding-top: 90px;
}

.custom-brand {
  margin-left: var(--brand-offset, 100px);
}
    .move-back {
  margin-left: 100px; 
  margin-top: 59px;  
  text-decoration-color: #fff;
}

  </style>
</head>

<a href="index1.php" class="move-back" style="color: #ffffff;">back</a>

<body>
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


<div class="container">
  <h2 style="color: #fff;"> Create Account</h2>
  <form method="POST" action="register.php">
    
    <div class="row">
      <div class="col">
        <input type="text" name="first_name" class="form-control" placeholder="First name" required>
      </div>
      <div class="col">
        <input type="text" name="last_name" class="form-control" placeholder="Last name" required>
      </div>
    </div>
    
    <input type="email" name="email" class="form-control" placeholder="Email" required>
    <input type="text" name="username" class="form-control" placeholder="Username" required>
    <input type="password" name="password" class="form-control" placeholder="New password" required>
    <input type="date" name="birthdate" class="form-control" required>
    
    <select name="sex" class="form-control" required>
      <option value="">Select Sex</option>
      <option value="Male">Male</option>
      <option value="Female">Female</option>
    </select>

    <select name="role" class="form-control" required>
      <option value="">Select Role</option>
      <option value="admin">Admin</option>
      <option value="editor">Editor</option>
      <option value="user">User</option>
    </select>

    <input type="submit" value="Register" class="form-control">
    
  </form>
</div>

</body>
</html>
