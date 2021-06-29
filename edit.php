<!-- plik edit -->
<?php
if (!defined('IN_INDEX')) { exit("Nie można uruchomić tego pliku bezpośrednio."); }

#--------------------EDYTOWANIE-ZADANIA--------------------
# s119.labagh.pl?page=edit&id=997
if (isset($_GET['id'], $_POST['edit_name'], $_POST['edit_description'], $_POST['edit_begin_time'], $_POST['edit_end_time'])) {
    if ($_GET['id'] && $_POST['edit_name'] && $_POST['edit_description'] && $_POST['edit_begin_time'] && $_POST['edit_end_time']) {
        $_POST['edit_begin_time'] = date("Y-m-d H:i:00", strtotime($_POST['edit_begin_time']));
        $_POST['edit_end_time'] = date("Y-m-d H:i:00", strtotime($_POST['edit_end_time']));
        $stmt = $dbh->prepare("UPDATE tasks SET name = :name, description = :description, begin_time = :begin_time, end_time = :end_time WHERE user_email = :user_email AND id = :task_id");
        $stmt->execute([':user_email' => $_SESSION['email'], ':name' => $_POST['edit_name'], ':description' => $_POST['edit_description'], ':begin_time' => $_POST['edit_begin_time'], ':end_time' => $_POST['edit_end_time'], ':task_id' => $_GET['id']]);
        print '<script>window.location.replace("/");</script>';
    } else {
        print '<span style="font-weight: bold; color: red;">Niepoprawna zmiana!</span>';
    }
}

#--------------------ŁADOWANIE-ZADANIA--------------------
$stmt = $dbh->prepare("SELECT * FROM tasks WHERE id = :task_id AND user_email = :user_email");
$stmt->execute([':user_email' => $_SESSION['email'], ':task_id' => $_GET['id']]);
$task = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$task)
    exit("nie bawimy sie tak cwaniaczku");

#--------------------USUWANIE-ZADANIA--------------------
# s119.labagh.pl?page=edit&id=997&delete=1
if (isset($_GET['id'], $_GET['delete'])) {
    $stmt = $dbh->prepare("DELETE FROM tasks WHERE user_email = :user_email AND id = :task_id");
    $stmt->execute([':user_email' => $_SESSION['email'], ':task_id' => $_GET['id']]);
    $stmt = $dbh->prepare("DELETE FROM links WHERE task_id = :task_id");
    $stmt->execute([':task_id' => $_GET['id']]);
    $stmt = $dbh->prepare("DELETE FROM checkboxes WHERE task_id = :task_id");
    $stmt->execute([':task_id' => $_GET['id']]);
    print '<script>window.location.replace("/");</script>';
}

?>

<script>

	function add_new_task_to_list(content_name, i) {
		$('#dynamic_field').append('<div class="form-floating" id="row_task' + i + '"> \
			<div class="row row-task-edit">\
				<div class="col-10">\
					<input name="name[]" type="text" class="form-control name_list" value="' + content_name + '" id="name" placeholder="Empty task" readonly="readonly">\
						<!--<label for="name"></label>-->\
					</div>\
				<div class="col-2 padding-button-edit">\
					<div name="remove" id="' + i + '" class="btn btn-remove button-show button-delete">\
						<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-x">\
							<path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>\
						</svg>\
					</div>\
				</div>\
			</div>\
		</div>');
	}

	function add_new_contributor_to_list(content_name, i) {
		$('#dynamic_field_contributor').append('<div class="form-floating " id="row_contributor' + i + '"> \
			<div class="row row-task-edit">\
				<div class="col-10">\
					<input name="name2[]" type="text" class="form-control name_list" value="' + content_name + '" id="contributor" placeholder="Enter Contributor" readonly="readonly">\
						<!--<label for="name">Your task</label>-->\
					</div>\
				<div class="col-2 padding-button-edit">\
					<div name="remove2" id="' + i + '" class="btn btn-remove-contributor button-show button-delete">\
						<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-x">\
							<path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>\
						</svg>\
					</div>\
				</div>\
			</div>\
		</div>');
	}

</script>

<form action="/edit/<?php print $task['id']; ?>" method="POST">
	<!-- nagłówek edita -->
	<div class="list-group list-group-flush scrollarea header-edit">
		<div class="list-group-item title-edit">

			<div class="row">

				<span class="text-xs-center text-decoration-none col-10 fs-1 fw-semibold center-class white-font">
					<span>Edit your event</span>
				</span>


				<!-- \/\/\/\/\/\/\/ TUTAJ JEST BUTTON DO ZATWIERDZANIA EDYCJI \/\/\/\/\/\/\/ -->


				<button type="submit" class="btn col-2 button-show button-edit center-class">
					<span>
						<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" class="bi bi-pencil">
							<path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z"/>
						</svg>
						Save
					</span>
				</button>
			</div>
		</div>
	</div> <!-- koniec nagłówka edita -->

	<div class="list-group list-group-flush scrollarea header-edit">
		<div class="list-group-item edit-edit">

			<div class="row">
				<!-- edycja szczegółów -->
				<div class="col-xl-4 form-edit">

					<div>
						<h2 class="fw-normal center-class">Information</h2>

						<div class="form-floating">
							<input type="text" name="edit_name" id="name" class="form-control first-element" placeholder="Name" value="<?php print $task['name']; ?>">
							<label for="name">Name</label>
						</div>

						<div class="form-floating">
							<input type="text" name="edit_description" id="description" class="form-control middle-element" placeholder="Description" value="<?php print $task['description']; ?>">
							<label for="description">Description</label>
						</div>

						<div class="form-floating">
							<input type="datetime-local" name="edit_begin_time" id="begin_time" class="form-control middle-element" value="<?php print str_replace("CEST", "T", date("Y-m-dTH:i" , strtotime($task['begin_time']))); ?>">
							<label for="begin_time">Begin time</label>
						</div>

						<div class="form-floating">
							<input type="datetime-local" name="edit_end_time" id="end_time" class="form-control last-element" value="<?php print str_replace("CEST", "T", date("Y-m-dTH:i" , strtotime($task['end_time']))); ?>">
							<label for="end_time">End time</label>
						</div>

					</div>

				</div>

				<!-- edycja podzadań -->
				<div class="col-xl-4 form-edit">

					<h2 class="fw-normal">Tasks</h2>

					<div name="new_task_space" id="new_task_space" class="list-group list-group-flush scrollarea">

						<div class="" id="dynamic_field">
							<!-- ZOSTAWIĆ,tutaj dodają się elementy z AJAXa -->
<?php
    $stmt = $dbh->prepare("SELECT id, name FROM checkboxes WHERE task_id = :task_id");
    $stmt->execute([':task_id' => $task['id']]);
    while ($checkbox = $stmt->fetch(PDO::FETCH_ASSOC)) {
        print '<script>add_new_task_to_list("'.$checkbox['name'].'", '.$checkbox['id'].');</script>';
    }
?>
						</div>

						<div class="row-task-edit">
							<input name="name[]" type="text" class="form-control name_list" id="new_task_entry" placeholder="Enter Task">
						</div>

						<div type="button" name="new_task_button" id="new_task_button" class="btn col-12 button-show button-edit center-class">
							<span>
							<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-plus">
								<path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
							</svg>
								Add new task
							</span>
						</div>
					</div>

				</div>

				<!-- edycja kontrybutorów -->
				<div class="col-xl-4 form-edit">

					<h2 class="fw-normal center-class">Contributors</h2>

					<div name="new_contributor_space" id="new_contributor_space">

						<div class="" id="dynamic_field_contributor">
							<!-- ZOSTAWIĆ,tutaj dodają się elementy z AJAXa -->
<?php
    $stmt = $dbh->prepare("SELECT id, guest_email FROM links WHERE task_id = :task_id");
    $stmt->execute([':task_id' => $task['id']]);
    while ($link = $stmt->fetch(PDO::FETCH_ASSOC)) {
        print '<script>add_new_contributor_to_list("'.$link['guest_email'].'", '.$link['id'].');</script>';
    }
?>
						</div>

						<div class="row-task-edit">
							<input name="name[]" type="text" class="form-control name_list" id="new_contributor_entry" placeholder="Enter Contributor">
						</div>

						<div type="button" name="new_contributor_button" id="new_contributor_button" class="btn col-12 button-show button-edit center-class">
							<span>
							<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-plus" viewBox="0 0 16 16">
								<path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
							</svg>
								Add new contributor
							</span>
						</div>
					</div>

				</div>
			</div>
		</div>
	</div>
</form>

<script>
	$(document).ready(function() {
		$('#new_task_button').click(function () {
			content_name = document.getElementById("new_task_entry").value;
			$.ajax({
				url: "/ajax-checkbox-add.php",
				method: "POST",
				data: {
          			task_id : <?php print $task['id']; ?>,
					content_description : content_name
				}
			}).done(function(inn) {
                console.log(inn);
				if (inn != 'NOT OK') {
					add_new_task_to_list(content_name, inn);
					document.getElementById("new_task_entry").value = '';
				} else {
                    alert("Something went wrong...");
                }
			}).fail(function() {
                alert("Something went wrong...");
            });
		});

		$(document).on('click', '.btn-remove', function () {
			var button_id = $(this).attr("id");
			$.ajax({
				url: "/ajax-checkbox-delete.php",
				method: "POST",
				data: {
          			task_id : <?php print $task['id']; ?>,
					checkbox_id : $(this).attr("id")
				}
			}).done(function(ouu) {
				if(ouu != 'NOT OK') {
					$('#row_task' + button_id + '').remove();
				} else {
                    alert("Something went wrong...");
                }
			}).fail(function() {
                alert("Something went wrong...");
            });

		});
	});


	$(document).ready(function() {
		$('#new_contributor_button').click(function () {
			content_name = document.getElementById("new_contributor_entry").value;
			$.ajax({
				url: "/ajax-guest-add.php",
				method: "POST",
				data: {
          			task_id : <?php print $_GET['id']; ?>,
					guest_email : content_name
				}
			}).done(function(inn) {
				if (inn != 'NOT OK') {
					add_new_contributor_to_list(content_name, inn);
					document.getElementById("new_contributor_entry").value = '';
				} else {
                    alert("No such user found!");
                }
			}).fail(function() {
                alert("Something went wrong...");
            });
		});

		$(document).on('click', '.btn-remove-contributor', function () {
			var button_id = $(this).attr("id");
			$.ajax({
				url: "/ajax-guest-delete.php",
				method: "POST",
				data: {
          			task_id : <?php print $_GET['id']; ?>,
					link_id : $(this).attr("id")
				}
			}).done(function(ouu) {
				if(ouu != 'NOT OK') {
					$('#row_contributor' + button_id + '').remove();
				} else {
                    alert("Something went wrong...");
                }
			}).fail(function() {
                alert("Something went wrong...");
            });
		});
	});

</script>

<!-- koniec pliku edit -->
