<?php
error_reporting(0);

switch ($_GET['pages']) {

	case 'def':
		include "login.php";
        break;

	default:
		include "login.php";
}
?>