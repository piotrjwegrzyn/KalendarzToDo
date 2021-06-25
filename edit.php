<?php
if (!defined('IN_INDEX')) { exit("Nie można uruchomić tego pliku bezpośrednio."); }


#--------------------ŁADOWANIE-ZADANIA--------------------
$stmt = $dbh->prepare("SELECT * FROM tasks WHERE id = :task_id AND user_id = :user_id");
$stmt->execute([':user_id' => $_SESSION['id'], ':task_id' => $_GET['id']]);
$task = $stmt->fetch(PDO::FETCH_ASSOC);

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
if (isset($_GET['id'], $_GET['unlink']) && $_GET['id'] && $_GET['unlink']) {
    $stmt = $dbh->prepare("DELETE FROM links WHERE task_id = :task_id AND guest_id = :user_id");
    $stmt->execute([':task_id' => $_GET['unlink'], ':user_id' => $_SESSION['id']]);
    if ($stmt) {
        print '<span style="font-weight: bold; color: green;">Poprawnie usunięto gościa.</span>';
    } else {
        print '<span style="font-weight: bold; color: red;">Nie udało się usunąć gościa!</span>';
    }
}
?>


<!-- <!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="">
  <title>Edit</title>

  <link rel="canonical" href="https://getbootstrap.com/docs/5.0/examples/sign-in/">


  <link href="css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
  <meta name="theme-color" content="#7952b3">

  <link href="style.css" rel="stylesheet">
</head>
<body class="body-edit"> -->

<main class="form-edit">
  <form>
    <h1 class="h3 mb-3 fw-normal">Edit</h1>

    <div class="form-floating">
      <input type="text" name="edit_name" id="name" class="form-control mr-sm-2" placeholder="Name" value="<?php print $task['name']; ?>">
        <label for="name">Name</label>
    </div>

    <div class="form-floating">
      <input type="text" name="edit_description" id="description" class="form-control mr-sm-2" placeholder="Description"value="<?php print $task['description']; ?>">
        <label for="description">Description</label>
    </div>

	 <div class="form-floating">
      <input type="datetime-local" name="edit_begin_time" id="begin_time" class="form-control mr-sm-2" value="<?php print $task['begin_time']; ?>">
         <label for="begin_time">Begin time</label>
    </div>

	 <div class="form-floating">
      <input type="datetime-local" name="edit_end_time" id="end_time" class="form-control mr-sm-2" value="<?php print $task['end_time']; ?>">
         <label for="end_time">End time</label>
    </div>
    <button class="w-100 btn btn-lg btn-primary" type="button">Zatwierdź</button>
  </form>
</main>

<!-- </body>
</html> -->
