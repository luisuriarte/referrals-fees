<?php

include("functions.php");
include("conection.php");
$sqlmember = mysqli_query($con, "SELECT l.title FROM list_options l INNER JOIN patient_data p ON p.pricelevel = l.option_id WHERE p.pid = $p_id");
$member = mysqli_fetch_assoc($sqlmember);
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Payment Detail</title>

	<!-- Bootstrap -->
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<link href="css/style_nav.css" rel="stylesheet">
	<style>
		.content {
			margin-top: 80px;
		}
	</style>
	
	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
</head>
<body>
	<nav class="navbar navbar-fixed-top bg-dark text-white" style="background-color: #e3f2fd;">
		<?php include('nav.php');?>
	</nav>
	<div class="container">
		<div class="content">
			<h2 class="mt-3">Fee attribute <?php echo $p_name; ?></h2>
			<h2><small><p><a href="#" class="text-danger"><?php echo $member['title']; ?></a></p></small></h2>
			<hr />
			
			<?php
			// escaping, additionally removing everything that could be (html/javascript-) code
			$nik = mysqli_real_escape_string($con,(strip_tags($_GET["nik"],ENT_QUOTES)));
			
			$sql = mysqli_query($con, "SELECT f.id id, f.pid pid, f.fee_date fee_date, f.fee fee, f.discount discount, f.method method, f.billing_period billing_period, f.month_type month_type, CONCAT(u.lname, ', ', u.fname, ' ', u.mname) user_auth, (f.fee - f.discount) total, CURDATE() current, DATEDIFF(CURDATE(), f.fee_date) days_gone, f.updated_at updated_at FROM fees f INNER JOIN users u ON f.user_auth=u.id WHERE f.id='$nik'");
						
			if(mysqli_num_rows($sql) == 0){
				header("Location: index.php");
			}else{
				$row = mysqli_fetch_assoc($sql);
			}
			
			if(isset($_GET['aksi']) == 'delete'){
				$delete = mysqli_query($con, "UPDATE fees SET deleted='1', deleted_at='$timestamp' WHERE id='$nik'");
				if($delete){
					echo '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> Data removed successfully.</div>';
				}else{
					echo '<div class="alert alert-info alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Error, data could not be deleted.</div>';
				}
			}
			?>
			
			<table class="table table-striped table-condensed">
				<tr>
					<th>Payment Date</th>
					<td><?php echo $row['fee_date']; ?></td>
				</tr>
				<tr>
					<th>Fee</th>
					<td><?php echo $row['fee']; ?></td>
				</tr>
				<tr>
					<th>Discount</th>
					<td><?php echo $row['discount']; ?></td>
				</tr>
				<tr>
					<th>Total</th>
					<td><?php echo $row['total']; ?></td>
				</tr>
				<tr>
					<th>Method</th>
					<td><?php echo $row['method']; ?></td>
				</tr>
				<tr>
					<th>Days Since Payment - Today: <?php echo $today; ?></th>
					<td><?php echo $row['days_gone']; ?></td>
				</tr>
				<tr>
					<th>Period</th>
					<?php
					setlocale(LC_TIME, "english");
					$fecha = $row['billing_period'];
					$newDate = date("d-m-Y", strtotime($fecha));				
					$mesDesc = date("Y - M", strtotime($newDate));
					?>
					<td><?php echo $mesDesc; ?></td>
				</tr>
				<tr>
					<th>Month Type</th>
					<td>
					<?php 
					if($row['month_type'] == 0){
						echo '<span class = "label label-default">unrecorded</span>';
						}
					if($row['month_type'] == 1){
						echo '<span class = "label label-warning">_Completion_</span>';
						}
                    else if ($row['month_type'] == 2 ){
						echo '<span class = "label label-info">Proportionate</span>';
						}
					?>
					</td>
				</tr>
				<tr>
					<th>Last Modification</th>
					<td><?php echo $row['updated_at']; ?></td>
				</tr>
				<tr>
					<th>By</th>
					<td><?php echo $row['user_auth']; ?></td>
				</tr>
			</table>
			
			<a href="list.php" class="btn btn-sm btn-info"><span class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span> Back</a>
			<a href="edit.php?nik=<?php echo $row['id']; ?>" class="btn btn-sm btn-success"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span> Edit</a>
			<a href="profile.php?aksi=delete&nik=<?php echo $nik; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Esta seguro de borrar los datos <?php echo $row['fee_date']; ?>')"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span> Delete</a>
		</div>
	</div>

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
</body>
</html>