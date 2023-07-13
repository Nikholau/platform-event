<?php
session_start();
require_once '../classes/User.php';
require_once '../classes/Registration.php';

$user = null;
if (isset($_SESSION['user'])) {
    $user = unserialize($_SESSION['user']);
}

$registeredEvents = [];
if ($user) {
    $registeredEvents = Registration::getRegisteredEvents($user->getId());
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Perfil de Usuário</title>
    <link rel="stylesheet" href="../css/user_profile.css">
</head>

<body>
    <header>
        <h1>Platform Event</h1>
        <nav>
            <ul>
                <li><a href="./index.php"><img class="home-page" src="../assets/home.png" title="Home"></a></li>
                <?php if ($user instanceof User && ($user->getUserType() === 'admin' || $user->getUserType() === 'grant_admin')): ?>
                    <li><a class="register-event" href="../pages/add_event.php">Adicionar Evento</a></li>
                <?php endif; ?>
                <?php if ($user instanceof User): ?>
                    <li><a class="register-event" href="../pages/process_registration.php">Registrar evento</a></li>
                    <li><a href="../pages/user_profile.php"><img class="perfil-img" src="../assets/perfil.png"
                                title="Profile"></a>
                    </li>
                    <li><a href="../services/logout.php"><img class="leave-img" src="../assets/sair.png"
                                title="SignOut"></a></li>
                <?php else: ?>
                    <li><a href="../pages/user_login.php">Login</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>



    <section>
        <h2>Perfil de Usuário</h2>
        <div class="info-users">
            <?php if ($user): ?>
                <p><span>Name:</span>
                    <?php echo $user->getName(); ?>
                </p>
                <p><span>Email:</span>
                    <?php echo $user->getEmail(); ?>
                </p>
                <p><span>Tipo de usuário:</span>
                    <?php echo $user->getUserType(); ?>
                </p>

                <?php if ($user instanceof User && $user->getUserType() === 'grant_admin'): ?>
                    <a href="./admin.php"><button class="btn-typeuser">Admin Pannel</button></a>
                <?php endif; ?>
                <?php if ($user instanceof User && $user->getUserType() === 'admin'): ?>
                    <a href="./event_list.php"><button class="btn-typeuser">Event List</button></a>
                <?php endif; ?>
                <div class="imagem">
                    <img class="img-user" src="../assets/user.png" title="User Image" alt="user">
                </div>
            </div>
            <br>
            <h3>Eventos Registrados:</h3>
            <?php if (!empty($registeredEvents)): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Título do evento</th>
                            <th>Data do evento</th>
                            <th>Reviews</th>
                            <th>Deletar evento</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($registeredEvents as $event): ?>
                            <tr>
                                <td>
                                    <?php echo $event->getTitle(); ?>
                                </td>
                                <td>
                                    <?php echo $event->getDate(); ?>
                                </td>
                                <td><a id="review" href="./reviews.php?event_id=<?php echo $event->getId(); ?>">Reviews</a></td>
                                <td>
                                    <form action="../services/delete_event.php" method="post">
                                        <input type="hidden" name="event_id" value="<?php echo $event->getId(); ?>">
                                        <button class="btn-deleteevent" type="submit" title="Delete"><img class="img-delete"
                                                src="../assets/delete.png" title="Delete Event" alt=""></button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>Sem eventos registrados</p>
            <?php endif; ?>
        <?php else: ?>
            <p>You are not logged in.</p>
        <?php endif; ?>
    </section>
</body>

</html>