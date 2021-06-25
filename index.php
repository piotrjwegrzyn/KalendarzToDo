<?php
    session_start();

    ini_set('display_errors', 1);
    error_reporting(E_ALL);

    define("IN_INDEX", 1);

    include("config.inc.php");
    include("functions.inc.php");

    #--------------------BAZA-DANYCH--------------------
    if (isset($config) && is_array($config)) {
        try {
            $dbh = new PDO('mysql:host=' . $config['db_host'] . ';dbname=' . $config['db_name'] . ';charset=utf8mb4', $config['db_user'], $config['db_password']);
            $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            print "Nie mozna polaczyc sie z baza danych: " . $e->getMessage();
            exit();
        }
    } else {
        exit("Nie znaleziono konfiguracji bazy danych.");
    }

    #--------------------WYLOGOWANIE--------------------
    if (isset($_GET['logout']) && $_GET['logout'] == 1) {
        unset($_SESSION['id'], $_SESSION['email']);
        
    }
?>

<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
    <link rel="canonical" href="https://getbootstrap.com/docs/5.0/examples/sign-in/">
    <!-- nasz własny CSS -->
    <link href="style.css" rel="stylesheet">

    <meta name="theme-color" content="#7952b3">

    <title>Kalendarz ToDo</title>

</head>
<body>

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

<footer class="footer" role="contentinfo">
    Some footer Content
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
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>

</body>

</html>
