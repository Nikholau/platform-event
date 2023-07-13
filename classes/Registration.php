<?php
require_once __DIR__ . '/../database/connection.php';
require_once __DIR__ . '/Event.php';

class Registration {
    private $id;
    private $userId;
    private $eventId;
    private $paymentStatus;

    public function __construct($userId, $eventId, $paymentStatus) {
        $this->userId = $userId;
        $this->eventId = $eventId;
        $this->paymentStatus = $paymentStatus;
    }

    // Getters
    public function getId() {
        return $this->id;
    }

    public function getUserId() {
        return $this->userId;
    }

    public function getEventId() {
        return $this->eventId;
    }

    public function getPaymentStatus() {
        return $this->paymentStatus;
    }

    // Setters
    public function setUserId($userId) {
        $this->userId = $userId;
    }

    public function setEventId($eventId) {
        $this->eventId = $eventId;
    }

    public function setPaymentStatus($paymentStatus) {
        $this->paymentStatus = $paymentStatus;
    }

    // Database interaction methods
    public function save() {
        try {
            $conn = getConnection();
            $stmt = $conn->prepare("INSERT INTO registrations (id, title, description, date, time, location, category_id, price, images) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("iss", $this->id, $this->title, $this->description,$this->date,$this->time,$this->location,$this->category_id,$this->price,$this->images);
            $stmt->execute();
            var_dump($stmt->affected_rows, $stmt->error);
            $this->id = $stmt->insert_id;
            $stmt->close();
            $conn->close();
        }
        catch(\Exception $e) {
            var_dump($e->getMessage());
            die();
        }
    }

    public static function getById($id) {
        $conn = getConnection();
        $stmt = $conn->prepare("SELECT * FROM registrations WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $registration = $result->fetch_assoc();
        $stmt->close();
        $conn->close();
        return $registration ? new Registration($registration['user_id'], $registration['event_id'], $registration['payment_status']) : null;
    }

    public static function createEvent($name, $description, $date, $time, $location, $categoryId, $price, $image) {
        $registration = new Registration($name, $description, $date, $time, $location, $categoryId, $price, $image);
        $registration->save();
        return $registration->getId();
    }

    public static function debug(...$args) {
        print("<pre>" . print_r($args, true) . "</pre>");
        die();
    }

    public static function getRegisteredEvents($userId) {
        $conn = getConnection();
        $stmt = $conn->prepare("SELECT e.id, e.title, e.description, e.date, e.time, e.location, e.category_id, e.price, e.images 
                                FROM events e 
                                INNER JOIN registrations r ON e.id = r.event_id 
                                WHERE r.user_id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $events = [];
        while ($event = $result->fetch_assoc()) {
            $events[] = new Event($event['id'], $event['title'], $event['description'], $event['date'], $event['time'], $event['location'], $event['category_id'], $event['price'], $event['images']);
        }
        $stmt->close();
        $conn->close();
        return $events;
    }

    public static function deleteById($eventId) {
        $conn = getConnection();
        $stmt = $conn->prepare("DELETE FROM registrations WHERE event_id = ?");
        $stmt->bind_param("i", $eventId);
        $stmt->execute();
        $stmt->close();
        $conn->close();
    }
}

?>
