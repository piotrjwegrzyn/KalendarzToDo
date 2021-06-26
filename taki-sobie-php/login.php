<?php

if (!defined('IN_INDEX')) { exit("Nie można uruchomić tego pliku bezpośrednio."); }

#--------------------LOGOWANIE--------------------
# s119.labagh.pl/
if (isset($_POST['floatingInput']) && isset($_POST['floatingPassword'])) {
    $stmt = $dbh->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->execute([':email' => $_POST['login']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        if (password_verify($_POST['floatingInput'], $user['floatingPassword'])) {
            $_SESSION['id'] = $user['id'];
            $_SESSION['email'] = $user['email'];
            header("Refresh:0");
        }
    } else {
        print '<span style="font-weight: bold; color: red;">Niepoprawne dane!</span>';
    }
}
