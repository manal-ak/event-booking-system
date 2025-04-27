<?php
session_start();

// Check admin login
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin.php");
    exit();
}

// Connect to database
require_once '../includes/config.php';

// Fetch events
$stmt = $conn->query("SELECT * FROM events ORDER BY date ASC");
$events = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Events</title>
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
            <h2>Manage Events</h2>
            <table>
                <thead>
                    <tr>
                        <th>Event Name</th>
                        <th>Date</th>
                        <th>Location</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
    <?php foreach ($events as $event): ?>
        <tr>
            <td><?= htmlspecialchars($event['name']) ?></td>
            <td><?= $event['date'] ?></td>
            <td><?= htmlspecialchars($event['location']) ?></td>
            <td class="action-links">
                <a href="viewEvent.php?id=<?= $event['id'] ?>">View</a>
                <a href="editEvent.php?id=<?= $event['id'] ?>">Edit</a>
                <a href="deleteEvent.php?id=<?= $event['id'] ?>">Delete</a>
            </td>
        </tr>
    <?php endforeach; ?>
</tbody>

            </table>
        </div>
    </div>
    

    <script src="manageEvents.js"></script>
</body>

</html>