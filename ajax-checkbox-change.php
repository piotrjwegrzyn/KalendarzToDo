<?php
	include("config.inc.php");

	$stmt = $dbh->prepare("SELECT id FROM tasks WHERE id = :task_id AND (user_email = :user_email OR id = (SELECT task_id FROM links WHERE guest_email = :user_email AND task_id = :task_id))");
	$stmt->execute([':task_id' => $_POST['task_id'], ':user_email' => $_POST['user_email']]);
	if($stmt->fetch(PDO::FETCH_ASSOC)) {
		try {
			$stmt = $dbh->prepare("UPDATE checkboxes SET state = :state WHERE id = :checkbox_id AND task_id = :task_id");
			$stmt->execute([':state' => $_POST['state'], ':checkbox_id' => $_POST['checkbox_id'], ':task_id' => $_POST['task_id']]);
			print 'OK';
		} catch (PDOException $e) {
			print 'NOT OK';
		}
	}
