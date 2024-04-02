<?php

include("functions.php");
include("conection.php");
$tp = 0;
$dayb = 40;

?>
<!DOCTYPE html>
<html lang="en">
<head>

	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Referred By <?php echo $p_name; ?></title>

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
			<h2>Referred By &raquo <?php echo $p_name;  ?></h2>
			<hr />

			<?php
			if(isset($_POST['btnenviar'])){
				$dayb = $_POST['day_back'];
				}	
			?>
			<div class="container">
			<div class="row">
			<div class="col-md-12">
            <hr>
			<form method="post" class="form-inline">
			<div class="form-group mr-3">
			<input type="number" id="day_back" name="day_back" value = '40' placeholder="Days Back" step="1" class="form-control">
			</div>
			<!-- <div class="form-group mr-3">
			<input type="date" name="date_back" value=" " class="input-group date form-control"  placeholder="yyyy-mm-dd"
			</div>
			-->
			<input name="csrf" type="hidden" value="<?php echo escape($_SESSION['csrf']);?>">
			<button type="submit" name="btnenviar" class="btn btn-primary">View Result</button>
			</form>
			</div>
			</div>
			</div>
			<br />
			<div class="table-responsive">
			<table class="table table-striped table-hover">
			<tr>
            <th>Name</th>
            <th>SS Number</th>
			<th>Fee</th>
			<th>Last Payment</th>
			<th>Last Period Payment</th>
			<th>Month Type</th>
			<th>Status</th>
			</tr>
				<?php
				//$sql = mysqli_query($con, "SELECT p.id, p.pid, concat(p.lname, ', ', p.fname, ' ', p.mname) pname, p.ss ss, f2.fee fee, f2.fee_date fee_date,CURDATE() current, f2.billing_period billing_period, DATEDIFF(CURDATE(), f2.billing_period) days_gone, f2.deleted
				//				FROM (SELECT MAX(id) AS id, pid	FROM fees GROUP BY pid) f
				//			    INNER JOIN patient_data p ON f.pid = p.pid
				//				INNER JOIN fees f2 ON f2.id = f.id 
				//			    WHERE p.referred_by=$p_id AND f2.deleted='0'
				//			    ");
				
				$sql = mysqli_query($con, "SELECT p.id, p.pid, concat(p.lname, ', ', p.fname, ' ', p.mname) pname, p.ss ss, (f2.fee - discount) fee, f2.fee_date fee_date,CURDATE() current, f2.billing_period billing_period, f2.month_type month_type, DATEDIFF(CURDATE(), f2.fee_date) days_gone_pay, DATEDIFF(CURDATE(), f2.billing_period) days_gone_period, f2.deleted
								FROM (SELECT MAX(id) AS id, pid	FROM fees GROUP BY pid) f
							    INNER JOIN patient_data p ON f.pid = p.pid
								INNER JOIN fees f2 ON f2.id = f.id 
							    WHERE p.referred_by=$p_id AND f2.deleted='0'
							    ");
				if(mysqli_num_rows($sql) == 0){
					echo '<tr><td colspan="8">Not found.</td></tr>';
				}else{
					$no = 1;
					while($row = mysqli_fetch_assoc($sql)){
						echo '
				<tr>
				<td>'.$row['pname'].'</a></td>
                <td>'.$row['ss'].'</td>
                <td>'.$row['fee'].'</td>
				<td>';
				$fecha = $row['fee_date'];
				$newDate = date("Y-m-d", strtotime($fecha));
				echo $newDate;
				echo '
				</td>
				<td>';
				setlocale(LC_TIME, "english");
				$fecha = $row['billing_period'];
				$newDate = date("Y-m-d", strtotime($fecha));				
				$mesDesc = date("Y - F", strtotime($newDate));
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
				<td>';
				if ($row['days_gone_pay'] <= $dayb and $row['month_type'] == 1){
				    $tp = $tp + 1;
								echo '<span class="label label-success">Time Payment</span>';
					}
                        	    else {
				    echo '<span class="label label-danger">Late Payment</span>';
					}
				   echo '
				   </td>
				   </tr>
					
				    ';
				$no++;
				   }
				}
				?>
				<tr>
					<td></td>
					<td></td>
					<td></td>
					<td><b>Total Time Payment <?php echo ' ('. $dayb .') Days Back';  ?></b></td>
					<td></td>
					<td><b> <?php echo $tp;  ?> </b></td>
				</tr>
			</table>
			<a href="list.php" class="btn btn-sm btn-danger"><span class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span> Back</a>
			</div>
		</div>
	</div><center>
	<p>&copy; Origen <?php echo date("Y");?></p
		</center>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
</body>
</html>
