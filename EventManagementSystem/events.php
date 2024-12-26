                                                                                                     <?php
require_once 'classes/Database.php';
require_once 'classes/Event.php';
require_once 'classes/Session.php';

Session::start();
$user_id = Session::get('user_id');

if (!$user_id) {
    header("Location: index.php");
    exit;
}

$database = new Database();
$db = $database->conn;

$event = new Event($db);
$event->user_id = $user_id;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['create'])) {
        $event->name = $_POST['name'];
        $event->description = $_POST['description'];
        $event->date_time = $_POST['date_time'];
        $event->location = $_POST['location'];

        if ($event->create()) {
            echo "Event created successfully.";
        } else {
            echo "Event creation failed.";
        }
    } elseif (isset($_POST['submitBtn'])) {
        $eventId = trim(filter_var($_POST['eventId'], FILTER_SANITIZE_STRING));
        if (!empty($eventId)) {
            $events->delete($eventId);
        } else {
            echo "Event ID is missing or invalid.";
            echo $eventId ;
        }
        return;
    } elseif (isset($_POST['edit'])) {
        $event ->id = $_POST['id'];
        $event ->name= $_POST['name'];
        $event->description = $_POST['description'];
        $event->date_time = $_POST['date_time'];
        $event->location = $_POST['location'];

        if ($event->update()) {//MAKE UODATE FUNCTION IN EVENT CLASS
            echo "Event updated successfully.";
        } else {
            echo "Event update failed.";
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['delete'])) {
    $event->id = $_GET['delete'];
    if ($event->delete()) {
        echo "Event deleted successfully." ;
    } else {
        echo "Event deletion failed.";
    }
}

$stmt = $event->read();
$events = $stmt->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Events</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
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
            max-width: 1200px;
            margin: 100px auto 50px auto; /* Adjusted to account for the fixed header */
            padding: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            display: grid;
            grid-template-columns: 1fr 2fr;
            gap: 20px;
        }
        .form-container {
            display: flex;
            flex-direction: column;
        }
        .form-container h2 {
            margin: 0;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        form label {
            margin-top: 10px;
        }
        form input[type="text"], form input[type="datetime-local"], form textarea {
            margin-bottom: 10px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        form input[type="submit"] {
            padding: 10px;
            color: white;
            background-color: #5cb85c;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        form input[type="submit"]:hover {
            background-color: #4cae4c;
        }
        .events-list-container h2 {
            margin-top: 0;
        }
        .events-list {
            list-style-type: none;
            padding: 0;
        }
        .events-list li {
            background: #f9f9f9;
            margin: 10px 0;
            padding: 10px;
            border-left: 5px solid #5cb85c;
            border-radius: 4px;
            position: relative;
        }
        .events-list li h3 {
            margin: 0;
        }
        .events-list li p {
            margin: 5px 0;
        }
        .events-list li .actions {
            position: absolute;
            right: 10px;
            top: 10px;
        }
        .events-list li .actions button {
            margin-left: 5px;
            padding: 5px 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .events-list li .actions .edit {
            background-color: #f0ad4e;
            color: white;
        }
        .events-list li .actions .delete {
            background-color: #d9534f;
            color: white;
        }
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
        }
        .modal-content {
            background: white;
            padding: 20px;
            border-radius: 8px;
            width: 400px;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">Event Management System</div>
        <div class="nav-buttons">
            <a href="index.php">Home</a>
            <a href="logout.php" class="logout-btn">Logout</a>
        </div>
    </div>

    <div class="container">
        <div class="form-container">
            <h2>Create Event</h2>
            <form method="post">
                <label for="name">Event Name:</label>
                <input type="text" name="name" id="name" required>
                <label for="description">Description:</label>
                <textarea name="description" id="description" required></textarea>
                <label for="date_time">Date and Time:</label>
                <input type="datetime-local" name="date_time" id="date_time" required>
                <label for="location">Location:</label>
                <input type="text" name="location" id="location" required>
                <input type="submit" name="create" value="Create Event">
            </form>
        </div>

        <div class="events-list-container">
            <h2>My Events</h2>
            <ul class="events-list">
                <?php foreach ($events as $event) : ?>
                    <li>
                        <h3><?php echo htmlspecialchars($event['name']); ?></h3>
                        <p><?php echo htmlspecialchars($event['description']); ?></p>
                        <p>Date and Time: <?php echo htmlspecialchars($event['date_time']); ?></p>
                        <p>Location: <?php echo htmlspecialchars($event['location']); ?></p>
                        <div class="actions">
                            <button class="edit" onclick="openEditModal(<?php echo htmlspecialchars(json_encode($event)); ?>)">Edit</button>
                            <button class="delete" onclick="deleteEvent(<?php echo htmlspecialchars(json_encode($event)); ?>)">Delete</button>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>

    <div class="modal" id="editModal">
        <div class="modal-content">
            <h2>Edit Event</h2>
            <form method="post">
                <input type="hidden" name="id" id="editId">
                <label for="editName">Event Name:</label>
                <input type="text" name="name" id="editName" required>
                <label for="editDescription">Description:</label>
                <textarea name="description" id="editDescription" required></textarea>
                <label for="editDateTime">Date and Time:</label>
                <input type="datetime-local" name="date_time" id="editDateTime" required>
                <label for="editLocation">Location:</label>
                <input type="text" name="location" id="editLocation" required>
                <input type="submit" name="edit" value="Save Changes">
            </form>
        </div>
    </div>

    <script>
        function openEditModal(event) {
            document.getElementById('editId').value = event.ID;
            document.getElementById('editName').value = event.name;
            document.getElementById('editDescription').value = event.description;
            document.getElementById('editDateTime').value = event.date_time;
            document.getElementById('editLocation').value = event.location;
            document.getElementById('editModal').style.display = 'flex';
        }

        function deleteEvent(event) {
            if (confirm('Are you sure you want to delete this event?')) {
                window.location.href = '?delete=' + event.ID;
            }
        }

        window.onclick = function(event) {
            if (event.target == document.getElementById('editModal')) {
                document.getElementById('editModal').style.display = 'none';
            }
        }
    </script>
</body>
</html>