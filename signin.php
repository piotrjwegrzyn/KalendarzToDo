<?php
if (!defined('IN_INDEX')) { exit("Nie można uruchomić tego pliku bezpośrednio."); }
?>

<main class="form-signin">
  <form>
    <h1 class="h3 mb-3 fw-normal">Please sign in</h1>

    <div class="form-floating">
      <input type="email" class="form-control" id="floatingInput" placeholder="name@example.com">
      <label for="floatingInput">Email address</label>
    </div>

    <div class="form-floating">
      <input type="password" class="form-control" id="floatingPassword" placeholder="Password">
      <label for="floatingPassword">Password</label>
    </div>

    <button class="w-100 btn btn-lg btn-primary" type="submit">Sign in</button>
    <button class="w-100 btn btn-lg btn-primary" type="button">Sign up</button>
  </form>
  <?php
  #--------------------LOGOWANIE--------------------
  # s119.labagh.pl/
  if (isset($_POST['floatingInput']) && isset($_POST['floatingPassword'])) {
      $stmt = $dbh->prepare("SELECT * FROM users WHERE email = :email");
      $stmt->execute([':email' => $_POST['login']]);
      $user = $stmt->fetch(PDO::FETCH_ASSOC);

      if ($user) {
          if (password_verify($_POST['floatingInput'], $user['floatingPassword'])) {
              $_SESSION['id'] = $user['id'];
              $_SESSION['email'] = $user['email'];
              header("Refresh:0");
          }
      } else {
          print '<span style="font-weight: bold; color: red;">Niepoprawne dane!</span>';
      }
  }
  ?>
</main>
