<?php
$conn = new mysqli("localhost", "root", "", "user_registration");

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $result = $conn->query("SELECT * FROM users WHERE id=$id");
    $user = $result->fetch_assoc();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = intval($_POST['id']);
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $birthdate = $_POST['birthdate'];
    $sex = $_POST['sex'];
    $role = $_POST['role'];
    $password = $_POST['password'];

    if (!empty($password)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $sql = "UPDATE users SET first_name='$first_name', last_name='$last_name', email='$email', password='$hashed_password', birthdate='$birthdate', sex='$sex', role='$role' WHERE id=$id";
    } else {
        $sql = "UPDATE users SET first_name='$first_name', last_name='$last_name', email='$email', birthdate='$birthdate', sex='$sex', role='$role' WHERE id=$id";
    }

    $conn->query($sql);
    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit User</title>
    <style>
        body { font-family: Arial; padding: 20px; background: #f2f2f2; }
        .form-box { max-width: 500px; margin: auto; background: white; padding: 20px; border-radius: 8px; }
        input, select, button {
            width: 100%; padding: 10px; margin: 10px 0;
            border: 1px solid #ccc; border-radius: 4px;
        }
        button { background-color: #28a745; color: white; }
    </style>
</head>
<body>

<div class="form-box">
    <h2>Edit User</h2>
    <form method="POST">
        <input type="hidden" name="id" value="<?= $user['id'] ?>">
        <input type="text" name="first_name" value="<?= $user['first_name'] ?>" required>
        <input type="text" name="last_name" value="<?= $user['last_name'] ?>" required>
        <input type="email" name="email" value="<?= $user['email'] ?>" required>
        <input type="password" name="password" placeholder="Enter new password (leave blank to keep old)">
        <input type="date" name="birthdate" value="<?= $user['birthdate'] ?>" required>
        <select name="sex" required>
            <option value="Male" <?= $user['sex'] == 'Male' ? 'selected' : '' ?>>Male</option>
            <option value="Female" <?= $user['sex'] == 'Female' ? 'selected' : '' ?>>Female</option>
        </select>
        <select name="role" required>
            <option value="user" <?= $user['role'] == 'user' ? 'selected' : '' ?>>User</option>
            <option value="editor" <?= $user['role'] == 'editor' ? 'selected' : '' ?>>Editor</option>
            <option value="admin" <?= $user['role'] == 'admin' ? 'selected' : '' ?>>Admin</option>
        </select>
        <button type="submit">Update User</button>
    </form>
</div>

</body>
</html>
