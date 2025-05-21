<?php
session_start();

// Connect to user_registration DB (both tables here)
$conn = new mysqli("localhost", "root", "", "user_registration");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // 1. Verify username & password in users table
    $stmt = $conn->prepare("SELECT password, role FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($hashed_password, $role);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            // Login success, set session variables
            $_SESSION['username'] = $username;
            $_SESSION['role'] = $role;

            // 2. Insert login info into user_login table (username and password)
            // Prevent duplicate login records for same username:
            $check = $conn->prepare("SELECT id FROM user_login WHERE username = ?");
            $check->bind_param("s", $username);
            $check->execute();
            $check->store_result();

            if ($check->num_rows == 0) {
                // Insert username and hashed password
                $insert = $conn->prepare("INSERT INTO user_login (username, password) VALUES (?, ?)");
                $insert->bind_param("ss", $username, $hashed_password);
                $insert->execute();
                $insert->close();
            }
            $check->close();

            // Redirect based on role
            if ($role === 'admin' || $role === 'editor') {
                header("Location: dashboard.php");
            } else {
                header("Location: index.php");
            }
            exit();
        } else {
            echo "⚠️ Invalid password.";
        }
    } else {
        echo "⚠️ Username not found.";
    }

    $stmt->close();
    $conn->close();
}
?>
