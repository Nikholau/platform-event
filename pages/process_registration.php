<?php
ini_set("display_errors", 1);
ini_set("error_reporting", E_ALL);
ini_set('xdebug.overload_var_dump', 1);

session_start();
require_once '../classes/User.php';
require_once '../classes/Category.php';
require_once '../classes/Event.php';
require_once '../classes/Registration.php';

$user = null;
if (isset($_SESSION['user'])) {
    $user = unserialize($_SESSION['user']);
    $userId = $user->getId();
} else {
    header('Location: user_login.php');
    exit();
}

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nameEvent = $_POST['nameEvent'];
    $descriptionEvent = $_POST['descriptionEvent'];
    $dateEvent = $_POST['dateEvent'];
    $timeEvent = $_POST['timeEvent'];
    $location = $_POST['location'];
    $price = $_POST['price'];
    $urlImageEvent = $_POST['urlImageEvent'];
    
    $registrationId = Registration::createEvent($nameEvent, $descriptionEvent, $dateEvent, $timeEvent, $location, $price, $urlImageEvent);

    if ($registrationId) {
        header('Location: user_profile.php');
        exit();
    } else {
        exit();
    }
}

function debug(...$args){
    print("<pre>" . print_r($args, true) . "</pre>");
    die();
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Register</title>
    <link rel="stylesheet" href="../css/process_registration.css">
</head>

<body>
    <header>
        <h1></h1>
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

    <section2>
        <h2>Registro</h2>
        <form class="categoria" id="formEvent" method="POST" action="">
            <div class="form-categories">
                <label class="label-categoria" for="category">Categoria:</label>
                <?php
                $categories = Category::getAll();

                if (!empty($categories)) {
                    echo "<select name='category' id='category'>";
                    echo "<option value=''>Selecione uma categoria</option>";

                    foreach ($categories as $category) {
                        echo "<option value='" . $category->getId() . "'>" . $category->getName() . "</option>";
                    }

                    echo "</select>";
                } else {
                    echo "<p>Nenhuma categoria encontrada.</p>";
                }
                ?>
            </div>

            <div id="eventsContainer">

            </div>
            <div class="">
                <input class="space-form-text-3" type="text" id="nameEvent" placeholder="Nome do evento">
                <input class="space-form-text-3" type="text" id="descriptionEvent" placeholder="Descrição do evento">
                <input class="space-form-text-3" type="date " id="dateEvent" placeholder="Data do evento">
                <input class="space-form-text-3" type="time" id="timeEvent" placeholder="Hora do evento">
                <input class="space-form-text-3" type="text" id="location" placeholder="Local do evento">
                <input class="space-form-text-3" type="text" id="price" placeholder="Preço do evento">
                <input class="space-form-text-3" type="text" id="urlImageEvent" placeholder="Url da imagem do evento">
            </div>
            <button type="submit" class="btn-registrar">Registrar</button>
        </form>
    </section2>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#category').change(function () {

                var categoryId = $(this).val();
                const formEvent = $('#formEvent');

                formEvent.find('input, select').forEach((elements) => console.log(elements))

                if (categoryId !== '') {
                    $.ajax({
                        url: '../services/get_events.php',
                        method: 'POST',
                        data: { category_id: categoryId },
                        success: function (response) {
                            $('#eventsContainer').html(response);
                        }
                    });
                } else {
                    $('#eventsContainer').html('');
                }
            });
            /*
            $(document).on('click', '.event-link', function (e) {
                e.preventDefault();
                var eventId = $(this).data('event-id');
                var eventDetails = $(this).siblings('.event-details');
                eventDetails.toggleClass('hidden');
                $('#event_id').val(eventId);
            });
            */

        });
    </script>

</body>

</html>