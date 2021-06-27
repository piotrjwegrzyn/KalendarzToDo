<?php
    if (!defined('IN_INDEX')) { exit("Nie można uruchomić tego pliku bezpośrednio."); }

    # s119.labagh.pl?page=register
    function register_user($post, $config, $server, $dbh) {
        $recaptcha = new \ReCaptcha\ReCaptcha($config['recaptcha_private']);
        $resp = $recaptcha->setExpectedHostname('s119.labagh.pl')
                              ->verify($post['g-recaptcha-response'], $server['REMOTE_ADDR']);
        if (!$resp->isSuccess())
          return '<span style="font-weight: bold; color: red;">Rozwiąż re-captcha!</span>	';

      	if (!($_POST['email'] && $_POST['password'] && $_POST['password-retype']))
      		return '<span style="font-weight: bold; color: red;">Uzupełnij wszystkie pola!</span>';

      	if (!preg_match('/^[a-zA-Z0-9\-\_\.]+\@[a-zA-Z0-9\-\_\.]+\.[a-zA-Z]{2,5}$/D', $post['email']))
      		return '<span style="font-weight: bold; color: red;">Podaj poprawny adres email!</span>';

      	if ($post['password'] != $post['password-retype'])
      		return '<span style="font-weight: bold; color: red;">Podane hasła się różnią!</span>';

      	try {
      		$hash = password_hash($post['password'], PASSWORD_DEFAULT);
      		$stmt = $dbh->prepare('INSERT INTO users (id, email, password, created) VALUES (null, :email, :password, NOW())');
      		$stmt->execute([':email' => $post['email'], ':password' => $hash]);

      		return '<span style="font-weight: bold; color: green;">Zarejestrowano użytkownika '.$post['email'].' :))</span>';
      	} catch (PDOException $e) {
      		return '<span style="font-weight: bold; color: red;">Podany email jest zajęty!</span>';
      	}
     }
?>

<body class="body-sign">

<main class="form-sign">
<div class="div-sign">
    <form action='/signup' method='POST'>
      <h1 class="h3 mb-3 fw-normal">Please sign up</h1>

      <div class="form-floating">
        <input name="email" type="email" class="form-control" id="floatingInput" placeholder="name@example.com">
        <label for="floatingInput">Email address</label>
      </div>

      <div class="form-floating">
        <input name="password" type="password" class="form-control first-password" id="floatingPassword" placeholder="Password">
        <label for="floatingPassword">Password</label>
      </div>

      <div class="form-floating">
        <input name="password-retype" type="password" class="form-control" id="floatingRetype" placeholder="RetypePassword">
        <label for="floatingRetype">Retype Password</label>
      </div>

      <div class="form-floating">
          <div class='g-recaptcha' data-sitekey="<?php print $config['recaptcha_public']; ?>" data-size="compact"></div>
      </div>

      <!-- <button class="w-100 btn btn-lg btn-primary" type="button">Sign up</button> -->
      <input class="w-100 btn btn-lg btn-primary button-sign" type="submit" value="Sign up">
    </form>
    <a href="signin"><button class="w-100 btn btn-lg btn-primary button-sign" type="button">Sign in</button></a>
  </div>

<?php
    if (isset($_POST['email'], $_POST['password'], $_POST['password-retype'])) {
    	print register_user($_POST, $config, $_SERVER, $dbh);
    }
?>
</main>
