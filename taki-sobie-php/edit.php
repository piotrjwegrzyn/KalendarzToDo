<?php
if (!defined('IN_INDEX')) { exit("Nie można uruchomić tego pliku bezpośrednio."); }

#--------------------EDYTOWANIE-ZADANIA--------------------
# s119.labagh.pl?page=edit&id=997
if (isset($_GET['id'], $_POST['edit_name'], $_POST['edit_description'], $_POST['edit_beign_time'], $_POST['edit_end_time'])) {
    if ($_GET['id'] && $_POST['edit_name'] && $_POST['edit_description'] && $_POST['edit_beign_time'] && $_POST['edit_end_time']) {
        $stmt = $dbh->prepare("UPDATE tasks SET name = :name, description = :description, beign_time = :begin_time, end_time = :end_time WHERE user_id = :user_id AND id = :task_id");
        $stmt->execute([':user_id' => $_SESSION['id'], ':name' => $_POST['edit_name'], ':description' => $_POST['edit_description'], ':beign_time' => $_POST['edit_beign_time'], ':end_time' => $_POST['edit_end_time'], ':task_id' => $_GET['id']]);
        print '<span style="font-weight: bold; color: green;">Poprawnie edytowano wpis.</span>';
    } else {
        print '<span style="font-weight: bold; color: red;">Niepoprawna zmiana!</span>';
    }
}

#--------------------USUWANIE-ZADANIA--------------------
# s119.labagh.pl?page=edit&id=997&delete=1
if (isset($_GET['id'], $_GET['delete'])) {
    $stmt = $dbh->prepare("DELETE FROM tasks WHERE user_id = :user_id AND id = :task_id");
    $stmt->execute([':user_id' => $_SESSION['id'], ':task_id' => $_GET['id']]);
    if ($stmt) {
        $stmt = $dbh->prepare("DELETE FROM links WHERE task_id = :task_id");
        $stmt->execute([':task_id' => $_GET['delete']]);
        print '<span style="font-weight: bold; color: green;">Poprawnie usunięto wpis.</span>';
    } else {
        print '<span style="font-weight: bold; color: red;">Usuwanie się nie powiodło!</span>';
    }
}

#--------------------USUWANIE-GUESTA--------------------
# s119.labagh.pl?page=edit&id=997&guest_del=112
if (isset($_GET['id']) && $_GET['id']) {
    $stmt = $dbh->prepare("DELETE FROM links WHERE task_id = :task_id AND guest_id = :user_id");
    $stmt->execute([':task_id' => $_GET['unlink'], ':user_id' => $_SESSION['id']]);
    if ($stmt) {
        print '<span style="font-weight: bold; color: green;">Poprawnie usunięto gościa.</span>';
    } else {
        print '<span style="font-weight: bold; color: red;">Nie udało się usunąć gościa!</span>';
    }

}
