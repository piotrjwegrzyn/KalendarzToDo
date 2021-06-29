<?php
	include("config.inc.php");
	$stmt = $dbh->prepare("SELECT id FROM tasks WHERE id = :task_id AND user_email = :user_email");
	$stmt->execute([':task_id' => $_POST['task_id'], ':user_email' => $_POST['user_email']]);
	if($stmt->fetch(PDO::FETCH_ASSOC) && $_POST['guest_email']) {
		try {
			$stmt = $dbh->prepare("INSERT INTO links (id, task_id, guest_email) VALUES (NULL, :task_id, (SELECT email FROM users WHERE email = :guest_email))");
			$stmt->execute([':task_id' => $_POST['task_id'], ':guest_email' => $_POST['guest_email']]);
			$result = $stmt->fetch(PDO::FETCH_ASSOC);
			print $dbh->lastInsertId();
		} catch (PDOException $e) {
			print "NOT OK";
		}
	} else {
		print "NOT OK";
	}
