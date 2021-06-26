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
if (isset($_GET['logout'])) {
    unset($_SESSION['id'], $_SESSION['email']);
    header("Refresh:0");
}

#--------------------ŁADOWANIE-STRON--------------------
$allowed_pages = ['signin', 'signup'];

if (isset($_SESSION['id'])) {
    include('main.php');
} elseif (isset($_GET['page']) && in_array($_GET['page'], $allowed_pages)) {
    include($_GET['page'] . '.php');
} else {
    include('login.php');
}
