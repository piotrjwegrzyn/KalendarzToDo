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
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">To Do Calendar</a>
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
<main class="container-fluid">

   <div class="row">


        <!-- tu się zaczyna nav z lewej strony -->
      <div class="col-md-4">

            <!-- lista elementów nava -->
            <div class="list-group list-group-flush scrollarea nav-nav">

                <!-- nagłówek nava -->
                <div class="row element-nav header-nav" id="list-group">

                    <button type="button" class="btn col-md-2 button-nav">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z"></path>
                            </svg>
                    </button>

                    <a href="/" class="d-flex link-dark text-xs-center text-decoration-none col-md-8">
                        <span class="fs-5 fw-semibold">Week list</span>
                    </a>

                    <button type="button" class="btn col-md-2 button-nav">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-right" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8z"></path>
                        </svg>
                    </button>

                </div> <!-- koniec nagłówka nava -->

<?php
    $stmt = $dbh->prepare("SELECT * FROM tasks WHERE user_id = :user_id OR id = (SELECT task_id FROM links WHERE guest_id = :user_id) ORDER BY begin_time ASC");
    $stmt->execute([':user_id' => $_SESSION['id']]);
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        print '
                <div class="element-nav">
                    <a href="/show/'.$row['id'].'" class="list-group-item list-group-item-action active py-3 lh-tight" aria-current="true">
                        <div class="d-flex w-100 align-items-center justify-content-between">
                            <strong class="mb-1">'.$row['id'].'</strong>
                            <small>Wed</small>
                        </div>
                        <div class="col-10 mb-1 small">This week is pretty busy</div>
                    </a>
                </div>
        ';
    }
?>

            </div> <!-- to kończy się lista elementów nava -->

        </div> <!-- tu się zamyka nav z lewej strony -->


        <!-- tu zaczyna się ta szeroka lista z prawej strony -->

        <div class="col-md-8">
            <ul class="list-group list-group-flush nav-nav">
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
    </div>
</main>
