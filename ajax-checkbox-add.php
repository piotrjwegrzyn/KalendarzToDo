<?php
	include("config.inc.php");

	$stmt = $dbh->prepare("SELECT id FROM tasks WHERE id = :task_id AND user_email = :user_email");
	$stmt->execute([':task_id' => $_POST['task_id'], ':user_email' => $_POST['user_email']]);
	if($stmt->fetch(PDO::FETCH_ASSOC)) {
		try {
			$stmt = $dbh->prepare("INSERT INTO checkboxes (id, task_id, name, state) VALUES (NULL, :task_id, :name, 0)");
			$stmt->execute([':task_id' => $_POST['task_id'], ':name' => $_POST['content_description']]);
			print $dbh->lastInsertId();
		} catch (PDOException $e) {
			print "NOT OK";
		}
	}
