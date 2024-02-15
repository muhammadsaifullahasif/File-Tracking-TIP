<?php include "head.php"; ?>

			<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
				<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
					<h1 class="h2">Welcome <?php echo display_name(); ?></h1>
				</div>

				<div class="title">
					<h2 class="d-inline">Track File</h2>
					<a href="new-file.php" class="btn btn-dark mb-3 ms-2">All File</a>
				</div>
				<div id="msg" class="mb-3"></div>
				<div class="table-responsive">
					<table class="table table-striped table-sm">
						<thead>
							<tr>
								<th scope="col">#</th>
								<th scope="col">File Number</th>
								<th scope="col">Subject</th>
								<th scope="col">Initiator</th>
								<th scope="col">Current Department</th>
								<th scope="col">Date Created</th>
								<th scope="col">Actions</th>
							</tr>
						</thead>
						<tfoot>
							<tr>
								<th scope="col">#</th>
								<th scope="col">File Number</th>
								<th scope="col">Subject</th>
								<th scope="col">Initiator</th>
								<th scope="col">Current Department</th>
								<th scope="col">Date Created</th>
								<th scope="col">Actions</th>
							</tr>
						</tfoot>
						<tbody id="display_files">
						</tbody>
					</table>
				</div>

				<div class="modal fade" id="send_file">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<h5>Sending File</h5>
								<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
							</div>
							<div class="modal-body">
								<form class="form" method="post" id="send_file_form">
									<div id="send_file_msg" class="mb-3"></div>
									<input type="hidden" value="" id="send_file_id" name="send_file_id">
									<div class="mb-3">
										<label>Carrier Person:</label>
										<input type="text" class="form-control" placeholder="Enter Carrier Person Name" name="carrier_person_name" id="carrier_person_name">
									</div>
									<div class="mb-3">
										<label>Sending to Department:</label>
										<select class="form-select" name="receiver_department_id" id="receiver_department_id">
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
									<button class="btn btn-success" type="submit" name="submit" id="submit">Submit</button>
								</form>
							</div>
						</div>
					</div>
				</div>
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
			display_files(<?php echo user_id(); ?>);

		});		
	</script>
  </body>
</html>
