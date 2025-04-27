<?php
session_start();
require_once '../includes/config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$name = $_SESSION['name'];
$cart = $_SESSION['cart'] ?? [];

$totalPrice = 0;
$cartDetails = [];

if ($cart) {
    // Fetch event data from DB
    foreach ($cart as $event_id => $quantity) {
        $stmt = $conn->prepare("SELECT * FROM events WHERE id = ?");
        $stmt->execute([$event_id]);
        $event = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($event) {
            $event['quantity'] = $quantity;
            $event['subtotal'] = $event['price'] * $quantity;
            $totalPrice += $event['subtotal'];
            $cartDetails[] = $event;
        }
    }
}

// Reserve Tickets: Insert into bookings table
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reserve'])) {
    foreach ($cartDetails as $event) {
        $stmt = $conn->prepare("INSERT INTO bookings (user_id, event_id, quantity, total_price) VALUES (?, ?, ?, ?)");
        $stmt->execute([$_SESSION['user_id'], $event['id'], $event['quantity'], $event['subtotal']]);

        // Optional: Update event max_tickets
        $newAvailable = $event['max_tickets'] - $event['quantity'];
        $conn->prepare("UPDATE events SET max_tickets = ? WHERE id = ?")
             ->execute([$newAvailable, $event['id']]);
    }

    $_SESSION['cart'] = []; // clear cart
    header("Location: home.php?reserved=1");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Cart - Event Booking</title>
    <style>
        body { font-family: Arial; margin: 0; background: #f4f4f4; }
        header, footer {
            background: #111; color: white;
            padding: 15px 20px;
        }
        .header-right { float: right; }
        .container {
            padding: 30px;
            max-width: 1000px;
            margin: auto;
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        table {
            width: 100%; border-collapse: collapse; margin-top: 20px;
        }
        table th, table td {
            border: 1px solid #ccc; padding: 10px; text-align: center;
        }
        .total {
            text-align: right; margin-top: 20px; font-size: 18px;
        }
        .btn {
            display: block;
            width: 100%;
            padding: 12px;
            background: green;
            color: white;
            text-align: center;
            border: none;
            margin-top: 20px;
            font-size: 16px;
            border-radius: 6px;
        }
    </style>
</head>
<body>

<header>
    <a href="home.php" style="color:white; text-decoration:none; font-weight:bold;">
        Event Booking System
    </a>
    <div class="header-right">
        Welcome, <?= htmlspecialchars($name) ?> |
        <span style="background:green; padding:5px 10px; color:white; border-radius:5px;">
            Cart (<?= array_sum($cart) ?>)
        </span>
        |
        <a href="logout.php" style="color:red;">Logout</a>
    </div>
</header>

<div class="container">
    <h2>Your Cart</h2>
    <p>Current Date: <?= date('l, F j, Y \a\t h:i:s A') ?></p>

    <?php if ($cartDetails): ?>
        <form method="POST">
            <table>
                <tr>
                    <th>Event</th>
                    <th>Date</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Total</th>
                </tr>
                <?php foreach ($cartDetails as $event): ?>
                    <tr>
                        <td><?= htmlspecialchars($event['name']) ?></td>
                        <td><?= $event['date'] ?></td>
                        <td><?= $event['quantity'] ?></td>
                        <td><?= $event['price'] ?> SR</td>
                        <td><?= $event['subtotal'] ?> SR</td>
                    </tr>
                <?php endforeach; ?>
            </table>
            <div class="total"><strong>Total:</strong> <?= number_format($totalPrice, 2) ?> SR</div>
            <button type="submit" name="reserve" class="btn">Reserve Tickets</button>
        </form>
    <?php else: ?>
        <p>Your cart is empty.</p>
    <?php endif; ?>
</div>

<footer>
    &copy; <?= date("Y") ?> Event Booking System
</footer>

</body>
</html>
