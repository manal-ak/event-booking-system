<?php
session_start();
require_once '../includes/config.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: admin.php');
    exit();
}

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

$error = '';
$success = '';

// Handle form submission
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
        $imageName = $event['image']; // keep old image if not uploading new one
    }

    if ($name && $date && $location && $price && $max_tickets) {
        $stmt = $conn->prepare("UPDATE events SET name = ?, date = ?, location = ?, price = ?, max_tickets = ?, image = ?, description = ? WHERE id = ?");
        $stmt->execute([$name, $date, $location, $price, $max_tickets, $imageName, $description, $event_id]);

        $success = "Event updated successfully!";
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
    <title>Edit Event</title>
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
        <h2>Edit Event</h2>

        <?php if ($error): ?>
            <p style="color:red;"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">
            <label>Event Name:</label>
            <input type="text" name="name" value="<?= htmlspecialchars($event['name']) ?>" required>

            <label>Event Date:</label>
            <input type="datetime-local" name="date" value="<?= date('Y-m-d\TH:i', strtotime($event['date'])) ?>" required>

            <label>Location:</label>
            <input type="text" name="location" value="<?= htmlspecialchars($event['location']) ?>" required>

            <label>Ticket Price (SR):</label>
            <input type="number" name="price" step="0.01" value="<?= $event['price'] ?>" required>

            <label>Maximum Tickets:</label>
            <input type="number" name="max_tickets" value="<?= $event['max_tickets'] ?>" required>

            <label>Event Image:</label>
            <input type="file" name="image">
            <p>Current Image: <?= htmlspecialchars($event['image']) ?></p>

            <label>Description:</label>
            <textarea name="description" rows="4"><?= htmlspecialchars($event['description'] ?? '') ?></textarea>

            <button type="submit">Update Event</button>
        </form>
    </div>
</div>

</body>
</html>
