<?php
$success = '';
$error = '';

session_start();
require_once '../includes/config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$name = $_SESSION['name'];
$cart = $_SESSION['cart'] ?? [];

// Get event ID from query
$event_id = $_GET['id'] ?? null;
if (!$event_id) {
    echo "Event not found.";
    exit();
}

// Fetch event details
$stmt = $conn->prepare("SELECT * FROM events WHERE id = ?");
$stmt->execute([$event_id]);
$event = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$event) {
    echo "Invalid event ID.";
    exit();
}

// Flags for popup
$askClearCart = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $quantity = (int) ($_POST['quantity'] ?? 0);

    if ($quantity < 1) {
        $error = "Please select a valid number of tickets.";
    } elseif ($quantity > $event['max_tickets']) {
        $error = "Cannot book more than available tickets.";
    } else {
        if (!empty($cart)) {
            $existingEventId = array_keys($cart)[0];
            if ($existingEventId != $event_id) {
                // Set a flag to ask user
                $askClearCart = true;
                $_SESSION['pending_event'] = $event_id;
                $_SESSION['pending_quantity'] = $quantity;
            } else {
                // Same event → update cart
                $_SESSION['cart'][$event_id] = $quantity;
                $success = "Cart updated successfully!";
            }
        } else {
            // Cart empty → add new event
            $_SESSION['cart'][$event_id] = $quantity;
            $success = "Added to cart successfully!";
        }
    }
}

// Handle clear cart
if (isset($_GET['action']) && $_GET['action'] == 'clear_and_add') {
    if (isset($_SESSION['pending_event']) && isset($_SESSION['pending_quantity'])) {
        $_SESSION['cart'] = [];
        $_SESSION['cart'][$_SESSION['pending_event']] = $_SESSION['pending_quantity'];
        unset($_SESSION['pending_event'], $_SESSION['pending_quantity']);
        $success = "Cart cleared and new event added!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($event['name']) ?> - Event Booking</title>
    <style>
        * { box-sizing: border-box; }
        body { font-family: Arial; margin: 0; background: #f4f4f4; }
        header, footer {
            background: #111; color: white;
            padding: 15px 20px;
        }
        .header-right { float: right; }
        .container {
            padding: 30px; max-width: 1000px; margin: auto;
            display: flex; gap: 30px;
        }
        .event-img {
            flex: 1;
        }
        .event-img img {
            width: 100%; border-radius: 10px;
        }
        .event-details {
            flex: 2;
            background: white; padding: 20px; border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        form {
            margin-top: 20px;
        }
        select, button {
            padding: 10px; width: 100%;
            margin-top: 10px; border-radius: 5px;
        }
        .success { color: green; }
        .error { color: red; }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>
<body>
<?php if ($askClearCart): ?>
<script>
Swal.fire({
  title: 'Another Event in Your Cart!',
  text: "Do you want to discard your current cart and add this new event?",
  icon: 'warning',
  showCancelButton: true,
  confirmButtonColor: '#28a745',
  cancelButtonColor: '#d33',
  confirmButtonText: 'Yes, discard and add',
  cancelButtonText: 'No, keep current'
}).then((result) => {
  if (result.isConfirmed) {
    window.location.href = "event.php?id=<?= $event_id ?>&action=clear_and_add";
  }
});
</script>
<?php endif; ?>



<header>
    <a href="home.php" style="color:white; text-decoration:none; font-weight:bold;">
        Event Booking System
    </a>
    <div class="header-right">
        Welcome, <?= htmlspecialchars($name) ?> |
        <a href="cart.php" 
   style="background:green; 
          padding:5px 10px; 
          color:white; 
          border-radius:5px; 
          text-decoration:none;">
    Cart (<?= array_sum($_SESSION['cart'] ?? []) ?>)
</a>
        |
        <a href="logout.php" style="color:red;">Logout</a>
    </div>
</header>

<div class="container">
    <div class="event-img">
        <img src="images/<?= $event['image'] ?>" alt="<?= $event['name'] ?>">
    </div>
    <div class="event-details">
        <h2><?= htmlspecialchars($event['name']) ?></h2>
        <p><strong>Date:</strong> <?= $event['date'] ?></p>
        <p><strong>Location:</strong> <?= $event['location'] ?></p>
        <p><strong>Price per ticket:</strong> <?= $event['price'] ?> SR</p>
        <p><strong>Available tickets:</strong> <?= $event['max_tickets'] ?></p>
        <p><?= $event['description'] ?? 'Join us for an unforgettable experience.' ?></p>

        <form method="POST">
            <label>Number of Tickets:</label>
            <select name="quantity" required>
                <option value="">Select</option>
                <?php for ($i = 1; $i <= $event['max_tickets']; $i++): ?>
                    <option value="<?= $i ?>"><?= $i ?></option>
                <?php endfor; ?>
            </select>
            <button type="submit">Add to Cart</button>
        </form>

        <?php if ($success): ?>
            <p class="success"><?= $success ?></p>
        <?php elseif ($error): ?>
            <p class="error"><?= $error ?></p>
        <?php endif; ?>
    </div>
</div>

<footer>
    &copy; <?= date("Y") ?> Event Booking System
</footer>

</body>
</html>
