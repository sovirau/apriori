<?php
error_reporting(0);
include('../config.php');
session_start();
if(!isset($_SESSION['username'])){
    header("Location:../");
}

switch ($_GET['pages']) {

	case 'home':
		include "dashboard.php";
        break;

    case 'dtbrg':
		include "data_barang.php";
        break;

    case 'viewdata':
		include "data_barang2.php";
        break;

    case 'viewitem':
		include "v_item.php";
        break;

    case 'olahdata':
		include "olah_data.php";
        break;

    case 'logout':
		include "logout.php";
        break;

	default:
        include "dashboard.php";
    break;
}
?>