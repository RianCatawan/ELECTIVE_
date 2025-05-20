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
        body { font-family: Arial; background-color: #f2f2f2; padding: 40px; }
        .box { background: #fff; max-width: 400px; margin: auto; padding: 30px; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        input[type="password"], button {
            width: 100%; padding: 12px; margin: 10px 0;
            border: 1px solid #ccc; border-radius: 5px;
        }
        button {
            background-color: #007bff; color: white; border: none;
        }
        .error { color: red; font-weight: bold; }
    </style>
</head>
<body>
<div class="box">
    <h2>Confirm Admin/Editor Access</h2>
    <p>Please enter the confirmation password to complete registration.</p>
    <form method="POST">
        <input type="password" name="confirm_password" placeholder="Enter password" required>
        <button type="submit">Confirm</button>
        <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>
    </form>
</div>
</body>
</html>
