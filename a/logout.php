<?php  
error_reporting(1);
require_once "../config.php";
if($_SESSION['username']){
	session_destroy();
	echo "<script> document.location='../'; </script>";
}else{
}
?>