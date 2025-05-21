<?php
session_start();

// Redirect if no temp user data
if (!isset($_SESSION['temp_user_data'])) {
    header("Location: register.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $inputPassword = $_POST['confirm_password'];
    $correctPassword = "password"; // the predefined confirmation password

    if ($inputPassword === $correctPassword) {
        // Password correct — insert user into database
        $conn = new mysqli("localhost", "root", "", "user_registration");
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $data = $_SESSION['temp_user_data'];
        $first_name = $conn->real_escape_string($data['first_name']);
        $last_name = $conn->real_escape_string($data['last_name']);
        $email = $conn->real_escape_string($data['email']);
        $password = password_hash($data['password'], PASSWORD_DEFAULT);
        $birthdate = $data['birthdate'];
        $sex = $data['sex'];
        $role = $data['role'];

        $sql = "INSERT INTO users (first_name, last_name, email, password, birthdate, sex, role)
                VALUES ('$first_name', '$last_name', '$email', '$password', '$birthdate', '$sex', '$role')";

        if ($conn->query($sql) === TRUE) {
            $_SESSION['username'] = $email;
            $_SESSION['role'] = $role;
            unset($_SESSION['temp_user_data']); // Clear temporary data
            header("Location: dashboard.php");
            exit();
        } else {
            echo "Error: " . $conn->error;
        }
        $conn->close();
    } else {
        $error = "Incorrect confirmation password!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Confirm Role Access</title>
    <style>
        body { font-family: Arial; background-color:rgb(0, 0, 0); padding: 40px;
        background-image: url(smoke.jpg);
 
      background-repeat: no-repeat;
      background-size: cover;
        }
        .box {
            max-width: 400px; margin: auto; padding: 30px; border-radius: 10px;  box-shadow: 0 0 20px rgba(0, 0, 0, 0.4);
      backdrop-filter: blur(5px);
      background-color: rgba(15, 14, 14, 0.8);  }
        input[type="password"], button {
            width: 100%; padding: 12px; margin: 10px 0;
            border: 1px solid #ccc; border-radius: 5px;
        }
        button {
            background-color:rgb(4, 232, 42); color: white; border: none;
        }
        .error { color: red; font-weight: bold; }
    </style>
</head>
<body>
    <br><br><br><br><br>
<div class="box">
    <h2 style="color: #ccc;">Confirm Admin/Editor Access</h2>
    <p style="color: #ccc;">Please enter the confirmation password to complete registration.</p>
    <form method="POST">
        <input type="password" name="confirm_password" placeholder="Enter password" required>
        <button type="submit">Confirm</button>
        <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>
    </form>
</div>
</body>
</html>
