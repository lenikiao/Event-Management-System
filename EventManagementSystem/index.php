<?php
require_once 'classes/Session.php';

// Start the session
Session::start();
$user_id = Session::get('user_id');
if (!$user_id) {
    header("Location: login.php");
    exit;
}
// Include database connection
require_once 'classes/Database.php';

// Initialize $events as an empty array to avoid warnings
$events = [];

// Create a new Database instance and fetch the events
try {
    $database = new Database();
    $conn = $database->conn;

    // Assuming you have a table 'events' with a column 'user_id'
    $stmt = $conn->prepare("SELECT name, description, date_time, location FROM events WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Fetch all events as an associative array
    $events = $result->fetch_all(MYSQLI_ASSOC);

    $stmt->close();
} catch (Exception $e) {
    $error_message = "Failed to fetch events: " . $e->getMessage();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Management System</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            margin: 0;
            padding: 0;
        }
        .header {
            background-color: white;
            border-bottom: 1px solid #ddd;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000;
        }
        .header .logo {
            font-size: 24px;
            font-weight: bold;
        }
        .header .nav-buttons {
            display: flex;
            gap: 15px;
        }
        .header .nav-buttons a {
            padding: 10px 20px;
            text-decoration: none;
            border: 2px solid #4CAF50;
            border-radius: 20px;
            transition: background-color 0.3s, color 0.3s;
            font-weight: bold;
        }
        .header .nav-buttons a:hover {
            background-color: #4CAF50;
            color: white;
        }
        .header .nav-buttons .logout-btn {
            background-color: #4CAF50;
            color: white;
        }
        .container {
            padding: 100px 20px 20px; /* Adjusted to account for the fixed header */
        }
        h1 {
            color: #333;
            font-size: 32px;
        }
        .events-list {
            list-style-type: none;
            padding: 0;
        }
        .events-list li {
            background: white;
            margin: 10px 0;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .events-list li:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0,0,0,0.2);
        }
        .events-list li h2 {
            margin: 0 0 10px;
            font-size: 24px;
            color: #4CAF50;
        }
        .events-list li p {
            margin: 5px 0;
            font-size: 16px;
            color: #555;
        }
        .error {
            color: red;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">Event Management System</div>
        <div class="nav-buttons">
            <a href="events.php">Events</a>
            <a href="logout.php" class="logout-btn">Logout</a>
        </div>
    </div>

    <div class="container">
        <h2>Manage Your Events Effortlessly</h2>
        <?php if (isset($error_message)): ?>
            <p class="error"><?php echo $error_message; ?></p>
        <?php endif; ?>

        <ul class="events-list">
            <?php if (!empty($events)): ?>
                <?php foreach ($events as $event): ?>
                    <li>
                        <h2><?php echo htmlspecialchars($event['name']); ?></h2>
                        <p><?php echo htmlspecialchars($event['description']); ?></p>
                        <p><strong>Date and Time:</strong> <?php echo htmlspecialchars($event['date_time']); ?></p>
                        <p><strong>Location:</strong> <?php echo htmlspecialchars($event['location']); ?></p>
                    </li>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No events found.</p>
            <?php endif; ?>
        </ul>
    </div>
</body>
</html>
