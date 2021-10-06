<?php

include("functions.php");
include("conection.php");

?>
<!DOCTYPE html>
<html lang="en">
<head>

	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Payment Edit <?php echo $p_name; ?></title>
	<link rel="stylesheet" type="text/css" media="screen" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/themes/base/jquery-ui.css">
	<!-- Bootstrap -->
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<link href="css/bootstrap-datepicker.css" rel="stylesheet">
	<link href="css/style_nav.css" rel="stylesheet">
	<style>
		.content {
			margin-top: 80px;
		}
		.ui-datepicker-calendar {
    display: none;
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
			<h2> <?php echo $p_name;?> &raquo; Edit Payment</h2>
			<hr />
			<?php
			// escaping, additionally removing everything that could be (html/javascript-) code
			$nik = mysqli_real_escape_string($con,(strip_tags($_GET["nik"],ENT_QUOTES)));
			$sql = mysqli_query($con, "SELECT * FROM fees WHERE id='$nik'");
			if(mysqli_num_rows($sql) == 0){
				header("Location: list.php");
			}else{
				$row = mysqli_fetch_assoc($sql);
			}
			if(isset($_POST['save'])){
				$fee_date = mysqli_real_escape_string($con,(strip_tags($_POST["fee_date"],ENT_QUOTES)));//Escanpando caracteres 
			    $fee	  = mysqli_real_escape_string($con,(strip_tags($_POST["fee"],ENT_QUOTES)));//Escanpando caracteres 
			    $discount = mysqli_real_escape_string($con,(strip_tags($_POST["discount"],ENT_QUOTES)));//Escanpando caracteres 
			    $method	  = mysqli_real_escape_string($con,(strip_tags($_POST["method"],ENT_QUOTES)));//Escanpando caracteres 
				$billing_period = mysqli_real_escape_string($con,(strip_tags($_POST["billing_period"],ENT_QUOTES)));//Escanpando caracteres 
			    $month_type	  = mysqli_real_escape_string($con,(strip_tags($_POST["month_type"],ENT_QUOTES)));//Escanpando caracteres 
			    $total	  = ($fee - $discount);
			    $p_id     = $p_id;
				$billing_period = date('Y-m-d', strtotime($billing_period));
				
				$update = mysqli_query($con, "UPDATE fees SET fee_date='$fee_date', fee='$fee', discount='$discount', method='$method', billing_period= '$billing_period', month_type='$month_type', user_auth='$user', updated_at='$timestamp' WHERE id='$nik'") or die(mysqli_error());
				if($update){
					header("Location: edit.php?nik=".$nik."&pesan=sukses");
				}else{
					echo '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Error, data could not be saved.</div>';
				}
			}
			
			if(isset($_GET['pesan']) == 'sukses'){
				echo '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>The data has been saved successfully.</div>';
			}
			?>
			<form class="form-horizontal" action="" method="post" name='MyForm'>
				<div class="form-group">
					<label class="col-sm-3 control-label">Payment Date</label>
					<div class="col-sm-2">
						<input type="date" name="fee_date" value="<?php echo date('Y-m-d', strtotime($row ['fee_date'])); ?>" class="input-group date form-control"  placeholder="yyyy-mm-dd" required>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">Fee ($)</label>
					<div class="col-sm-2">
						<input type="number" name="fee" value="<?php echo $row ['fee']; ?>" class="form-control" placeholder="Amount" step="0.10"  required>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">Discount ($)</label>
					<div class="col-sm-2">
						<input type="number" name="discount" value="<?php echo $row ['discount']; ?>" class="form-control" placeholder="Discount" step="0.10">
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">Total to Pay ($)</label>
					<div class="col-sm-2">
						<input type="number" name="total" class="form-control" onblur="resta()" onclick="resta()" onchange="resta()" onfocus="resta()" readonly >
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">Method</label>
					<div class="col-sm-2">
						<select name="method" class="form-control"> 
    						<option value"none" </option>
    						<?php $sql =$con->prepare("SELECT title FROM list_options WHERE list_id = 'payment_method'");
								$sql->execute(); 
									$result = $sql->get_result();
								while ($rowval = $result->fetch_assoc())
								{ ?>
							<option value="<?php echo $rowval['title'];?>" <?php echo ($rowval['title']==$row['method'])?"selected":""; ?>><?php echo $rowval['title']; ?></option>
							<?php } ?>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">Billing Period</label>
					<?php 
					setlocale(LC_TIME, "english");
					$convert_date = strtotime($row['billing_period']);
					$month = date('m',$convert_date);
					$year = date('Y',$convert_date);
					$ym = $year . '-' . $month;
					?>
					<div class="col-sm-2">
						<input type="month" name="billing_period" step="1" min="2021-01-01" max="2032-12-31" value="<?php echo $ym; ?>" class="input-group date form-control" >
					</div>
				</div>
				
				<div class="form-group">
					<label class="col-sm-3 control-label">Type</label>
					<div class="col-sm-2">
						<select name="month_type" class="form-control">
							<option value="0" <?php if ($row ['month_type']==0){echo "selected";} ?>>----------</option>
							<option value="1" <?php if ($row ['month_type']==1){echo "selected";} ?>>Completion</option>
							<option value="2" <?php if ($row ['month_type']==2){echo "selected";} ?>>Proportionate</option>
						</select>
					</div>
				</div>
						
				<div class="form-group">
					<label class="col-sm-3 control-label">&nbsp;</label>
					<div class="col-sm-6">
						<input type="submit" name="save" class="btn btn-sm btn-primary" value="Save Data">
						<a href="list.php" class="btn btn-sm btn-danger">Back</a>
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
$(function() {
     $('.date-picker').datepicker(
                    {
                        dateFormat: "mm/yy",
                        changeMonth: true,
                        changeYear: true,
                        showButtonPanel: true,
                        onClose: function(dateText, inst) {


                            function isDonePressed(){
                                return ($('#ui-datepicker-div').html().indexOf('ui-datepicker-close ui-state-default ui-priority-primary ui-corner-all ui-state-hover') > -1);
                            }

                            if (isDonePressed()){
                                var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
                                var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
                                $(this).datepicker('setDate', new Date(year, month, 1)).trigger('change');
                                
                                 $('.date-picker').focusout()//Added to remove focus from datepicker input box on selecting date
                            }
                        },
                        beforeShow : function(input, inst) {

                            inst.dpDiv.addClass('month_year_datepicker')

                            if ((datestr = $(this).val()).length > 0) {
                                year = datestr.substring(datestr.length-4, datestr.length);
                                month = datestr.substring(0, 2);
                                $(this).datepicker('option', 'defaultDate', new Date(year, month-1, 1));
                                $(this).datepicker('setDate', new Date(year, month-1, 1));
                                $(".ui-datepicker-calendar").hide();
                            }
                        }
                    })
});
</script>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.1/jquery.js"></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/jquery-ui.min.js"></script>
<script src="http://pure.github.io/pure/libs/pure.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/bootstrap-datepicker.js"></script>
	
</body>
</html>