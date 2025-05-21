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

// FULL JOIN simulation to fetch combined data
$sql = "
SELECT u.id, u.username, u.email, u.role, ul.password AS login_password
FROM users u
LEFT JOIN user_login ul ON u.username = ul.username

UNION

SELECT NULL AS id, ul.username, NULL AS email, NULL AS role, ul.password AS login_password
FROM user_login ul
LEFT JOIN users u ON ul.username = u.username
WHERE u.username IS NULL
ORDER BY username;
";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard - User Management</title>
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
        table { border-collapse: collapse; width: 90%; margin: 20px auto; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background-color: #f0f0f0; }
        a.delete-link { color: red; text-decoration: none; }
    </style>
</head>
<body>
    <a href="dashboard.php" class="logout">Logout</a>
    <h2 style="color: #fff;">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?> (<?php echo htmlspecialchars($_SESSION['role']); ?>)</h2>

    <h3 style="color: #fff;">User Data (Combined from users & user_login)</h3>

    <table>
        <thead>
            <tr>
                <th>ID (users)</th>
                <th>Username</th>
                <th>Email</th>
                <th>Role</th>
                <th>Password (user_login)</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . ($row['id'] ?? 'N/A') . "</td>";
                    echo "<td>" . htmlspecialchars($row['username']) . "</td>";
                    echo "<td>" . ($row['email'] ?? 'N/A') . "</td>";
                    echo "<td>" . ($row['role'] ?? 'N/A') . "</td>";
                    echo "<td>" . htmlspecialchars($row['login_password'] ?? 'N/A') . "</td>";
                    echo "<td>";
                    if (!empty($row['id'])) {
                        echo '<a href="dashboard.php?delete=' . $row['id'] . '" class="delete-link" onclick="return confirm(\'Delete this user?\');">Delete</a>';
                    } else {
                        echo '-';
                    }
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='6'>No users found.</td></tr>";
            }
            $conn->close();
            ?>
        </tbody>
    </table>
</body>
</html>
