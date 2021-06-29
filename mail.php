<?php
	# chron job tego czegoś:
	# 0 8 * * * mail.php
	# o 08:00 rano każdego dnia

	include("config.inc.php");
	include('mail_password.php');

	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\Exception;
	use PHPMailer\PHPMailer\SMTP;
	require 'PHPMailer/Exception.php';
	require 'PHPMailer/PHPMailer.php';
	require 'PHPMailer/SMTP.php';

	$stmt = $dbh->prepare("SELECT * FROM users");
	$stmt->execute();
	while ($user = $stmt->fetch(PDO::FETCH_ASSOC)) {
		$anytask = 0;

		$mail = new PHPMailer();
		$mail->isSMTP();
		$mail->SMTPAuth = true;
		$mail->SMTPSecure = 'ssl';
		$mail->Host = 'smtp.gmail.com';
		$mail->Port = '465';
		$mail->isHTML();
		$mail->Username = 'todo.calendars@gmail.com';
		$mail->Password = $mail_password;
		$mail->SetFrom('todo.calendars@gmail.com');
		$mail->Subject = 'Daily task notification';
		$mail->AddAddress($user['email']);

		$template = '
<!DOCTYPE html>
	<body>
		<div class="email-background" style="background: #eee;padding: 10px;max-width: 600px;">
			<div class="email-container" style="background: white;font-family: sans-	serif;overflow: hidden;border-radius: 5px;">
				<img src="https://media.makeameme.org/created/brace-yourself-tasks-a8f4c4b3e5.jpg">
				<p style="margin: 20px;font-size: 18px;font-weight: 300;color: #666;line-height: 1.5;text-align: center;">Tasks to do:</p>
				<ul>
		';
		$stmt_task = $dbh->prepare("SELECT * FROM tasks WHERE (
		    (user_email = :user_email OR id = ANY (SELECT task_id FROM links WHERE guest_email = :user_email))
		    AND ((YEARWEEK(begin_time) = YEARWEEK(NOW())
		            AND WEEKDAY(begin_time) = WEEKDAY(NOW()))
		        OR ((YEARWEEK(end_time) = YEARWEEK(NOW())
			        AND WEEKDAY(end_time) = WEEKDAY(NOW()))))
		) ORDER BY begin_time ASC");
		$stmt_task->execute([':user_email' => $user['email']]);
		while ($task = $stmt_task->fetch(PDO::FETCH_ASSOC)) {
			$anytask = 1;
			$template = $template . '
					<li>'.$task['name'].'</li>
					<ul style="list-style-type: circle;">
			';

			$stmt_checkbox = $dbh->prepare("SELECT * FROM checkboxes WHERE task_id = :task_id");
			$stmt_checkbox->execute([':task_id' => $task['id']]);
			while ($checkbox = $stmt_checkbox->fetch(PDO::FETCH_ASSOC)) {
				$template = $template . '
						<input type="checkbox" onclick="return false"';

				if ($checkbox['state'] == 1) {
					$template = $template . ' checked="checked"';
				}

				$template = $template . ' />
						<label>'.$checkbox['name'].'</label><br>
				';
			}
			$template = $template . '
					</ul><br>
			';
		}
		$template = $template . '
				</ul>
				<div class="CheckIt" style="margin: 20px;text-align: center;">
					<a href="https://s119.labagh.pl/" style="text-decoration: none;display: inline-block;background: #0066FF;color: white;padding: 10px 20px;border-radius: 5px;">! Check Them Out !</a>
				</div>
			</div>
			<div class="footer" style="background: none;padding: 20px;font-size: 12px;text-align: center;">
				Błaszczyna © Węgrzyn © Kwak
			</div>
		</div>
	</body>
</html>
		';
		if ($anytask == 1) {
			$mail->Body = $template;
			$mail->Send();
			print 'sent</br>';
		}
	}
?>
