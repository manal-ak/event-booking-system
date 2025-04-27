<?php
// Connect to database
require_once '../includes/config.php';

try {
    // Fetch all events from the database
    $stmt = $conn->query("SELECT * FROM events");
    $events = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>

<!-- Basic HTML to display the events -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Event Booking</title>
    <style>
        .event {
            border: 1px solid #ccc;
            border-radius: 10px;
            padding: 16px;
            margin-bottom: 16px;
            width: 300px;
        }
    </style>
</head>
<body>
    <h1>Available Events</h1>

    <?php if (count($events) > 0): ?>
        <?php foreach ($events as $event): ?>
            <div class="event">
                <h2><?= htmlspecialchars($event['name']) ?></h2>
                <p><strong>Date:</strong> <?= $event['date'] ?></p>
                <p><strong>Location:</strong> <?= $event['location'] ?></p>
                <p><strong>Price:</strong> <?= $event['price'] ?> SR</p>
                <p><strong>Tickets Available:</strong> <?= $event['max_tickets'] ?></p>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No events found.</p>
    <?php endif; ?>
</body>
</html>
