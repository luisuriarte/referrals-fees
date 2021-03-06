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
	<title>Pre Paid Data</title>

	<!-- Bootstrap -->
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<link href="css/style_nav.css" rel="stylesheet">

	<style>
		.content {
			margin-top: 80px;
		}
	</style>

</head>
<body>
	<nav class="navbar navbar-fixed-top bg-dark text-white" style="background-color: #e3f2fd;">
		<?php include('nav.php');?>
	</nav>
	<div class="container">
		<div class="content">
			<h2>Pre Paid &raquo <?php echo $p_name;  ?></h2>
			<h2><small><p><a href="#" class="text-danger"><?php echo $member['title']; ?></a></p></small></h2>
			<hr />

			<?php
			
			if(ISSET($_GET['filter'])){
				$date1 = date("Y-m-d", strtotime($_GET['date1']));
				$date2 = date("Y-m-d", strtotime($_GET['date2']));
			}
			
			if(isset($_GET['aksi']) == 'delete'){
				// escaping, additionally removing everything that could be (html/javascript-) code
				$nik = mysqli_real_escape_string($con,(strip_tags($_GET["nik"],ENT_QUOTES)));
				$sql = mysqli_query($con, "SELECT * FROM fees WHERE id='$nik' AND deleted=0");
				if(mysqli_num_rows($sql) == 0){
					echo '<div class="alert alert-info alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> No data found.</div>';
				}else{
					$delete = mysqli_query($con, "UPDATE fees SET deleted='1', deleted_at='$timestamp' WHERE id='$nik'");
					if($delete){
						echo '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> Data removed successfully.</div>';
					}else{
						echo '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> Error, data could not be deleted.</div>';
					}
				}
			}
			
			?>
			
			<div class="container">
			  <div class="row">
			    <div class="col-md-12">
					<a href="add.php"  class="btn btn-primary mt-4">Add Pre Pay</a>
					<tr>
					<th></th>
					<a href="referrals.php"  class="btn btn-success">View Referrals</a>
					</tr>
				</div>
			  </div>
			</div>
			
			<!--
			<hr style="color: #0056b2;" />
			
			<div class="col-md-12"></div>
						<form class="form-inline" method="GET" action="">
						<label>Date From:</label>
							<input type="date" class="form-control" placeholder="Start" name="date1"/>
						<label>Date To</label>
							<input type="date" class="form-control" placeholder="End" name="date2"/>
						<?php $filter = (isset($_GET['filter']) ? strtolower($_GET['filter']) : NULL);  ?>
				<button class="btn btn-primary" name="filter"><span class="glyphicon glyphicon-search"></span></button> <a href="list.php" type="button" class="btn btn-success"><span class = "glyphicon glyphicon-refresh"><span></a>
			-->
			<tr>
			<br />
			<hr style="color: #0056b2;" />
			<div class="table-responsive">
			<table class="table table-striped table-hover">
			<tr>
			<th>#</th>
            <th>Fee Date</th>
            <th>Fee</th>
			<th>Discount</th>
			<th>Total</th>
			<th>Method</th>
			<th>Billing Period</th>
		    <th>Month Type</th>
			<th>Operator</th>
			<th>Act</th>
			</tr>
			<?php
			if($filter){
					$sql = mysqli_query($con, "SELECT f.id id, f.pid pid, DATE(f.fee_date) fee_date, f.fee fee, f.discount discount, f.method method, f.billing_period, f.month_type, f.user_auth user_auth, (f.fee - f.discount) total, CONCAT(u.lname, ', ', u.fname, ' ', u.mname) user_auth FROM fees f INNER JOIN users u ON user_auth=u.id WHERE f.pid = $p_id AND deleted='0' AND (f.fee_date >= '".$date1."' AND f.fee_date <= '".$date2."') ORDER BY f.fee_date ASC");
					echo $date1;
					echo $date2;
				}else{
					$sql = mysqli_query($con, "SELECT f.id id, f.pid pid, DATE(f.fee_date) fee_date, f.fee fee, f.discount discount, f.method method, f.billing_period, f.month_type, f.user_auth user_auth, (f.fee - f.discount) total, CONCAT(u.lname, ', ', u.fname, ' ', u.mname) user_auth FROM fees f INNER JOIN users u ON user_auth=u.id WHERE f.pid = $p_id AND deleted='0' ORDER BY f.fee_date DESC");
					//echo $date2;
					//echo $filter;
				}
			if(mysqli_num_rows($sql) == 0){
			    echo '<tr><td colspan="8">Not found.</td></tr>';
			}else{
			    $no = 1;
			while($row = mysqli_fetch_assoc($sql)){
				
			echo '
			    <tr>
				<td>'.$no.'</td>
				<td><a href="./profile.php?nik='.$row['id'].'"><span class="glyphicon glyphicon-ok-sign" aria-hidden="true"></span> '.$row['fee_date'].'</a></td>
                <td>'.$row['fee'].'</td>
                <td>'.$row['discount'].'</td>
				<td>'.$row['total'].'</td>
				<td>'.$row['method'].'</td>
				<td>';
				setlocale(LC_TIME, "english");
				$fecha = $row['billing_period'];
				$newDate = date("d-m-Y", strtotime($fecha));				
				$mesDesc = strftime("%Y - %B", strtotime($newDate));
				echo $mesDesc;
				echo '
				</td>
				<td>';
					if($row['month_type'] == 0){
						echo '<span class = "label label-default">unrecorded</span>';
						}
					if($row['month_type'] == 1){
						echo '<span class = "label label-warning"> Completion </span>';
						}
                    else if ($row['month_type'] == 2 ){
						echo '<span class = "label label-info">Proportional</span>';
						}
                            
				echo '
				</td>
				<td>'.$row['user_auth'].'</td>
				</td>
				<td>
				    <a href="./edit.php?nik='.$row['id'].'" title="Edit" class="btn btn-primary btn-sm"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></a>
				    <a href="./list.php?aksi=delete&nik='.$row['id'].'" title="Delete" onclick="return confirm(\'Be sure to delete the payment of the '.$row['fee_date'].'?\')" class="btn btn-danger btn-sm"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a>
				</td>
			    </tr>
			    ';
			    $no++;
			    }
			}
			?>
			</table>
			</div>
		</div>
	</div><center>
	<p>&copy; Vluznet <?php echo date("Y");?></p
		</center>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
</body>
</html>
