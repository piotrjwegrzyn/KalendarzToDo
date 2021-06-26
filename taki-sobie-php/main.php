<?php
# s119.labagh.pl/
if (!defined('IN_INDEX')) { exit("Nie można uruchomić tego pliku bezpośrednio."); }

#--------------------WCZYTYWANIE-ZADAŃ--------------------
$stmt = $dbh->prepare("SELECT * FROM tasks WHERE user_id = :user_id OR id = (SELECT task_id FROM links WHERE guest_id = :user_id) ORDER BY begin_time ASC");
$stmt->execute([':user_id' => $_SESSION['id']]);
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    print '<a href="/show/' . $row['id'] . '">' . $row['name'] . '</a>';
}

#--------------------USUWANIE-SIEBIE-JAKO-GUEST--------------------
# s119.labagh.pl?unlink=997
if (isset($_GET['unlink']) && $_GET['unlink']) {
    $stmt = $dbh->prepare("DELETE FROM links WHERE task_id = :task_id AND guest_id = :user_id");
    $stmt->execute([':task_id' => $_GET['unlink'], ':user_id' => $_SESSION['id']]);
}

$allowed_pages = ['edit', 'create', 'show'];

if (isset($_GET['page']) && in_array($_GET['page'], $allowed_pages)) {
    include($_GET['page'] . '.php');
}
