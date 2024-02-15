<?php

include "config.php";

$username = $_SESSION['file_tracking_username'];

function user_id() {
	global $conn, $username;
	$query = mysqli_query($conn, "SELECT * FROM users WHERE username='$username'");
	if(mysqli_num_rows($query) > 0) {
		$result = mysqli_fetch_assoc($query);
		return $result['id'];
	}
}
$user_id = user_id();

function user_name($user_id) {
	global $conn;
	$query = mysqli_query($conn, "SELECT * FROM users WHERE id='$user_id'");
	if(mysqli_num_rows($query) > 0) {
		$result = mysqli_fetch_assoc($query);
		return $result['display_name'];
	}
}

function department_name($department_id) {
	global $conn;
	$query = mysqli_query($conn, "SELECT * FROM departments WHERE id='$department_id'");
	if(mysqli_num_rows($query) > 0) {
		$result = mysqli_fetch_assoc($query);
		return $result['name'];
	}
}

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

function department_id() {
	global $conn, $username;
	$query = mysqli_query($conn, "SELECT * FROM users WHERE username='$username'");
	if(mysqli_num_rows($query) > 0) {
		$result = mysqli_fetch_assoc($query);
		return $result['department_id'];
	}
}

$user_department_id = department_id();


if(isset($_POST['action']) && $_POST['action'] == 'display_files') {

	if(is_admin()) {
		$query = mysqli_query($conn, "SELECT DISTINCT(t.file_id), f.id, f.file_subject, f.user_id, f.department_id, f.current_department_id, f.file_number, f.type, f.time_created FROM files f INNER JOIN tracking t ON f.id=t.file_id ORDER BY time_created DESC");
	} else {
		$query = mysqli_query($conn, "SELECT DISTINCT(t.file_id), f.id, f.file_subject, f.user_id, f.department_id, f.current_department_id, f.file_number, f.type, f.time_created FROM files f INNER JOIN tracking t ON f.id=t.file_id WHERE f.user_id='$user_id' || t.sender_department_id='$user_department_id' || t.receiver_department_id='$user_department_id' ORDER BY time_created DESC");
	}

	$output = '';

	if(mysqli_num_rows($query) > 0) {
		$i = 1;
		while($result = mysqli_fetch_assoc($query)) {
			$file_id = $result['id'];
			$output .= "<tr>";
			$output .= "<td>".$i."</td>";
			$output .= "<td>".$result['file_number']."</td>";
			$output .= "<td>".$result['file_subject']."</td>";
			$output .= "<td>".department_name($result['department_id'])."</td>";
			$output .= "<td>".department_name($result['current_department_id'])."</td>";
			$output .= "<td>".date('d-m-Y', $result['time_created'])."</td>";
			$tracking_query = mysqli_query($conn, "SELECT * FROM tracking WHERE file_id='$file_id' ORDER BY id DESC LIMIT 1");
			if(mysqli_num_rows($tracking_query) > 0) {
				$tracking_result = mysqli_fetch_assoc($tracking_query);
				$output .= "<td>";
				if($tracking_result['receiver_time_created'] == '' && $tracking_result['reject_time_created'] == '') {
					$output .= "<span class='badge text-bg-warning'>Not Received</span>";
				} else if($tracking_result['reject_time_created'] != '') {
					$output .= "<span class='badge text-bg-danger'>Rejected</span>";
				} else if($tracking_result['receiver_time_created'] != '' && $tracking_result['reject_time_created'] == '') {
					$output .= "<span class='badge text-bg-success'>Received</span>";
				} else if($tracking_result['receiver_time_created'] != '' && $tracking_result['reject_time_created'] != '') {
					$output .= "<span class='badge text-bg-danger'>Rejected</span>";
				}
				$output .= "</td>";
				$output .= "<td><div class='btn-group'>";

				if($tracking_result['receiver_department_id'] == $user_department_id) {
					if(!is_admin()) {
						if($tracking_result['receiver_time_created'] == '' && $tracking_result['reject_time_created'] == '') {
							$output .= "<button class='btn btn-danger reject_file_btn' data-id='".$tracking_result['id']."'>Reject</button>";
							$output .= "<button class='btn btn-success receive_file_btn' data-id='".$tracking_result['id']."'>Receive</button>";
						} else if($tracking_result['reject_time_created'] == '' && $tracking_result['receiver_time_created'] != '') {
							$output .= "<button class='btn btn-success send_file_btn' data-id='".$file_id."'>Send</button>";
						} else if($tracking_result['reject_time_created'] != '' && $tracking_result['receiver_time_created'] != '') {
							$output .= "<button class='btn btn-success send_file_btn' data-id='".$file_id."'>Send</button>";
						}
					}
				} else if($tracking_result['sender_department_id'] == $user_department_id) {
					if($tracking_result['receiver_time_created'] == '' && $tracking_result['reject_time_created'] == '') {
						$output .= "<button class='btn btn-danger cancel_file_btn' data-id='".$tracking_result['id']."'>Cancel</button>";
					}
				}

			}
			$output .= "<button data-id='".$file_id."' class='btn btn-primary track_file_btn'>Get Tracking</button>";
			$tracking_total = mysqli_query($conn, "SELECT * FROM tracking WHERE file_id='$file_id'");
			if(mysqli_num_rows($tracking_total) == 1) {
				if($result['user_id'] == $user_id && $tracking_result['receiver_time_created'] == '') {
					$output .= "<button class='btn btn-danger delete_file_btn' data-id='".$result['id']."'>Delete</button>";
				}
			}
			$output .= "</div></td>";
			$output .= "</tr>";
			$i++;
		}
	} else {
		$output .= "<tr><td colspan='8' class='text-center'>No Record Found</td></tr>";
	}

	echo $output;
}

if(isset($_POST['action']) && $_POST['action'] == 'delete_file') {
	$id = mysqli_real_escape_string($conn, $_POST['id']);
	if($id != '' && $id != 0) {
		$query = mysqli_query($conn, "DELETE FROM files WHERE id='$id'");
		$tracking = mysqli_query($conn, "DELETE FROM tracking WHERE file_id='$id'");
		if($query && $tracking) {
			echo 2;
		} else {
			echo 1;
		}
	} else {
		echo 0;
	}
}

if(isset($_POST['action']) && $_POST['action'] == 'cancel_file') {
	$id = mysqli_real_escape_string($conn, $_POST['id']);
	if($id != '' && $id != 0) {
		$check_query = mysqli_query($conn, "SELECT * FROM tracking WHERE id='$id'");
		if(mysqli_num_rows($check_query) > 0) {
			$check_result = mysqli_fetch_assoc($check_query);
			$query = mysqli_query($conn, "DELETE FROM tracking WHERE id='$id'");
			$current_department_query = mysqli_query($conn, "UPDATE files SET current_department_id='$user_department_id' WHERE id='{$check_result['file_id']}'");
			if($query && $current_department_query) {
				echo 2;
			} else {
				echo 1;
			}
		}
	}
}


if(isset($_POST['action']) && $_POST['action'] == 'reject_file') {
	$reject_file_id = mysqli_real_escape_string($conn, $_POST['reject_file_id']);
	$reject_file_remarks = mysqli_real_escape_string($conn, $_POST['reject_file_remarks']);

	if($reject_file_id != 0 && $reject_file_id != '' && $reject_file_remarks != '') {
		$check_query = mysqli_query($conn, "SELECT * FROM tracking WHERE id='$reject_file_id'");
		if(mysqli_num_rows($check_query) > 0) {
			$check_result = mysqli_fetch_assoc($check_query);
			$query = mysqli_query($conn, "UPDATE tracking SET reject_remarks='$reject_file_remarks', reject_time_created='$time_created', reject_department_id='$user_department_id', receiver_time_created='$time_created', receiver_department_id='{$check_result['sender_department_id']}', receiver_person='{$check_result['sender_person']}' WHERE id='$reject_file_id'");
			$current_department_query = mysqli_query($conn, "UPDATE files SET current_department_id='{$check_result['sender_department_id']}' WHERE id='{$check_result['file_id']}'");
			if($query) {
				echo 2;
			} else {
				echo 1;
			}
		}
	}
}


if(isset($_POST['action']) && $_POST['action'] == 'receive_file') {
	$id = mysqli_real_escape_string($conn, $_POST['id']);

	$query = mysqli_query($conn, "UPDATE tracking SET receiver_person='$user_id', receiver_time_created='$time_created' WHERE id='$id'");
	if($query) {
		echo 1;
	} else {
		echo 0;
	}
}


if(isset($_POST['action']) && $_POST['action'] == 'send_file') {
	$send_file_id = mysqli_real_escape_string($conn, $_POST['send_file_id']);
	$carrier_person_name = mysqli_real_escape_string($conn, $_POST['carrier_person_name']);
	$receiver_department_id = mysqli_real_escape_string($conn, $_POST['receiver_department_id']);

	if($send_file_id != '' && $carrier_person_name != '' && $receiver_department_id != '') {
		$query = mysqli_query($conn, "INSERT INTO tracking(file_id, sender_department_id, receiver_department_id, sender_person, carrier_person_name, sender_time_created) VALUES('$send_file_id', '$user_department_id', '$receiver_department_id', '$user_id', '$carrier_person_name', '$time_created')");
		$current_department_query = mysqli_query($conn, "UPDATE files SET current_department_id='$receiver_department_id' WHERE id='$send_file_id'");
		if($query && $current_department_query) {
			echo 2;
		} else {
			echo 1;
		}
	} else {
		echo 0;
	}
}


if(isset($_POST['action']) && $_POST['action'] == 'track_file_table') {
	$file_id = mysqli_real_escape_string($conn, $_POST['file_id']);

	$query = mysqli_query($conn, "SELECT * FROM tracking WHERE file_id='$file_id'");
	$output = '';

	if(mysqli_num_rows($query) > 0) {
		$i = 1;
		while($result = mysqli_fetch_assoc($query)) {
			$output .= "<tr>";
			$output .= "<td>".$i."</td>";
			$output .= "<td class='table-primary'>".department_name($result['sender_department_id'])."</td>";
			$output .= "<td class='table-primary'>".$result['carrier_person_name']."</td>";
			$output .= "<td class='table-primary'>".date('d-m-Y H:i', $result['sender_time_created'])."</td>";
			$output .= "<td class='table-danger'>".department_name($result['receiver_department_id'])."</td>";
			$output .= "<td class='table-danger'>".user_name($result['receiver_person'])."</td>";
			$output .= "<td class='table-danger'>";
			if($result['receiver_time_created'] != '') {
				$output .= date('d-m-Y H:i', $result['receiver_time_created']);
			} else {
				$output .= "No Received";
			}
			$output .= "</td>";
			$output .= "<td class='table-warning'>".department_name($result['reject_department_id'])."</td>";
			$output .= "<td class='table-warning'>".$result['reject_remarks']."</td>";
			$output .= "</tr>";
			$i++;
		}
	}

	echo $output;
}




if(isset($_POST['action']) && $_POST['action'] == 'display_users') {
	$query = mysqli_query($conn, "SELECT * FROM users WHERE active_status='1' && delete_status='0'");
	$output = '';
	if(mysqli_num_rows($query) > 0) {
		$i = 1;
		while($result = mysqli_fetch_assoc($query)) {
			$id = $result['id'];
			$output .= "<tr>";
			$output .= "<td>".$i."</td>";
			$output .= "<td>".$result['username']."</td>";
			$output .= "<td>".$result['display_name']."</td>";
			$output .= "<td>".department_name($result['department_id'])."</td>";
			$output .= "<td>";
			if($result['role'] == 0) {
				$output .= "Admin";
			} else {
				$output .= "User";
			}
			$output .= "</td>";
			$output .= "<td>".date('d-m-Y', $result['time_created'])."</td>";
			$output .= "<td><div class='btn-group'>";
			$output .= "<a class='btn btn-primary' href='edit-user.php?id=".$result['id']."'>Edit</a>";
			if($result['role'] != 0) {
				$user_file_total = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM files WHERE user_id='$id'"));
				$user_tracking_total = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM tracking WHERE sender_person='$id' || receiver_person='$id'"));
				if($user_file_total == 0 && $user_tracking_total == 0) {
					$output .= "<button class='btn btn-danger delete_user_btn' data-id='".$id."'>Delete</button>";
				}
			}
			$output .= "</div></td>";
			$output .= "</tr>";
			$i++;
		}
	} else {
		$output .= "<tr><td colspan='4' class='text-center'>No Record Found</td></tr>";
	}

	echo $output;
}

if(isset($_POST['action']) && $_POST['action'] == 'delete_user') {
	$id = mysqli_real_escape_string($conn, $_POST['id']);
	if($id != '' && $id != 0) {
		$query = mysqli_query($conn, "DELETE FROM users WHERE id='$id'");
		if($query) {
			echo 2;
		} else {
			echo 1;
		}
	} else {
		echo 0;
	}
}




if(isset($_POST['action']) && $_POST['action'] == 'display_departments') {
	$query = mysqli_query($conn, "SELECT * FROM departments");
	$output = '';
	if(mysqli_num_rows($query) > 0) {
		$i = 1;
		while($result = mysqli_fetch_assoc($query)) {
			$id = $result['id'];
			$output .= "<tr>";
			$output .= "<td>".$i."</td>";
			$output .= "<td>".$result['name']."</td>";
			$output .= "<td>".$result['slug']."</td>";
			$output .= "<td><div class='btn-group'>";
			$output .= "<a class='btn btn-primary' href='edit-department.php?id=".$result['id']."'>Edit</a>";
			$department_user_total = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM users WHERE department_id='$id'"));
			$department_file_total = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM files WHERE department_id='$id' || current_department_id='$id'"));
			$department_tracking_total = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM tracking WHERE sender_department_id='$id' || receiver_department_id='$id'"));
			if($department_user_total == 0 || $department_file_total == 0 || $department_tracking_total == 0) {
				$output .= "<button class='btn btn-danger delete_department_btn' data-id='".$id."'>Delete</button>";
			}
			$output .= "</div></td>";
			$output .= "</tr>";
			$i++;
		}
	} else {
		$output .= "<tr><td colspan='4' class='text-center'>No Record Found</td></tr>";
	}

	echo $output;
}

if(isset($_POST['action']) && $_POST['action'] == 'delete_department') {
	$id = mysqli_real_escape_string($conn, $_POST['id']);
	if($id != '' && $id != 0) {
		$query = mysqli_query($conn, "DELETE FROM departments WHERE id='$id'");
		if($query) {
			echo 2;
		} else {
			echo 1;
		}
	} else {
		echo 0;
	}
}

?>