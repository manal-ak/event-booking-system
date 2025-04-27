<?php
session_start();
require_once '../includes/config.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: admin.php');
    exit();
}

// Fetch all bookings with customer and event information
$stmt = $conn->query("
    SELECT 
        users.name AS customer_name,
        users.email AS customer_email,
        bookings.booking_date AS booking_date,
        events.name AS event_name,
        events.date AS event_date,
        bookings.quantity,
        bookings.total_price
    FROM bookings
    JOIN users ON bookings.user_id = users.id
    JOIN events ON bookings.event_id = events.id
    ORDER BY bookings.booking_date DESC
");
$bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Bookings</title>
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
            <h2>All Bookings</h2>

            <table>
                <thead>
                    <tr>
                        <th>Customer Name</th>
                        <th>Customer Email</th>
                        <th>Booking Date</th>
                        <th>Event Name</th>
                        <th>Event Date</th>
                        <th>Tickets Booked</th>
                        <th>Total Price (SR)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($bookings): ?>
                        <?php foreach ($bookings as $booking): ?>
                            <tr>
                                <td><?= htmlspecialchars($booking['customer_name']) ?></td>
                                <td><?= htmlspecialchars($booking['customer_email']) ?></td>
                                <td><?= $booking['booking_date'] ?></td>
                                <td><?= htmlspecialchars($booking['event_name']) ?></td>
                                <td><?= $booking['event_date'] ?></td>
                                <td><?= $booking['quantity'] ?></td>
                                <td><?= $booking['total_price'] ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="7">No bookings found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
