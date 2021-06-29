<?php
	include("config.inc.php");
	session_start();
	$stmt = $dbh->prepare("SELECT id FROM tasks WHERE id = :task_id AND user_email = :user_email");
	$stmt->execute([':task_id' => $_POST['task_id'], ':user_email' => $_SESSION['email']]);
	if($stmt->fetch(PDO::FETCH_ASSOC)) {
		try {
			$stmt = $dbh->prepare("DELETE FROM checkboxes WHERE id = :checkbox_id AND task_id = :task_id");
			$stmt->execute([':checkbox_id' => $_POST['checkbox_id'], ':task_id' => $_POST['task_id']]);
			print 'OK';
		} catch (PDOException $e) {
			print 'NOT OK';
		}
	} else {
		print 'NOT OK';
	}
