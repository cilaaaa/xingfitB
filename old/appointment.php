<?php
include("./mysql.php");
include("./message.php");
session_start();
if ($_SESSION['merchantId']=="") {
	message('登录超时，请重新登录',"./login.html",'error');
}else{
	if ($_REQUEST['category']=="") {
		$_POST['category'] = 0;
	}
	include './appointment.html';
}
?>