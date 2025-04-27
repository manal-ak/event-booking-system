<?php
session_start();
require_once '../includes/config.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: admin.php');
    exit();
}

// Get the event ID from URL
$event_id = $_GET['id'] ?? null;

if (!$event_id) {
    header('Location: manageEvents.php');
    exit();
}

// Fetch the event from database
$stmt = $conn->prepare("SELECT * FROM events WHERE id = ?");
$stmt->execute([$event_id]);
$event = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$event) {
    echo "Event not found.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Event</title>
    <link rel="stylesheet" href="../event_booking_system.css">
</head>
<body>

<div class="admin-panel">
    <div class="side-menu">
        <h2>Admin Panel</h2>
        <ul>
            <li><a href="manageEvents.php">Manage Events</a></li>
            <li><a href="addEvent.php">Add Event</a></li>
            <li><a href="viewBookings.php">View Bookings</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>

    <div class="main-section">
        <h2>Event Details</h2>

        <div class="event-details" style="text-align:left;">
            <img src="../images/<?= htmlspecialchars($event['image']) ?>" alt="Event Image" style="max-width:400px; margin-top:10px; border-radius:10px;">
            
            <h2 style="margin-top:15px;"><?= htmlspecialchars($event['name']) ?></h2>

            <p><strong>Date & Time:</strong> <?= $event['date'] ?></p>
            <p><strong>Location:</strong> <?= htmlspecialchars($event['location']) ?></p>
            <p><strong>Ticket Price:</strong> <?= $event['price'] ?> SR</p>
            <p><strong>Available Tickets :</strong> <?= $event['max_tickets'] ?></p>
            <p><strong>Description:</strong> <?= nl2br(htmlspecialchars($event['description'] ?? 'No description.')) ?></p>
        </div>

        <a href="manageEvents.php" style="display:inline-block; margin-top:20px; padding:10px 20px; background:#007bff; color:white; border-radius:5px; text-decoration:none;">Back to Events</a>
    </div>
</div>

</body>
</html>
