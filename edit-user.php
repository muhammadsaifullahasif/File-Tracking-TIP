<?php

include "head.php";

if(isset($_GET['id']) && $_GET['id'] != 0 && $_GET['id'] != '') {
	$id = mysqli_real_escape_string($conn, $_GET['id']);

	$data_query = mysqli_query($conn, "SELECT * FROM users WHERE id='$id'");
	if(mysqli_num_rows($data_query) > 0) {
		$result = mysqli_fetch_assoc($data_query);
	} else {
		header("location: users.php");
	}
} else {
	header("location: users.php");
}

?>

			<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
				<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
					<h1 class="h2">Welcome <?php echo display_name(); ?></h1>
				</div>

				<div class="title">
					<h2 class="d-inline">Edit User</h2>
					<a href="users.php" class="btn btn-dark mb-3 ms-2">Back</a>
				</div>
				<form method="post">

					<?php

					if(isset($_POST['submit'])) {
						$password = mysqli_real_escape_string($conn, $_POST['password']);
						$display_name = mysqli_real_escape_string($conn, $_POST['display_name']);
						$user_department_id = mysqli_real_escape_string($conn, $_POST['user_department_id']);

						if($password != '' && $display_name != '' && $user_department_id) {
							$query = mysqli_query($conn, "UPDATE users SET password='$password', display_name='$display_name', department_id='$user_department_id' WHERE id='$id'");
							if($query) {
								echo "<div class='alert alert-success'>User is Successfully Updated</div>";
							} else {
								echo "<div class='alert alert-danger'>Please Try Again</div>";
							}
						} else {
							echo "<div class='alert alert-danger'>Fill All Required Fields</div>";
						}
					}

					?>
					
					<div class="mb-3">
						<label>Username:</label>
						<input type="text" readonly class="form-control" value="<?php echo $result['username']; ?>" placeholder="Enter Username" name="username" id="username">
					</div>
					<div class="mb-3">
						<label>Password:</label>
						<input type="password" class="form-control" value="<?php echo $result['password']; ?>" placeholder="Enter Password" name="password">
					</div>
					<div class="mb-3">
						<label>Display Name:</label>
						<input type="text" class="form-control" value="<?php echo $result['display_name']; ?>" placeholder="Enter Name" name="display_name">
					</div>
					<div class="mb-3">
						<label>Department:</label>
						<select class="form-select" name="user_department_id">
							<option value="">Select Department</option>
							<?php

							$department_query = mysqli_query($conn, "SELECT * FROM departments");
							if(mysqli_num_rows($department_query) > 0) {
								while($department_result = mysqli_fetch_assoc($department_query)) {
									if($result['department_id'] == $department_result['id']) { $selected = 'selected'; } else { $selected = ''; }
									echo "<option ".$selected." value='".$department_result['id']."'>".$department_result['name']."</option>";
								}
							}

							?>
						</select>
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
  </body>
</html>
