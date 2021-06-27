<?php
    session_start();

    ini_set('display_errors', 1);
    error_reporting(E_ALL);

    define("IN_INDEX", 1);

    include("config.inc.php");
    include("functions.inc.php");

    require __DIR__ . '/vendor/autoload.php';

    #--------------------WYLOGOWANIE--------------------
    if (isset($_GET['logout']) && $_GET['logout'] == 1) {
        unset($_SESSION['id'], $_SESSION['email']);

    }
?>

<!DOCTYPE html>
<html lang="en" class="has-sticky-footer">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="canonical" href="https://getbootstrap.com/docs/5.0/examples/sign-in/">
    <!-- nasz własny CSS -->
    <link href="/style.css" rel="stylesheet">

    <link rel="shortcut icon" type="image/x-icon" href="/favicon.ico"/>

    <meta name="theme-color" content="#7952b3">

    <script src="https://www.google.com/recaptcha/api.js" async defer></script>

    <title>To Do Calendar</title>

</head>

<?php
    #--------------------ŁADOWANIE-STRON--------------------
    $allowed_pages = ['signin', 'signup'];

    if (isset($_SESSION['id'], $_SESSION['email'])) {
        include('main.php');
    } elseif (isset($_GET['page']) && in_array($_GET['page'], $allowed_pages)) {
        include($_GET['page'] . '.php');
    } else {
        include('signin.php');
    }
?>

<footer class="page-footer font-small footer-copyright text-center py-3">
    <span>Błaszczyna © Kwak © Węgrzyn</span>
</footer>

<!-- nasz własny Javascript -->
<script>

    for (const element of document.getElementsByClassName('list-group-item-lepszy-description')) {
        const content = element.getElementsByClassName("list-group-item-lepszy-description-zawartosc")[0]
        element.onclick = () => {
            content.style.display = content.style.display === 'none' ? 'block' : 'none'
        }
    }

</script>

<!-- Bootstrap -->
<!-- JavaScript Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

</body>

</html>
