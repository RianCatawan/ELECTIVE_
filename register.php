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

    $sql = "INSERT INTO users (first_name, last_name, email, username, password, birthdate, sex, role)
            VALUES ('$fname', '$lname', '$email', '$username', '$password', '$birthdate', '$sex', '$role')";

    if ($conn->query($sql) === TRUE) {
        $_SESSION['username'] = $username;
        $_SESSION['role'] = $role;

        // Redirect based on role
       if ($role === 'admin' || $role === 'editor') {
    session_start();
    $_SESSION['temp_user_data'] = $_POST; // temporarily store all user data
    header("Location: confirm_role.php");
    exit();
}
        } else {
            header("Location: index.php");
        }
        exit();
    } else {
        echo " " . $conn->error;
    }

?>
<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <style>
        body { font-family: Arial; background: #f0f2f5; padding: 40px; }
        .container { background: #fff; padding: 30px; max-width: 500px; margin: auto; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        input, select { width: 100%; padding: 10px; margin-top: 10px; border-radius: 5px; border: 1px solid #ccc; }
        input[type="submit"] { background: #007bff; color: white; font-weight: bold; cursor: pointer; }
    </style>
</head>
<body>
<div class="container">
    <h2>User Registration</h2>
    <form method="POST" action="register.php">
        <input type="text" name="first_name" placeholder="First Name" required>
        <input type="text" name="last_name" placeholder="Last Name" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <input type="date" name="birthdate" required>
        <select name="sex" required>
            <option value="">Select Sex</option>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
        </select>
        <select name="role" required>
            <option value="">Select Role</option>
            <option value="admin">Admin</option>
            <option value="editor">Editor</option>
            <option value="user">User</option>
        </select>
        <input type="submit" value="Register">
    </form>
</div>
</body>
</html>
