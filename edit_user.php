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
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: url('smoke.jpg') no-repeat bottom center / cover;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .form-box {
            width: 50%;
            background: rgba(0, 0, 0, 0.9);
            padding: 30px 40px;
            border-radius: 10px;
            color: #fff;
            box-shadow: 0 0 20px rgba(0,0,0,0.5);
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #00ffcc;
        }

        .form-row {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
        }

        .form-group {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        label {
            font-size: 14px;
            margin-bottom: 5px;
        }

        input, select {
            padding: 10px;
            border: none;
            border-radius: 5px;
            background-color: #f7f7f7;
            color: #333;
            font-size: 14px;
        }

        input:focus, select:focus {
            outline: 2px solid #00ffcc;
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: #00cc99;
            border: none;
            border-radius: 6px;
            color: white;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s;
            margin-top: 10px;
        }

        button:hover {
            background-color: #00b386;
        }

        @media (max-width: 768px) {
            .form-box {
                width: 90%;
            }

            .form-row {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>

<div class="form-box">
    <h2>Edit User</h2>
    <form method="POST">
        <input type="hidden" name="id" value="<?= $user['id'] ?>">

        <div class="form-row">
            <div class="form-group">
                <label for="first_name">First Name</label>
                <input type="text" name="first_name" value="<?= $user['first_name'] ?>" required>
            </div>
            <div class="form-group">
                <label for="last_name">Last Name</label>
                <input type="text" name="last_name" value="<?= $user['last_name'] ?>" required>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" value="<?= $user['email'] ?>" required>
            </div>
            <div class="form-group">
                <label for="password">New Password</label>
                <input type="password" name="password" placeholder="Leave blank to keep current">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="birthdate">Birthdate</label>
                <input type="date" name="birthdate" value="<?= $user['birthdate'] ?>" required>
            </div>
            <div class="form-group">
                <label for="sex">Sex</label>
                <select name="sex" required>
                    <option value="Male" <?= $user['sex'] == 'Male' ? 'selected' : '' ?>>Male</option>
                    <option value="Female" <?= $user['sex'] == 'Female' ? 'selected' : '' ?>>Female</option>
                </select>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="role">Role</label>
                <select name="role" required>
                    <option value="user" <?= $user['role'] == 'user' ? 'selected' : '' ?>>User</option>
                    <option value="editor" <?= $user['role'] == 'editor' ? 'selected' : '' ?>>Editor</option>
                    <option value="admin" <?= $user['role'] == 'admin' ? 'selected' : '' ?>>Admin</option>
                </select>
            </div>
            <div class="form-group">
                <!-- Empty block for layout alignment -->
            </div>
        </div>

        <button type="submit">Update User</button>
    </form>
</div>

</body>
</html>
