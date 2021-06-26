<?php
if (!defined('IN_INDEX')) { exit("Nie można uruchomić tego pliku bezpośrednio."); }


#--------------------DODAWANIE-ZADANIA--------------------

if (isset($_POST['name'], $_POST['description'], $_POST['begin_time'], $_POST['end_time'])) {
    if ($_POST['name'] && $_POST['description'] && $_POST['begin_time'] && $_POST['end_time']) {
        try {
            $caught = false;
            $stmt = $dbh->prepare("INSERT INTO tasks (id, user_id, name, description, begin_time, end_time) VALUES (NULL, :user_id, :name, :description, :begin_time, :end_time)");
            $stmt->execute([':user_id' => $_SESSION['id'], ':name' => $_POST['name'], ':description' => $_POST['description'], ':begin_time' => $_POST['begin_time'], ':end_time' => $_POST['end_time']]);
        } catch (PDOException $e) {
            $caught = true;
            print '<span style="font-weight: bold; color: red;">Wprowadz poprawne dane!'.$e.'</span>';
        }
        if (!$caught) {
            print '<span style="font-weight: bold; color: green;">Dodano wpis!</span>';
        }
    } else {
        print '<span style="font-weight: bold; color: red;">Wprowadz dane!</span>';
    }
}

?>

<main class="form-add">
  <form action="/create" method="POST">
    <h1 class="h3 mb-3 fw-normal">ADD</h1>

    <div class="form-floating">
      <input type="text" name="name" id="name" class="form-control mr-sm-2" placeholder="Name">
        <label for="name">Name</label>
    </div>

    <div class="form-floating">
      <input type="text" name="description" id="description" class="form-control mr-sm-2" placeholder="Description">
        <label for="description">Description</label>
    </div>

	 <div class="form-floating">
      <input type="datetime-local" name="begin_time" id="begin_time" class="form-control mr-sm-2">
         <label for="begin_time">Begin time</label>
    </div>

	 <div class="form-floating">
      <input type="datetime-local" name="end_time" id="end_time" class="form-control mr-sm-2">
         <label for="end_time">End time</label>
    </div>
    <input type="submit" value="DODAJ">
    <!-- <button class="w-100 btn btn-lg btn-primary" type="button">Dodaj</button> -->
  </form>
</main>
