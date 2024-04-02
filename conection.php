<?php
/*Datos de conexion a la base de datos Mysql*/
$db_host = "localhost";
$db_user = "*******";
$db_pass = "******";
$db_name = "******";

$con = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

if(mysqli_connect_errno()){
	echo 'Can not connect to DB : '.mysqli_connect_error();
}
?>