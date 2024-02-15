<?php include "head.php"; ?>

			<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
				<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
					<h1 class="h2">Welcome <?php echo display_name(); ?></h1>
				</div>

				<div class="title">
					<h2 class="d-inline">All Files</h2>
					<?php

					if(!is_admin()) {
						echo '<a href="new-file.php" class="btn btn-dark mb-3 ms-2">New File</a>';
					}

					?>
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
								<th scope="col">Status</th>
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
								<th scope="col">Status</th>
								<th scope="col">Actions</th>
							</tr>
						</tfoot>
						<tbody id="display_files">
						</tbody>
					</table>
				</div>

				<div class="modal fade" id="track_file">
					<div class="modal-dialog modal-xl">
						<div class="modal-content">
							<div class="modal-header">
								<h5>Track File</h5>
								<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
							</div>
							<div class="modal-body">
								<div class="table-responsive">
									<table class="table table-striped table-sm table-hover table-bordered">
										<thead>
											<tr>
												<th></th>
												<th colspan="3" class="text-center table-primary">Sender Detail</th>
												<th colspan="3" class="text-center table-danger">Receiver Detail</th>
												<th colspan="3" class="text-center table-warning">Reject Detail</th>
											</tr>
											<tr>
												<th>#</th>
												<th class="table-primary">Sender Department</th>
												<th class="table-primary">Carrier Person</th>
												<th class="table-primary">Send Time</th>
												<th class="table-danger">Receiver Department</th>
												<th class="table-danger">Receiver</th>
												<th class="table-danger">Receive Time</th>
												<th class="table-warning">Reject Department</th>
												<th class="table-warning">Remarks</th>
											</tr>
										</thead>
										<tbody id="track_file_table"></tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="modal fade" id="reject_file_modal">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<h5>Remarks</h5>
								<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
							</div>
							<div class="modal-body">
								<form class="form" method="post" id="reject_file_form">
									<input type="hidden" value="" id="reject_file_id" name="reject_file_id">
									<div id="reject_file_form_msg" class="mb-3"></div>
									<div class="mb-3">
										<label>Remarks</label>
										<textarea class="form-control" placeholder="Enter Remarks" id="reject_file_remarks" name="reject_file_remarks"></textarea>
									</div>
									<button class="btn btn-primary" id="reject_file_form_btn" type="submit" name="reject_file_form_btn">Submit</button>
								</form>
							</div>
						</div>
					</div>
				</div>

				<div class="modal fade" id="send_file">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<h5>Sending File</h5>
								<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
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
			display_files();

			$(document).on('click', '.cancel_file_btn', function() {
				var id = $(this).data('id');
				if(id != '' && id != 0) {
					if(confirm('Are you sure to cancel this tracking?')) {
						$.ajax({
							url: 'ajax.php',
							type: 'POST',
							data: { action:'cancel_file', id:id },
							success: function(result) {
								if(result == 2) {
									display_files();
									$('#msg').removeClass('alert-danger').addClass('alert alert-success alert-dismissible fade show').html('File is Successfully Cancelled<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>');
								} else if(result == 1 || result == 0) {
									display_files();
									$('#msg').removeClass('alert-success').addClass('alert alert-danger alert-dismissible fade show').html('Please Try Again<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>');
								}
							}
						});
					}
				}
			});

			$('#reject_file_form').on('submit', function(e){
				e.preventDefault();
				reject_file_id = $('#reject_file_id').val();
				reject_file_remarks = $('#reject_file_remarks').val();
				var bool = 0;

				if(reject_file_id != 0 && reject_file_id != '') {
					if(reject_file_remarks != '') {
						$.ajax({
							url: 'ajax.php',
							type: 'POST',
							data: { action:'reject_file', reject_file_id:reject_file_id, reject_file_remarks:reject_file_remarks },
							success: function(result) {
								if(result == 2) {
									display_files(<?php echo user_id(); ?>);
									$('#reject_file_form_msg').removeClass('alert-danger').addClass('alert alert-success alert-dismissible fade show').html('File is Rejected Successfully<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>');
									$('#reject_file_remarks').val('');
								} else if(result == 1 || result == 0) {
									display_files(<?php echo user_id(); ?>);
									$('#reject_file_form_msg').removeClass('alert-danger').addClass('alert alert-danger alert-dismissible fade show').html('Please Try Again<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>');
								}
							}
						});
					} else {
						alert('Please Fill Remarks');
					}
				}
			});

			$(document).on('click', '.reject_file_btn', function(){
				var id = $(this).data('id');
				if(id != '' && id != 0) {
					$('#reject_file_id').val(id);
					$('#reject_file_modal').modal('show');
					$('#reject_file_modal').on('shown.bs.modal', function(){
						$('#reject_file_remarks').val('').focus();
					});
				}
			});

			$(document).on('click', '.delete_file_btn', function(){
				var id = $(this).data('id');
				if(id != '' && id != 0) {
					if(confirm('Are you sure to delete file?')) {
						$.ajax({
							url: 'ajax.php',
							type: 'POST',
							data: { action:'delete_file', id:id },
							success: function(result) {
								if(result == 2) {
									display_files(<?php echo user_id(); ?>);
									$('#msg').removeClass('alert-danger').addClass('alert alert-success alert-dismissible fade show').html('File is Successfully Deleted<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>');
								} else if(result == 1 || result == 0) {
									display_files();
									$('#msg').removeClass('alert-success').addClass('alert alert-danger alert-dismissible fade show').html('Please Try Again<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>');
								}
							}
						});
					}
				}
			});

			$(document).on('click', '.track_file_btn', function(){
				var file_id = $(this).data('id');
				if(file_id != '' && file_id != 0) {
					$.ajax({
						url: 'ajax.php',
						type: 'POST',
						data: { action:'track_file_table', file_id:file_id },
						success: function(result) {
							$('#track_file').modal('show');
							$('#track_file_table').html(result);
						}
					});
				}
			});

			$('#send_file_form').on('submit', function(e){
				e.preventDefault();
				var send_file_id = $('#send_file_id').val();
				var carrier_person_name = $('#carrier_person_name').val();
				var receiver_department_id = $('#receiver_department_id').val();
				if(send_file_id != '' && carrier_person_name != '' && receiver_department_id != '') {
					$.ajax({
						url: 'ajax.php',
						type: 'POST',
						data: { action:'send_file', send_file_id:send_file_id, carrier_person_name:carrier_person_name, receiver_department_id:receiver_department_id },
						success: function(result) {
							if(result == 2) {
								$('#send_file_msg').removeClass('alert-danger').addClass('alert alert-success alert-dismissible fade show').html('File is Successfully sended<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>');
								display_files();
								$('#send_file').modal('hide');
							} else if(result == 1) {
								$('#send_file_msg').removeClass('alert-success').addClass('alert alert-danger alert-dismissible fade show').html('Please Try Again<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>');
							} else if(result == 0) {
								$('#send_file_msg').removeClass('alert-success').addClass('alert alert-danger alert-dismissible fade show').html('Please Fill Required Fields<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>');
							}
						}
					});
				}
			});

			$(document).on('click', '.receive_file_btn', function(e){
				var id = $(this).data('id');
				if(id != '' && id != 0) {
					if(confirm('Are you sure to Receive file?')) {
						$.ajax({
							url: 'ajax.php',
							type: 'POST',
							data: {action: 'receive_file', id:id},
							success: function(result) {
								if(result == 1) {
									$('#msg').removeClass('alert-danger').addClass('alert alert-success alert-dismissible fade show').html('File Received Successfully<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>');
									display_files();
								} else if(result == 2) {
									$('#msg').removeClass('alert-success').addClass('alert alert-danger alert-dismissible fade show').html('Please Try Again<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>');
								}
							}
						});
					}
				}
			});

			$(document).on('click', '.send_file_btn', function(){
				var file_id = $(this).data('id');
				$('#send_file').modal('show');
				$('#send_file_id').val(file_id);
			});
		});		
	</script>
  </body>
</html>
