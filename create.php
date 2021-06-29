<!-- plik create -->
<?php
if (!defined('IN_INDEX')) { exit("Nie można uruchomić tego pliku bezpośrednio."); }
?>

<form class="form-add" action="/create" method="POST">
    <!-- nagłówek adda -->
	<div class="list-group list-group-flush scrollarea header-edit">
		<div class="list-group-item title-edit">

			<div class="row">

				<span class="link-dark text-xs-center text-decoration-none col-10 fs-1 fw-semibold center-class white-font">
					<span>Add new event</span>
				</span>


				<!-- \/\/\/\/\/\/\/ TUTAJ JEST BUTTON DO ZATWIERDZANIA EDYCJI \/\/\/\/\/\/\/ -->

                <button type="submit" class="btn col-2 button-show button-edit center-class">
					<span>
						<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" class="bi bi-pencil">
							<path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z"/>
						</svg>
						Save
					</span>
                </button>
			</div>
		</div>
	</div> <!-- koniec nagłówka adda -->

	<div class="list-group list-group-flush scrollarea header-edit">
		<div class="list-group-item edit-edit">
            <div class="form-edit">
                <div class="form-floating">
                    <input type="text" name="name" id="name" class="form-control first-element" placeholder="Name">
                    <label for="name">Name</label>
                </div>

                <div class="form-floating">
                    <input type="text" name="description" id="description" class="form-control middle-element" placeholder="Description">
                    <label for="description">Description</label>
                </div>

                <div class="form-floating">
                    <input type="datetime-local" name="begin_time" id="begin_time" class="form-control middle-element" placeholder="Begin time">
                    <label for="begin_time">Begin time</label>
                </div>

                <div class="form-floating">
                    <input type="datetime-local" name="end_time" id="end_time" class="form-control last-element" placeholder="End time">
                    <label for="end_time">End time</label>
                </div>
        <?php
        #--------------------DODAWANIE-ZADANIA--------------------
        if (isset($_POST['name'], $_POST['description'], $_POST['begin_time'], $_POST['end_time'])) {
            if ($_POST['name'] && $_POST['description'] && $_POST['begin_time'] && $_POST['end_time']) {
                if ($_POST['begin_time'] > $_POST['end_time']) {
                    print '<span style="font-weight: bold; color: red;">Begin after end!</span>';
                } else {
                    try {
                        $_POST['begin_time'] = date("Y-m-d H:i:00", strtotime($_POST['begin_time']));
                        $_POST['end_time'] = date("Y-m-d H:i:00", strtotime($_POST['end_time']));

                        $stmt = $dbh->prepare("INSERT INTO tasks (id, user_email, name, description, begin_time, end_time) VALUES (NULL, :user_email, :name, :description, :begin_time, :end_time)");
                        $stmt->execute([':user_email' => $_SESSION['email'], ':name' => $_POST['name'], ':description' => $_POST['description'], ':begin_time' => $_POST['begin_time'], ':end_time' => $_POST['end_time']]);

                        print '<script>window.location.replace("/edit/'.$dbh->lastInsertId().'");</script>';
                    } catch (PDOException $e) {
                        print '<span style="font-weight: bold; color: red;">Please provide correct data!</span>';
                    }
                }
            } else {
                print '<span style="font-weight: bold; color: red;">Please provide all data!</span>';
            }
        }
        ?>
            </div>
        </div>
    </div>
</form>

<script type="text/javascript">
  $(function() {
    $('#datetimepicker1').datetimepicker({
      language: 'pt-BR'
    });
  });
</script>
<!-- koniec pliku create -->
