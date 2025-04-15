<?php
session_start();
require_once 'includes/config.php';

$loginError = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if ($email && $password) {
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['name'] = $user['name'];
            header("Location: home.php");
            exit();
        } else {
            $loginError = "Invalid email or password.";
        }
    } else {
        $loginError = "Please fill in all fields.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - Event Booking</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex; justify-content: center; align-items: center;
            height: 100vh; background-color: #f0f0f0;
        }
        .login-container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px #ccc;
            width: 300px;
        }
        .login-container h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        input[type=email], input[type=password] {
            width: 100%; padding: 10px; margin-bottom: 10px;
            border: 1px solid #ccc; border-radius: 5px;
        }
        button {
            width: 100%; padding: 10px;
            background-color: green; color: white; border: none; border-radius: 5px;
        }
        .link {
            margin-top: 10px; text-align: center;
        }
        .error {
            color: red; margin-bottom: 10px; text-align: center;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Event Booking System</h2>
        <?php if ($loginError): ?>
            <div class="error"><?= $loginError ?></div>
        <?php endif; ?>
        <form method="POST" action="">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
        <div class="link">
            <a href="register.php">Not a member yet? Register here</a>
        </div>
    </div>
</body>
</html>
