<?php
session_start();

// Access control
if (!isset($_SESSION['username']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'editor')) {
    header("Location: index.html");
    exit();
}

$conn = new mysqli("localhost", "root", "", "user_registration");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// DELETE logic
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM users WHERE id=$id");
    header("Location: dashboard.php");
    exit();
}

// FETCH USERS
$result = $conn->query("SELECT * FROM users");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
   <style>
    body {
        font-family: Arial;
        background-color: rgb(0, 0, 0);
        padding: 20px;
        margin: 0;
        background-image: url(smoke.jpg);
        background-size: cover;
        background-repeat: no-repeat;
        background-attachment: fixed; /* Keep the background static while scrolling */
        min-height: 200vh; /* Make the page tall for scrolling */
        overflow-y: auto; /* Enable vertical scrolling */
    }

    table {
        border-collapse: collapse;
        width: 90%;
        background-color: #fff;
        margin: 0 auto; /* Center the table */
        margin-top: 100px; /* Push down below navbar */
    }

    th, td {
        border: 1px solid #ccc;
        padding: 10px;
        text-align: center;
    }

    th {
        background-color: rgba(30, 255, 0, 0.75);
        color: white;
    }

    a.button, button {
        padding: 8px 15px;
        border: none;
        border-radius: 5px;
        text-decoration: none;
        cursor: pointer;
    }

    a.button.update {
        background-color: rgba(10, 222, 18, 0.82);
        color: white;
    }

    a.button.delete {
        background-color: #dc3545;
        color: white;
    }

    .logout {
        float: right;
        background-color: #333;
        color: white;
        padding: 10px 15px;
        text-decoration: none;
        border-radius: 5px;
    }
</style>

</head>
<body>
<h2 style="color: #fff;"> <?= $_SESSION['role'];?> Dashboard</h2>
<a href="index.php" class="logout">Logout</a>
<h3 style="color: #fff;">User Records</h3>

<table>
    <tr>
        <th>ID</th><th>First Name</th><th>Last Name</th><th>Email</th><th>Birthdate</th><th>Sex</th><th>Role</th><th>Actions</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()): ?>
    <tr>
        <td><?= $row['id'] ?></td>
        <td><?= htmlspecialchars($row['first_name']) ?></td>
        <td><?= htmlspecialchars($row['last_name']) ?></td>
        <td><?= htmlspecialchars($row['email']) ?></td>
        <td>********</td>
        <td><?= $row['birthdate'] ?></td>
        <td><?= $row['sex'] ?></td>
        <td><?= $row['role'] ?></td>
        <td>
        <td>
            <a class="button update" href="edit_user.php?id=<?= $row['id'] ?> ">Update</a>
            <a class="button delete" href="?delete=<?= $row['id'] ?>" onclick="return confirm('Are you sure?')">Delete</a>
        </td>
    </tr>
    <?php endwhile; ?>
</table>

</body>
</html>
