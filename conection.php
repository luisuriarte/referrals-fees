<?php
/*Datos de conexion a la base de datos*/
$db_host = "localhost";
$db_user = "openemr";
$db_pass = "ADmin@EMR#$99";
$db_name = "openemr";

$con = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

if(mysqli_connect_errno()){
	echo 'Can not connect to DB : '.mysqli_connect_error();
}
?>