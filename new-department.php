<?php include "head.php"; ?>

			<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
				<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
					<h1 class="h2">Welcome <?php echo display_name(); ?></h1>
				</div>

				<div class="title">
					<h2 class="d-inline">New Files</h2>
					<a href="departments.php" class="btn btn-dark mb-3 ms-2">Back</a>
				</div>
				<form method="post">

					<?php

					if(isset($_POST['submit'])) {
						$name = mysqli_real_escape_string($conn, $_POST['name']);
						$slug = mysqli_real_escape_string($conn, $_POST['slug']);
						$department_name = strtolower(str_replace(' ', '', $name));

						if($name != '' && $slug != '') {
							$check_query = mysqli_query($conn, "SELECT * FROM departments WHERE LOWER(name)='$department_name'");
							if(mysqli_num_rows($check_query) > 0) {
								echo "<div class='alert alert-danger'>Department can't be dublicated</div>";
							} else {

								$query = mysqli_query($conn, "INSERT INTO departments(name, slug) VALUES('$name', '$slug')");
								if($query) {
									echo "<div class='alert alert-success'>Department is Successfully Created</div>";
								} else {
									echo "<div class='alert alert-danger'>Please Try Again</div>";
								}
							}
						} else {
							echo "<div class='alert alert-danger'>Fill All Required Fields</div>";
						}
					}

					?>
					
					<div class="mb-3">
						<label>Name:</label>
						<input type="text" class="form-control" placeholder="Enter Department Name" name="name" id="department_name">
					</div>
					<div class="mb-3">
						<label>Slug:</label>
						<input type="text" class="form-control" placeholder="Enter Department Slug" name="slug">
					</div>

					<button class="btn btn-dark" type="submit" name="submit">Submit</button>

				</form>
			</main>
		</div>
	</div>
	<?php include "footer.php"; ?>


	<script type="text/javascript" src="https://code.jquery.com/jquery-3.6.1.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/feather-icons@4.28.0/dist/feather.min.js"></script>
	<script src="assets/js/main.js"></script>
	<script type="text/javascript">
		$(document).ready(function(){
			$('#department_name').focus();
		});
	</script>
  </body>
</html>
