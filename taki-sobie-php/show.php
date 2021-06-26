<?php

if (!defined('IN_INDEX')) { exit("Nie można uruchomić tego pliku bezpośrednio."); }

# s119.labagh.pl?page=show&id=997
$stmt = $dbh->prepare("SELECT * FROM tasks WHERE id = :show AND (user_id = :user_id OR id = (SELECT task_id FROM links WHERE guest_id = :user_id AND task_id = :task_id))");
$stmt->execute([':task_id' => $_GET['id'], ':user_id' => $_SESSION['id']]);
$row = $stmt->fetch(PDO::FETCH_ASSOC)
if ($row) {
    if ($row['user_id'] == $_SESSION['id']) {
        print 'pełne uprawnienia: <a href="edit/' . $row['id'] . '><button >Edytuj Zadanie</button></a>';
        $stmt = $dbh->prepare("SELECT email FROM users WHERE id = (SELECT guest_id FROM links WHERE task_id = :task_id)");
        $stmt->execute(':task_id' => $row['id']]);
        if ($username = $stmt->fetch(PDO::FETCH_ASSOC)) {
            print '<p> Gość: ' . $username['email'] . '</p>'
        }
    } else {
        print 'uprawnienia częściowe: <a href="/unlink/' . $row['id'] . '><button >Wypisz się</button></a> ';
        $stmt = $dbh->prepare("SELECT email FROM users WHERE id = :user_id");
        $stmt->execute(':user_id' => $row['user_id']]);
        if ($username = $stmt->fetch(PDO::FETCH_ASSOC)) {
            print '<p> Właściciel: ' . $username['email'] . '</p>';
        }

    }
    print '<p> Tu jakies wyswietlanie wszystkiego</p>';
}

#--------------------WCZYTYWANIE-DNIA--------------------
$stmt = $dbh->prepare("SELECT * FROM tasks WHERE (
    (user_id = :user_id OR id = (SELECT task_id FROM links WHERE guest_id = :user_id))
    AND ((YEARWEEK(begin_time) = YEARWEEK(NOW()) + :week_offset
            AND WEEKDAY(begin_time) = :day_index)
        OR ((YEARWEEK(end_time) + :week_offset) = YEARWEEK(NOW()
            AND WEEKDAY(end_time) = :day_index)))
) ORDER BY begin_time ASC");
$stmt->execute([':user_id' => $_SESSION['id'], ':week_offset' => $_GET['week_offset'], ':day_index' => $_GET['day_index']]);
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    print $row['name'];
}
# week_offset: -2 -1 0(now) 1 2 3 ...
# day_index: 0(mon) 1(tue) 2(we) 3 4 5

#--------------------WCZYTYWANIE-ToDo--------------------
$stmt = $dbh->prepare("SELECT * FROM checkboxes WHERE task_id = :task_id");
$stmt->execute([':task_id' => $task['id']]);
while ($checkbox = $stmt->fetch(PDO::FETCH_ASSOC)) {
    print 'checkbox'.$checkbox['name'];
}
