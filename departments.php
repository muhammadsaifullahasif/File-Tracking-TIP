<?php include "head.php"; ?>

			<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
				<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
					<h1 class="h2">Welcome <?php echo display_name(); ?></h1>
				</div>

				<div class="title">
					<h2 class="d-inline">All Departments</h2>
					<a href="new-department.php" class="btn btn-dark mb-3 ms-2">Add Department</a>
				</div>
				
				<div id="msg" class="mb-3"></div>
				<div class="table-responsive">
					<table class="table table-striped table-hover">
						<thead>
							<tr>
								<th>#</th>
								<th>Name</th>
								<th>Slug</th>
								<th>Action</th>
							</tr>
						</thead>
						<tfoot>
							<tr>
								<th>#</th>
								<th>Name</th>
								<th>Slug</th>
								<th>Action</th>
							</tr>
						</tfoot>
						<tbody id="display_departments">
							
						</tbody>
					</table>
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
			function display_departments() {
				$.ajax({
					url: 'ajax.php',
					type: 'POST',
					data: { action:'display_departments' },
					success: function(result) {
						$('#display_departments').html(result);
					}
				});
			}
			display_departments();

			$(document).on('click', '.delete_department_btn', function(){
				var id = $(this).data('id');
				if(id != '' && id != 0) {
					if(confirm('Are you sure to delete departments?')) {
						$.ajax({
							url: 'ajax.php',
							type: 'POST',
							data: { action:'delete_department', id:id },
							success: function(result) {
								if(result == 2) {
									display_departments();
									$('#msg').removeClass('alert-danger').addClass('alert alert-success alert-dismissible fade show').html('Department is Successfully Deleted<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>');
								} else if(result == 1 || result == 0) {
									display_departments();
									$('#msg').removeClass('alert-success').addClass('alert alert-danger alert-dismissible fade show').html('Please Try Again<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>');
								}
							}
						});
					}
				}
			});
		});
	</script>
  </body>
</html>
