<?php
session_start();
require_once '../includes/config.php';

// Check admin login
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: admin.php');
    exit();
}

// Get event ID from URL
$event_id = $_GET['id'] ?? null;

if (!$event_id) {
    header('Location: manageEvents.php');
    exit();
}

// Fetch the event
$stmt = $conn->prepare("SELECT * FROM events WHERE id = ?");
$stmt->execute([$event_id]);
$event = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$event) {
    echo "Event not found.";
    exit();
}

// Check if there are any bookings
$stmt = $conn->prepare("SELECT COUNT(*) FROM bookings WHERE event_id = ?");
$stmt->execute([$event_id]);
$bookings_count = $stmt->fetchColumn();

// 🛠️ Handle the delete POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_delete'])) {
    if ($bookings_count == 0) {
        // Delete the event
        $stmt = $conn->prepare("DELETE FROM events WHERE id = ?");
        $stmt->execute([$event_id]);
    }
    header('Location: manageEvents.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Delete Event</title>
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
        <h2>Delete Event</h2>

        <div class="delete-confirmation">
            <p>Are you sure you want to delete the following event?</p>
            <p><strong><?= htmlspecialchars($event['name']) ?></strong></p>
            <p>Date: <?= $event['date'] ?></p>
            <p>Location: <?= htmlspecialchars($event['location']) ?></p>
            <p>Bookings: <?= $bookings_count ?></p>

            <?php if ($bookings_count > 0): ?>
                <p style="color:red;">This event has bookings and cannot be deleted.</p>
                <a href="manageEvents.php" class="delete-button" style="background-color:gray;">Back</a>
            <?php else: ?>
                <form method="POST">
                    <button type="submit" name="confirm_delete" class="delete-button">Yes, Delete this Event</button>
                </form>
            <?php endif; ?>
        </div>
    </div>
</div>
</body>
</html>
