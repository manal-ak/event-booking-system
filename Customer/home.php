<?php
session_start();
require_once '../includes/config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$name = $_SESSION['name'] ?? 'User';

// Dummy cart count for now (we’ll update this from real cart session later)
$cartCount = $_SESSION['cart'] ?? [];
$cartItems = count($cartCount);

// Fetch events
$stmt = $conn->query("SELECT * FROM events");
$events = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Event Booking System</title>
    <style>
        * { box-sizing: border-box; }
        body {
            font-family: Arial, sans-serif;
            margin: 0; padding: 0; background: #f2f2f2;
        }
        header {
            background: #111; color: white;
            padding: 15px 20px; display: flex;
            justify-content: space-between; align-items: center;
        }
        .brand {
            font-size: 18px; font-weight: bold;
        }
        .header-right {
            display: flex; align-items: center; gap: 10px;
        }
        .cart {
            background: green; color: white; padding: 5px 10px;
            border-radius: 5px;
        }
        .logout {
            background: red; color: white; padding: 5px 10px;
            border-radius: 5px;
            text-decoration: none;
        }
        .welcome {
            margin-right: 10px;
        }
        h2 {
            padding: 20px;
            margin: 0;
        }

        .card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            position: relative;
            transition: 0.3s;
        }
        .card img {
            width: 100%; height: 180px; object-fit: cover;
        }
        .card-content {
            padding: 15px;
        }
        .card-content h3 {
            margin: 0 0 10px;
        }
        .book-btn {
            position: absolute;
            bottom: 0;
            width: 100%;
            text-align: center;
            background: green;
            color: white;
            padding: 10px;
            display: none;
            cursor: pointer;
        }
        .event-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr); /* Three equal columns */
    gap: 20px; /* Space between cards */
    padding: 20px;
        }

        .event-card {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        .card:hover .book-btn {
            display: block;
        }
        footer {
            background: #111; color: white;
            text-align: center;
            padding: 15px;
        }

        @media (max-width: 768px) {
            header {
                flex-direction: column;
                align-items: flex-start;
            }
        }
        @media (max-width: 992px) {
            .event-grid {
            grid-template-columns: repeat(2, 1fr); /* 2 events per row on medium screens */
            }
        }

        @media (max-width: 600px) {
            .event-grid {
                grid-template-columns: 1fr; /* 1 event per row on small screens */
            }
        }
    </style>
</head>
<body>

<header>
    <div class="brand"> <a href="home.php" style="color:white; text-decoration:none; font-weight:bold;">
        Event Booking System
    </a></div>
    <div class="header-right">
        <div class="welcome">Welcome, <?= htmlspecialchars($name) ?></div>
        <div class="cart"><a href="cart.php" 
   style="background:green; 
          padding:5px 10px; 
          color:white; 
          border-radius:5px; 
          text-decoration:none;">
    Cart (<?= array_sum($_SESSION['cart'] ?? []) ?>)
</a></div>
        <a href="logout.php" class="logout">Logout</a>
    </div>
</header>

<h2>Available Events</h2>
<div class="event-grid">
    <?php foreach ($events as $event): ?>
        <div class="card">
        <img src="../images/<?= $event['image'] ?>">
            <div class="card-content">
                <h3><?= htmlspecialchars($event['name']) ?></h3>
                <p><?= $event['date'] ?></p>
            </div>
            <div class="book-btn" onclick="window.location.href='event.php?id=<?= $event['id'] ?>'">Book Now</div>
        </div>
    <?php endforeach; ?>
</div>

<footer>
    &copy; <?= date("Y") ?> Event Booking System
</footer>

</body>
</html>
