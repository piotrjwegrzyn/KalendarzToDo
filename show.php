<?php
if (!defined('IN_INDEX')) { exit("Nie można uruchomić tego pliku bezpośrednio."); }
?>
<div class="list-group-item-lepszy" id="user_id">
<?php
	$stmt = $dbh->prepare("SELECT * FROM tasks WHERE id = :task_id AND (user_id = :user_id OR id = (SELECT task_id FROM links WHERE guest_id = :user_id AND task_id = :task_id))");
	$stmt->execute([':task_id' => $_GET['id'], ':user_id' => $_SESSION['id']]);
	$row = $stmt->fetch(PDO::FETCH_ASSOC);
	if ($row) {
	    if ($row['user_id'] == $_SESSION['id']) {
			$full_access = 1;
	    } else {
			$full_access = 0;
	    }
	} else {
		header('Location: index.php');
	}
?>
	<h5 class="name" style="grid-area: name"><?php print $row['name']; ?></h5>
	<h6 class="begin_time" style="grid-area: begin_time">Godzina od: <?php print $row['begin_time']; ?></h6>

<?php
	if ($full_access == 1) {
		print '
	<div style="grid-area: edit_icon_button">
		<a href="/edit/' . $row['id'] . '">
		<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16">
			<path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z"/>
		</svg>
		</a>
	</div>';
	}
?>

	<h6 class="end_time" style="grid-area: end_time">Godzina do: <?php print $row['end_time']; ?></h6>

	<div style="grid-area: delete_icon_button">
<?php
	if ($full_access == 1) {
		print
		'<a href="/edit/'.$row['id'].'/delete">';
	} else {
		print
		'<a href="/unlink/'.$row['id'].'">';
	}
?>
		<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-calendar-x" viewBox="0 0 16 16">
			<path d="M6.146 7.146a.5.5 0 0 1 .708 0L8 8.293l1.146-1.147a.5.5 0 1 1 .708.708L8.707 9l1.147 1.146a.5.5 0 0 1-.708.708L8 9.707l-1.146 1.147a.5.5 0 0 1-.708-.708L7.293 9 6.146 7.854a.5.5 0 0 1 0-.708z"/>
			<path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z"/>
		</svg>
		</a>

	</div>

	<div class="list-group-item-lepszy-description">
		<h6><?php print $row['description']; ?></h6>


<?php
	if ($full_access == 1) {
		$stmt = $dbh->prepare("SELECT email FROM users WHERE id = (SELECT guest_id FROM links WHERE task_id = :task_id)");
		$stmt->execute([':task_id' => $row['id']]);
		while ($username = $stmt->fetch(PDO::FETCH_ASSOC)) {
			print '<p> Gość: ' . $username['email'] . '</p>';
		}
	} else {
		$stmt = $dbh->prepare("SELECT email FROM users WHERE id = :user_id");
		$stmt->execute([':user_id' => $row['user_id']]);
		$username = $stmt->fetch(PDO::FETCH_ASSOC);
		if ($username) {
			print '<p> Właściciel: ' . $username['email'] . '</p>';
		}
	}
?>
		<div class="list-group-item-lepszy-description-zawartosc" style="display: none">

			Olcia Mardaus mrrrrrr

		</div>

	</div>

</div>
