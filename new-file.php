<?php

include "head.php";

$yesterday = strtotime('yesterday');
$now = strtotime('now');

$file_number_total_query = mysqli_query($conn, "SELECT * FROM files WHERE user_id='$user_id' && time_created BETWEEN $yesterday AND $now");
$file_number_total = mysqli_num_rows($file_number_total_query);

$file_number_total++;

?>

			<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
				<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
					<h1 class="h2">Welcome <?php echo display_name(); ?></h1>
				</div>

				<div class="title">
					<h2 class="d-inline">New Files</h2>
					<a href="index.php" class="btn btn-dark mb-3 ms-2">Back</a>
				</div>
				<form method="post">

					<?php

					if(isset($_POST['submit'])) {
						$file_number = mysqli_real_escape_string($conn, $_POST['file_number']);
						$file_subject = mysqli_real_escape_string($conn, $_POST['file_subject']);
						$file_type = mysqli_real_escape_string($conn, $_POST['file_type']);
						$receiver_department_id = mysqli_real_escape_string($conn, $_POST['receiver_department_id']);
						$carrier_person_name = mysqli_real_escape_string($conn, $_POST['carrier_person_name']);

						if($file_number != '' && $file_subject != '' && $file_type != '' && $receiver_department_id != '' && $carrier_person_name != '') {
							$query = mysqli_query($conn, "INSERT INTO files(file_subject, user_id, department_id, current_department_id, file_number, type, time_created) VALUES('$file_subject', '$user_id', '$department_id', '$receiver_department_id', '$file_number', '$file_type', '$time_created')");
							$file_id = mysqli_insert_id($conn);
							$tracking_query = mysqli_query($conn, "INSERT INTO tracking(file_id, sender_department_id, receiver_department_id, sender_person, carrier_person_name, sender_time_created) VALUES('$file_id', '$department_id', '$receiver_department_id', '$user_id', '$carrier_person_name', '$time_created')");
							if($query && $tracking_query) {
								echo "<div class='alert alert-success'>File is Successfully Created.</div>";
							} else {
								echo "<div class='alert alert-danger'>Please Try Again</div>";
							}
						} else {
							echo "<div class='alert alert-danger'>Please Fill All Required Fields</div>";
						}
					}

					?>
					
					<div class="mb-3">
						<label>Referance Number:</label>
						<input type="text" class="form-control" placeholder="Enter Referance Number" name="file_number">
					</div>
					<div class="mb-3">
						<label>Subject:</label>
						<input type="text" class="form-control" placeholder="Enter File Subject" name="file_subject">
					</div>
					<div class="mb-3">
						<label>File Type:</label>
						<select class="form-select" name="file_type">
							<option value="11">Mintue Sheet</option>
							<option value="12">Letter</option>
						</select>
					</div>
					<div class="mb-3">
						<label>Carrier Person Name:</label>
						<input type="text" class="form-control" placeholder="Enter Carrier Person Name" name="carrier_person_name">
					</div>
					<div class="mb-3">
						<label>Receiver Department:</label>
						<select class="form-select" name="receiver_department_id">
							<option value="">Select Department</option>
							<?php

							$department_query = mysqli_query($conn, "SELECT * FROM departments WHERE id!='$department_id'");
							if(mysqli_num_rows($department_query) > 0) {
								while($department_result = mysqli_fetch_assoc($department_query)) {
									echo "<option value='".$department_result['id']."'>".$department_result['name']."</option>";
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
