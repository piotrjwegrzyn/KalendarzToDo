<!-- plik show -->
<?php
if (!defined('IN_INDEX')) { exit("Nie można uruchomić tego pliku bezpośrednio."); }

//--------------------WCZYTYWANIE-DNIA--------------------
$stmt = $dbh->prepare("SELECT * FROM tasks WHERE ((user_email = :user_email
OR id = ANY (SELECT task_id FROM links WHERE guest_email = :user_email))
AND ((((YEARWEEK(begin_time,1) = YEARWEEK(NOW(),1) + :week_offset)
		 AND (WEEKDAY(begin_time) <= :day_index))
	 OR (YEARWEEK(begin_time,1) < YEARWEEK(NOW(),1) + :week_offset))
AND (((YEARWEEK(end_time,1) = YEARWEEK(NOW(),1) + :week_offset)
		 AND (WEEKDAY(end_time) >= :day_index))
	OR (YEARWEEK(end_time,1) > YEARWEEK(NOW(),1) + :week_offset)
))) ORDER BY begin_time ASC");
$stmt->execute([':user_email' => $_SESSION['email'], ':week_offset' => $_GET['week_offset'], ':day_index' => $_GET['day_index']]);

//print $_GET['week_offset'].'</br>'.$_GET['day_index'];
$harnas = 1;
while ($task = $stmt->fetch(PDO::FETCH_ASSOC)) {
	$harnas = 0;
	print '
<div class="element-nav element-show" id="user_id">
	<div class="row title-show py-3">
		<!-- tytuł zadania -->
		<div class="col-lg-8 col-12">
			<h1 class="name white-font">'.$task['name'].'</h1>
		</div>
		<!-- przycisk edit -->
		';
	if ($task['user_email'] == $_SESSION['email']) { // full access
		print '
		<div class="col-lg-2 col-6 button_holder center-class">

			<a href="/edit/' . $task['id'] . '" class="btn button-show button-edit">
				<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" class="bi bi-pencil">
					<path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z"/>
				</svg>
				Edit
			</a>
		</div>';

	}
	print '
		<!-- przycisk usuwania -->
		<div class="col-lg-2 col-6 button-holder center-class">
			';
		if ($task['user_email'] == $_SESSION['email']) {
			print '
			<a href="/edit/'.$task['id'].'/delete" class="btn button-show button-delete">

			';
		} else {
			print '
			<a href="/unlink/'.$task['id'].'" class="btn button-show button-delete">

			';
		}
		print '
				<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" class="bi bi-x">
					<path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
				</svg>
				Delete
			</a>
		</div>
	</div>
	<div class="row info-tasks-holder">

		<!-- info -->
		<div class="col-12 col-md-6">

			<div class="form-edit contributors-list">
				<h2 class="fw-normal">Information</h2>
				<h5>Dates and times</h5>
				<div class="row">

				<div class="col-12 col-md-6 begin_time py-3 task-times">
					Start date:
					<br />
					'.$task['begin_time'].'
				</div>

				<div class="col-12 col-md-6 end_time py-3 task-times">
					End date:
					<br />
					'.$task['end_time'].'
				</div>
				</div>



				<div>
					<h5>Description</h5>
						'.$task['description'].'
				</div>

			</div>
		';
		print '
			<div class="form-edit contributors-list">
				<h2 class="fw-normal">Other contributors</h2>
				<div class="list-group list-group-flush scrollarea">
					<input type="text" class="form-control name_list" value="Dupa" id="name" placeholder="Empty task" readonly="readonly">

				</div>
			</div>
		</div>

		<!-- tasks -->

		<div class="col-12 col-md-6">

			<div class="form-edit contributors-list">

			<h2 class="fw-normal">Tasks for this event</h2>


		<!-- lista tasków -->';
		$stmt_2 = $dbh->prepare("SELECT * FROM checkboxes WHERE task_id = :task_id");
	    $stmt_2->execute([':task_id' => $task['id']]);

	    while ($checkbox = $stmt_2->fetch(PDO::FETCH_ASSOC)) {
	        print '
		<div class="form-floating">
			<div class="row row-task-edit">
				<div class="col-2 padding-button-edit">
					<input class="form-check-input" type="checkbox" name="'.$task['id'].'" id="'.$checkbox['id'].'" ';
			if ($checkbox['state']==1)
				print 'checked';
			print '>
				</div>
				<div class="col-10">
					<input name="name[]" type="text" class="form-control name_list" value="'.$checkbox['name'].'" id="name" readonly="readonly">
						<!--<label for="name"></label>-->
				</div>
			</div>
		</div>
			';
		}
		print '
		</div>
	</div>
	</div>
	</div>
		';
}
if ($harnas)
	print '<img src="/harnas.png" width="100">';
?>
<script>
	$(document).ready(function() {
		$('.form-check-input').click(function  () {
			console.log((this.checked) ? 0 : 1);
			$.ajax({
				url: "/ajax-checkbox-change.php",
				method: "POST",
				data: {
          			task_id : $(this).attr("name"),
					user_email : "<?php print $_SESSION['email']; ?>",
					checkbox_id : $(this).attr("id"),
					state : this.checked ? 1 : 0
				}
			}).done(function(inn) {
				var guzik = $(this);
				console.log(inn);
				if (inn == 'NOT OK') {
					setTimeout(function() {
				       	guzik.prop('checked', false);
						console.log('chuj');
				    }, 1000);
				}
			});
		});
	});
</script>
<!-- koniec pliku show -->
