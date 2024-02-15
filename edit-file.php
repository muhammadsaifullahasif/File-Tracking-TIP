<?php

include "head.php";

if(isset($_GET['id']) && $_GET['id'] != 0 && $_GET['id'] != '') {
	$id = mysqli_real_escape_string($conn, $_GET['id']);

	$data_query = mysqli_query($conn, "SELECT * FROM files WHERE id='$id'");
	if(mysqli_num_rows($data_query) > 0) {
		$result = mysqli_fetch_assoc($data_query);
	} else {
		header("location: files.php");
	}
} else {
	header("location: files.php");
}

?>

			<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
				<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
					<h1 class="h2">Welcome <?php echo display_name(); ?></h1>
				</div>

				<div class="title">
					<h2 class="d-inline">Edit Files</h2>
					<a href="index.php" class="btn btn-dark mb-3 ms-2">Back</a>
				</div>
				<form method="post">

					<?php

					if(isset($_POST['submit'])) {
						$file_subject = mysqli_real_escape_string($conn, $_POST['file_subject']);
						$file_description = mysqli_real_escape_string($conn, $_POST['file_description']);
						$file_type = mysqli_real_escape_string($conn, $_POST['file_type']);

						if($file_subject != '' && $file_type != '' && $carrier_person_name != '') {
							$query = mysqli_query($conn, "INSERT INTO files(file_subject, file_description, user_id, department_id, current_department_id, file_number, type, time_created) VALUES('$file_subject', '$file_description', '$user_id', '$department_id', '$receiver_department_id', '$file_number', '$file_type', '$time_created')");
							$query = mysqli_query($conn, "UPDATE files SET file_subject='$file_subject', file_description='$file_description', file_type='$file_type' WHERE id='$id'");
							if($query) {
								echo "<div class='alert alert-success'>File is Successfully Updated.</div>";
							} else {
								echo "<div class='alert alert-danger'>Please Try Again</div>";
							}
						} else {
							echo "<div class='alert alert-danger'>Please Fill All Required Fields</div>";
						}
					}

					?>
					
					<div class="mb-3">
						<label>Subject:</label>
						<input type="text" class="form-control" value="<?php echo $result['file_subject']; ?>" placeholder="Enter File Subject" name="file_subject">
					</div>
					<div class="mb-3">
						<label>Description:</label>
						<textarea class="form-control" placeholder="Enter File Description" name="file_description"><?php echo $result['file_description']; ?></textarea>
					</div>
					<div class="mb-3">
						<label>File Type:</label>
						<select class="form-select" name="file_type">
							<option value="11" <?php if($result['file_type'] == 11) echo 'selected'; ?>>Mintue Sheet</option>
							<option value="12" <?php if($result['file_type'] == 11) echo 'selected'; ?>>Letter</option>
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
