<?php
require_once 'Database.php';

class Event {
    private $conn;
    private $table_name = "events";

    public $id;
    public $user_id;
    public $name;
    public $description;
    public $date_time;
    public $location;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Create a new event
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " (user_id, name, description, date_time, location) VALUES (?, ?, ?, ?, ?)";

        $stmt = $this->conn->prepare($query);

        // Clean data
        $this->user_id = htmlspecialchars(strip_tags($this->user_id));
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->date_time = htmlspecialchars(strip_tags($this->date_time));
        $this->location = htmlspecialchars(strip_tags($this->location));

        // Bind data
        $stmt->bind_param("issss", $this->user_id, $this->name, $this->description, $this->date_time, $this->location);

        // Execute query
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Read events by user ID
    public function read() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE user_id = ? ORDER BY date_time ASC";

        $stmt = $this->conn->prepare($query);

        // Clean data
        $this->user_id = htmlspecialchars(strip_tags($this->user_id));

        // Bind data
        $stmt->bind_param("i", $this->user_id);

        // Execute query
        $stmt->execute();

        return $stmt->get_result();
    }

    // Update an event by ID
    public function update() {
    $query = "UPDATE " . $this->table_name . " SET name = ?, description = ?, date_time = ?, location = ? WHERE ID = ?";

    $stmt = $this->conn->prepare($query);

    // Clean data
    $this->name = htmlspecialchars(strip_tags($this->name));
    $this->description = htmlspecialchars(strip_tags($this->description));
    $this->date_time = htmlspecialchars(strip_tags($this->date_time));
    $this->location = htmlspecialchars(strip_tags($this->location));
    $this->id = htmlspecialchars(strip_tags($this->id));
    $this->user_id = htmlspecialchars(strip_tags($this->user_id));

    // Bind data
    $stmt->bind_param("ssssi", $this->name, $this->description, $this->date_time, $this->location, $this->id);

    // Execute query
    if ($stmt->execute()=== True) {
        
        return true;
    }
    return false;
}

    
    // Delete an event by ID
    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ? AND user_id = ?";

        $stmt = $this->conn->prepare($query);

        // Clean data
        $this->id = htmlspecialchars(strip_tags($this->id));
        $this->user_id = htmlspecialchars(strip_tags($this->user_id));

        // Bind data
        $stmt->bind_param("ii", $this->id, $this->user_id);

        // Execute query
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

}
?>
