<?php
session_start();

// Check if admin is already logged in
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header("Location: manageEvents.php");
    exit();
}

// Handle login submission
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $admin_username = 'admin';
    $admin_password = 'admin123';

    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if ($username === $admin_username && $password === $admin_password) {
        $_SESSION['admin_logged_in'] = true;
        header("Location: manageEvents.php");
        exit();
    } else {
        $error = "Invalid username or password!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="../event_booking_system.css">
</head>

<body>
    <div class="login-form">
        <h2>Admin Login</h2>

        <?php if (!empty($error)): ?>
            <p style="color:red;"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <form id="adminLoginForm" action="admin.php" method="post">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
                <p id="usernameError" class="error"></p>
            </div>

            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
                <p id="passwordError" class="error"></p>
            </div>

            <button type="submit">Login</button>
        </form>
    </div>

    <script src="admin.js"></script>
</body>

</html>
