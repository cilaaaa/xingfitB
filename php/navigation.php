<?php

include("./mysql.php");
include("./message.php");
session_start();
if ($_REQUEST['account']!="") {
	$account = $_REQUEST['account'];
	$password = $_REQUEST['password'];
	$SqlHelper = new SqlHelper();
	$sql = 'select * from ims_xingfit_merchant_user where account = "'.$account.'" and password = "'.$password.'"';
	$res = $SqlHelper->pdo_fetch($sql);
	if ($res['merchantId']!="") {
		$_SESSION['merchantId']=$res['merchantId'];
		$sql2 = 'select * from ims_xingfit_merchant_info where merchantId = "'.$_SESSION['merchantId'].'"';
		$res2 = $SqlHelper->pdo_fetch($sql2);
		$_SESSION['logo'] = $res2['merchantLogo'];
		$_SESSION['username'] = $res2['merchantName'];
		include "../html/navigation.html";
	}else{
		message('用户名或密码错误','./login.html','error');
	}
	$SqlHelper->close_connect();
}else{
	include "../html/navigation.html";
}

?>