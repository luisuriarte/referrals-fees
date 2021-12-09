<?php

include("functions.php");
include("conection.php");
//echo $timestamp;
$sqlprepay = mysqli_query($con, "SELECT p.prepay_amount FROM patient_data p WHERE p.pid = $p_id");
$prepay = mysqli_fetch_assoc($sqlprepay);
$sqlmember = mysqli_query($con, "SELECT l.title FROM list_options l INNER JOIN patient_data p ON p.pricelevel = l.option_id WHERE p.pid = $p_id");
$member = mysqli_fetch_assoc($sqlmember);
?>
<!DOCTYPE html>
<html lang="en">
<head>
	
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Add Payment</title>

	<!-- Bootstrap 	-->
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<link href="css/bootstrap-datepicker.css" rel="stylesheet">
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
			<h2 class="mt-3"><?php echo $p_name; ?> &raquo; Add Payment</h2>
			<h2><small><p><a href="#" class="text-danger"><?php echo $member['title']; ?></a></p></small></h2>
			<hr />

			<?php
			if(isset($_POST['add'])){
				$fee_date = mysqli_real_escape_string($con,(strip_tags($_POST["fee_date"],ENT_QUOTES)));//Escanpando caracteres 
				$fee	  = mysqli_real_escape_string($con,(strip_tags($_POST["fee"],ENT_QUOTES)));//Escanpando caracteres 
				$discount = mysqli_real_escape_string($con,(strip_tags($_POST["discount"],ENT_QUOTES)));//Escanpando caracteres 
				$method	  = mysqli_real_escape_string($con,(strip_tags($_POST["method"],ENT_QUOTES)));//Escanpando caracteres
				$billing_period = mysqli_real_escape_string($con,(strip_tags($_POST["billing_period"],ENT_QUOTES)));//Escanpando caracteres 
				$month_type = mysqli_real_escape_string($con,(strip_tags($_POST["month_type"],ENT_QUOTES)));//Escanpando caracteres
				$total	  = ($fee - $discount);
				$p_id     = $p_id;
				
				$billing_period = date('Y-m-d', strtotime($billing_period));
								
				$insert = mysqli_query($con, "INSERT INTO fees(pid, fee_date, fee, discount, method, billing_period, month_type, user_auth, inserted_at) 

				VALUES('$p_id', '$timestamp', '$fee', '$discount', '$method', '$billing_period', '$month_type', '$user','$timestamp')") or die(mysqli_error());
				if($insert){
					echo '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Fee was successfully saved.</div>';
				}else{
					echo '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Error. Could not save !</div>';
				}
					 
			}
			?>

			<form class="form-horizontal" action="" method="post" name="MyForm">
				<div class="form-group">
					<label class="col-sm-3 control-label">Payment Date</label>
					<div class="col-sm-2">
						<input type="date" name="fee_date" value="<?php echo $today; ?>" max="<?php echo date("Y-m-d"); ?>" class="input-group date form-control" required>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">Fee ($)</label>
					<div class="col-sm-2">
						<input type="number" name="fee" id="fee" class="form-control" value="<?php echo number_format($prepay['prepay_amount'], 2, '.', ''); ?>" placeholder="Amount" step="0.10" required>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">Discount ($)</label>
					<div class="col-sm-2">
						<input type="number" name="discount" id="discount" class="form-control" value="0.00" placeholder="Discount" step="0.10" >
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">Total ($)</label>
					<div class="col-sm-2">
						<input type="number" name="total" class="form-control" onblur="resta()" onclick="resta()" onchange="resta()" onfocus="resta()" readonly >
				    </div>
				</div>

				<div class="form-group">
				<label class="col-sm-3 control-label">Period</label>
					<div class="col-sm-2">
					<input type="month" name="billing_period" step="1" min="2021-01-01" max="2032-12-31" value="<?php echo date('Y-m'); ?>" class="input-group date form-control" >	
				</div>
				</div>
				
				<div class="form-group">
					<label class="col-sm-3 control-label">Type</label>
					<div class="col-sm-2">
						<select name="month_type" class="form-control">
							<option value="1">Completion</option>
							<option value="2">Proportionate</option>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">Method</label>
					<div class="col-sm-2">
					<select name="method" class="form-control"> 
    					<?php $sql =$con->prepare("SELECT title, seq FROM list_options WHERE list_id = 'payment_method' ORDER BY seq ASC");
    						$sql->execute(); 
    						$result = $sql->get_result();
    						while ($row = $result->fetch_assoc())
    						{ ?>
						<option value="<?php echo $row['title']; ?>"> <?php echo $row['title']; ?> </option> 
						<?php } ?>
					</select>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">&nbsp;</label>
					<div class="col-sm-6">
						<input type="submit" name="add" class="btn btn-sm btn-primary" value="Save Data">
						<a href="./list.php" class="btn btn-sm btn-danger">Back</a>
					</div>
				</div>
			</form>
		</div>
	</div>

<script type="text/javascript">
		function resta() {
			var n1 = parseFloat(document.MyForm.fee.value);
			var n2 = parseFloat(document.MyForm.discount.value);
			document.MyForm.total.value=(n1-n2);
		}
</script>

<script type="text/javascript">

</script>
		
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/bootstrap-datepicker.js"></script>
	
</body>
</html>
