<?php
session_start();
require_once '../includes/config.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: admin.php');
    exit();
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $date = $_POST['date'];
    $location = $_POST['location'];
    $price = $_POST['price'];
    $max_tickets = $_POST['max_tickets'];
    $description = $_POST['description'];

    // Handle image upload
    if (!empty($_FILES['image']['name'])) {
        $imageName = basename($_FILES['image']['name']);
        $imagePath = "../images/" . $imageName;
        move_uploaded_file($_FILES['image']['tmp_name'], $imagePath);
    } else {
        $imageName = null;
    }

    if ($name && $date && $location && $price && $max_tickets && $imageName && $description) {
        $stmt = $conn->prepare("INSERT INTO events (name, date, location, price, max_tickets, image, description) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$name, $date, $location, $price, $max_tickets, $imageName, $description]);

        $success = "Event added successfully!";
        header("Location: manageEvents.php");
        exit();
    } else {
        $error = "All fields are required.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Event</title>
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
        <h2>Add New Event</h2>

        <?php if ($error): ?>
            <p style="color:red;"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">
            <label>Event Name:</label>
            <input type="text" name="name" required>

            <label>Event Date & Time:</label>
            <input type="datetime-local" name="date" required>

            <label>Location:</label>
            <input type="text" name="location" required>

            <label>Ticket Price (SR):</label>
            <input type="number" name="price" step="0.01" required>

            <label>Max Tickets Available:</label>
            <input type="number" name="max_tickets" required>

            <label>Event Image:</label>
            <input type="file" name="image" required>

            <label>Description:</label>
            <textarea name="description" rows="4" required></textarea>

            <button type="submit">Add Event</button>
        </form>
    </div>
</div>
</body>
</html>
