<?php
session_start();

require_once '../class/User.php';

$user = null;
if (isset($_SESSION['user'])) {
    $user = unserialize($_SESSION['user']);
}

?>

<!DOCTYPE html>
<html>

<head>
    <title>Event Details</title>
    <link rel="stylesheet" type="text/css" href="../css/eventDetails.css">
</head>

<body>
    <header>
        <h1>Platform Event</h1>
        <nav>
            <ul>
                <li><a href="./index.php"><img class="home-page" src="../assets/home.png" title="Home"></a></li>
                <?php if ($user instanceof User && ($user->getUserType() === 'admin' || $user->getUserType() === 'grant_admin')): ?>
                    <li><a class="register-event" href="../pages/addEvent.php">Add Event</a></li>
                <?php endif; ?>
                <?php if ($user instanceof User): ?>
                    <li><a class="register-event" href="../pages/processRegistration.php">Registrar evento</a></li>
                    <li><a href="../pages/profileUser.php"><img class="perfil-img" src="../assets/perfil.png"
                                title="Profile"></a>
                    </li>
                    <li><a href="../services/logout.php"><img class="leave-img" src="../assets/sair.png"
                                title="SignOut"></a></li>
                <?php else: ?>
                    <li><a href="../pages/loginUser.php">Login</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>


    <section3 class="event-details">
        <?php
        require_once __DIR__ . '/../data/connection.php';
        require_once '../class/Event.php';


        if (isset($_GET['id'])) {
            $eventId = $_GET['id'];
            $event = Event::getById($eventId);

            if ($event) {
                echo "<h2>{$event->getTitle()}</h2>";
                echo "<img class='img-eventdetails' src='{$event->getImages()}' alt='Event Image'>";
                echo "<p>Description: {$event->getDescription()}</p>";
                echo "<p>Date: {$event->getDate()}</p>";
                echo "<p>Time: {$event->getTime()}</p>";
                echo "<p>Location: {$event->getLocation()}</p>";

                // Botão para redirecionar para processRegistration.php
                echo "<form  action='../pages/processRegistration.php' method='post'>";
                echo "<input type='hidden'  name='event_id' value='{$eventId}'>";
                echo "<input class='btn-subscription' type='submit' name='payment' value='Subscribe'>";
                echo "</form>";
            } else {
                echo "<p>Event not found.</p>";
            }
        } else {
            echo "<p>Invalid event ID.</p>";
        }
        ?>
    </section3>
    <br>

</body>

</html>