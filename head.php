<?php

include "config.php";

if($_SESSION['file_tracking_username']) {
	$username = $_SESSION['file_tracking_username'];
	function is_admin() {
		global $conn, $username;
		$query = mysqli_query($conn, "SELECT * FROM users WHERE username='$username'");
		if(mysqli_num_rows($query) > 0) {
			$result = mysqli_fetch_assoc($query);
			if($result['role'] == 0) {
				return true;
			} else {
				return false;
			}
		}
	}
	function user_id() {
		global $conn, $username;
		$query = mysqli_query($conn, "SELECT * FROM users WHERE username='$username'");
		if(mysqli_num_rows($query) > 0) {
			$result = mysqli_fetch_assoc($query);
			return $result['id'];
		}
	}
	$user_id = user_id();

	function department_id() {
		global $conn, $username;
		$query = mysqli_query($conn, "SELECT * FROM users WHERE username='$username'");
		if(mysqli_num_rows($query) > 0) {
			$result = mysqli_fetch_assoc($query);
			return $result['department_id'];
		}
	}
	$department_id = department_id();

	function department_name($department_id) {
		global $conn, $username;
		$query = mysqli_query($conn, "SELECT * FROM departments WHERE id='$department_id'");
		if(mysqli_num_rows($query) > 0) {
			$result = mysqli_fetch_assoc($query);
			return $result['name'];
		}
	}

	function department_slug($department_id) {
		global $conn, $username;
		$query = mysqli_query($conn, "SELECT * FROM departments WHERE id='$department_id'");
		if(mysqli_num_rows($query) > 0) {
			$result = mysqli_fetch_assoc($query);
			return $result['slug'];
		}
	}

	function display_name() {
		global $conn, $username;
		$query = mysqli_query($conn, "SELECT * FROM users WHERE username='$username'");
		if(mysqli_num_rows($query) > 0) {
			$result = mysqli_fetch_assoc($query);
			return $result['display_name'];
		}
	}
} else {
	unset($_SESSION['file_tracking_username']);
	header('location: login.php');
}

?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="">
	<meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
	<meta name="generator" content="Hugo 0.101.0">
	<title>File Tracking · TIP</title>

	<link rel="canonical" href="https://getbootstrap.com/docs/5.2/examples/dashboard/">

	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet">

	<meta name="theme-color" content="#712cf9">


	<style>
		body {
			min-height: 100vh;
			max-height: auto !important;
			height: 100%;
		}
		.sidebar {
			min-height: 100vh;
			max-height: auto;
		}
		.bd-placeholder-img {
			font-size: 1.125rem;
			text-anchor: middle;
			-webkit-user-select: none;
			-moz-user-select: none;
			user-select: none;
		}

		@media (min-width: 768px) {
			.bd-placeholder-img-lg {
				font-size: 3.5rem;
			}
		}

		.b-example-divider {
			height: 3rem;
			background-color: rgba(0, 0, 0, .1);
			border: solid rgba(0, 0, 0, .15);
			border-width: 1px 0;
			box-shadow: inset 0 .5em 1.5em rgba(0, 0, 0, .1), inset 0 .125em .5em rgba(0, 0, 0, .15);
		}

		.b-example-vr {
			flex-shrink: 0;
			width: 1.5rem;
			height: 100vh;
		}

		.bi {
			vertical-align: -.125em;
			fill: currentColor;
		}

		.nav-scroller {
			position: relative;
			z-index: 2;
			height: 2.75rem;
			overflow-y: hidden;
		}

		.nav-scroller .nav {
			display: flex;
			flex-wrap: nowrap;
			padding-bottom: 1rem;
			margin-top: -1px;
			overflow-x: auto;
			text-align: center;
			white-space: nowrap;
			-webkit-overflow-scrolling: touch;
		}
	</style>

	
	<!-- Custom styles for this template -->
	<link href="assets/style.css" rel="stylesheet">
</head>
<body>
	<header class="navbar navbar-dark bg-dark flex-md-nowrap p-0 shadow">
		<a class="navbar-brand col-md-3 col-lg-2 me-0 px-3 fs-6" href="#">Telephone Industries of Pakistan</a>
		<button class="navbar-toggler d-lg-none collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu">
			<span class="navbar-toggler-icon"></span>
		</button>

	</header>

	<div class="container-fluid">
		<div class="row">
			<nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-dark sidebar collapse">
				<div class="position-sticky pt-3 sidebar-sticky navbar navbar-dark">
					<div class="container-fluid">

						<div class="" id="sidebar">
							<ul class="navbar-nav me-auto mb-2 mb-lg-0 flex-column">
								<li class="nav-item"><a class="nav-link active" href="index.php"><span data-feather="home" class="align-text-bottom"></span>Dashboard</a></li>
								<li class="nav-item dropdown">
									<a class="nav-link dropdown-toggle" href="index.php" role="button" data-bs-toggle="dropdown"><span data-feather="file-text"></span>Files</a>
									<ul class="dropdown-menu">
										<li><a class="dropdown-item" href="index.php">All Files</a></li>
										<?php
										if(!is_admin()) { echo '<li><a class="dropdown-item" href="new-file.php">New File</a></li>'; }
										?>
									</ul>
								</li>
								<?php if(is_admin()) { ?>
								<li class="nav-item dropdown">
									<a class="nav-link dropdown-toggle" href="departments.php" role="button" data-bs-toggle="dropdown"><span data-feather="table"></span>Departments</a>
									<ul class="dropdown-menu">
										<li><a class="dropdown-item" href="departments.php">All Departments</a></li>
										<li><a class="dropdown-item" href="new-department.php">New Department</a></li>
									</ul>
								</li>
								<?php } ?>
								<?php if(is_admin()) { ?>
								<li class="nav-item dropdown">
									<a class="nav-link dropdown-toggle" href="users.php" role="button" data-bs-toggle="dropdown"><span data-feather="users"></span>Users</a>
									<ul class="dropdown-menu">
										<li><a class="dropdown-item" href="users.php">All Users</a></li>
										<li><a class="dropdown-item" href="new-user.php">New User</a></li>
									</ul>
								</li>
								<?php } ?>
								<li class="nav-item"><a class="nav-link" href="logout.php"><span data-feather="logout" class="align-text-bottom"></span>Sign Out</a></li>
							</ul>

						</div>
					</div>

				</div>
			</nav>