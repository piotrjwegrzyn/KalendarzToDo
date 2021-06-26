<?php
# s119.labagh.pl/
if (!defined('IN_INDEX')) { exit("Nie można uruchomić tego pliku bezpośrednio."); }

#--------------------USUWANIE-SIEBIE-JAKO-GUEST--------------------
if (isset($_GET['unlink']) && $_GET['unlink']) {
    $stmt = $dbh->prepare("DELETE FROM links WHERE task_id = :task_id AND guest_id = :user_id");
    $stmt->execute([':task_id' => $_GET['unlink'], ':user_id' => $_SESSION['id']]);
}
?>

<body>
<!-- Pasek u góry -->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">KalendarzToDo</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="/">Home</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="/create">Add new task</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#">Settings</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="/logout">Log out</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Główny element -->
<main class="float-container">
    <div>
        <div class="d-flex flex-column align-items-stretch flex-shrink-0 bg-white float-child col-md-3">
            <a href="/" class="d-flex align-items-center flex-shrink-0 p-3 link-dark text-decoration-none border-bottom">
                <svg class="bi me-2" width="30" height="24"><use xlink:href="#bootstrap"/></svg>
                <span class="fs-5 fw-semibold">List group</span>
            </a>
            <div class="list-group list-group-flush border-bottom scrollarea">
<?php
    $stmt = $dbh->prepare("SELECT * FROM tasks WHERE user_id = :user_id OR id = (SELECT task_id FROM links WHERE guest_id = :user_id) ORDER BY begin_time ASC");
    $stmt->execute([':user_id' => $_SESSION['id']]);
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        print
        '<a href="/show/'.$row['id'].'" class="list-group-item list-group-item-action active py-3 lh-tight" aria-current="true">
            <div class="d-flex w-100 align-items-center justify-content-between">
                <strong class="mb-1">'.$row['id'].'</strong>
                <small>Wed</small>
            </div>
            <div class="col-10 mb-1 small">Some placeholder content in a paragraph below the heading and date.</div>
        </a>';
    }
?>
            </div>
        </div>

        <div class="container mb-5 float-child col-md-6">
            <ul class="list-group">
                <li class="list-group-item">
<?php
    $allowed_pages = ['edit', 'create', 'show'];

    if (isset($_GET['page']) && in_array($_GET['page'], $allowed_pages)) {
        include($_GET['page'] . '.php');
    }
?>
                </li>
            </ul>
        </div>
		  <div class="col-md-3">
				<p>!!!!!!!!!!!!!!!!!!</p>
		  </div>
    </div>
</main>
