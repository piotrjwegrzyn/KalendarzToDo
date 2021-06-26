<?php
# s119.labagh.pl?page=create

if (!defined('IN_INDEX')) { exit("Nie można uruchomić tego pliku bezpośrednio."); }

if (isset($_POST['name'], $_POST['description'], $_POST['beign_time'], $_POST['end_time'])) {
    if ($_POST['name'] && $_POST['description'] && $_POST['beign_time'] && $_POST['end_time']) {
        $stmt = $dbh->prepare("INSERT INTO tasks (id, user_id, name, description, beign_time, end_time) VALUES (null, :user_id, :name, :description, :beign_time, :end_time)");
        $stmt->execute([':user_id' => $_SESSION['id'], ':name' => $_POST['name'], ':description' => $_POST['description'], ':beign_time' => $_POST['beign_time'], ':end_time' => $_POST['end_time']]);
        if (isset($_POST['guest']) && $_POST['guest']) {
            $stmt = $dbh->prepare("INSERT INTO links (id, task_id, guest_id) VALUES (null, :task_id, :guest_id)");
            $stmt->execute([':task_id,' => $dbh->lastInsertId(), ':guest_id' => $_POST['guest_id']]);
        }
    } else {
        print '<span style="color: red;">Uzupełnij dane!</span>';
    }
}
