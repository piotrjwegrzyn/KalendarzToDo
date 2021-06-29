<?php
	include("config.inc.php");

	$stmt = $dbh->prepare("SELECT id FROM tasks WHERE id = :task_id AND user_email = :user_email");
	$stmt->execute([':task_id' => $_POST['task_id'], ':user_email' => $_POST['user_email']]);
	if($stmt->fetch(PDO::FETCH_ASSOC)) {
		try {
			$stmt = $dbh->prepare("DELETE FROM links WHERE id = :link_id AND task_id = :task_id");
			$stmt->execute([':link_id' => $_POST['link_id'], ':task_id' => $_POST['task_id']]);
			$result = $stmt->fetch(PDO::FETCH_ASSOC);
			print 'OK';
		} catch (PDOException $e) {
			print "NOT OK";
		}
	}
