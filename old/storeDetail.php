<?php
include("./mysql.php");
include("./message.php");
include("./tools.php");
session_start();
if ($_SESSION['merchantId']=="") {
	message('登录超时，请重新登录','./login.html','error');
}else{
	if ($_GET['storeId']!="") {
		$_SESSION['storeId']=$_GET['storeId'];
	}
	$SqlHelper = new SqlHelper();
	$sql = 'select * from ims_xingfit_merchant_store where store_id = "'.$_SESSION['storeId'].'"';
	$store = $SqlHelper->pdo_fetch($sql);
	$sql2 = 'select * from ims_xingfit_merchant_place where store_id = "'.$_SESSION['storeId'].'"';
	$places = $SqlHelper->pdo_fetchall($sql2);
	$sql3 = 'select * from ims_xingfit_merchant_store_time where store_id = "'.$_SESSION['storeId'].'"';
	$times = $SqlHelper->pdo_fetch($sql3);
	if ($times['store_id']=="") {
		$sql4 = 'insert into ims_xingfit_merchant_store_time values ("'.$_SESSION['storeId'].'","08","00","22","00","08","00","22","00","08","00","22","00","08","00","22","00","08","00","22","00","08","00","22","00","08","00","22","00")';
		$SqlHelper->pdo_query($sql4);
		$sql5 = 'select * from ims_xingfit_merchant_store_time where store_id = "'.$_SESSION['storeId'].'"';
		$times = $SqlHelper->pdo_fetch($sql3);
	}
	if ($_POST['place_name']!="") {
		$sql6 = 'select * from ims_xingfit_merchant_place where place_name = "'.$_POST['place_name'].'"';
		$res6 = $SqlHelper->pdo_fetch($sql6);
		if ($res6['place_name'] ==  "") {
			if ($_POST['forsj'] == "") {
				$_POST['forsj'] = 0;
			}else{
				$_POST['forsj'] = 1;
			}
			if ($_POST['fortk'] == "") {
				$_POST['fortk'] = 0;
			}else{
				$_POST['fortk'] = 1;
			}
			$sql7 = 'insert into ims_xingfit_merchant_place(place_name,place_for_sijiao,place_for_tuanke,store_id,place_limit) values("'.$_POST['place_name'].'","'.$_POST['forsj'].'","'.$_POST['fortk'].'","'.$_SESSION['storeId'].'","'.$_POST['place_limit'].'")';
			$SqlHelper->pdo_query($sql7);
			$url = './storeDetail.php?storeId='.$_SESSION['storeId'];
			echo "<script>";
			echo "window.location.href='$url'";
			echo "</script>";
		}else{
			message("已存在该场地",'','error');
		}
	}else if($_POST['place_xg']!=""){
		if ($_POST['xgsj'] == "") {
			$_POST['xgsj'] = 0;
		}else{
			$_POST['xgsj'] = 1;
		}
		if ($_POST['xgtk'] == "") {
			$_POST['xgtk'] = 0;
		}else{
			$_POST['xgtk'] = 1;
		}
		$sql12 = 'update ims_xingfit_merchant_placeset place_name="'.$_POST['place_xg_name'].'",place_for_sijiao="'.$_POST['xgsj'].'",place_for_tuanke="'.$_POST['xgtk'].'",place_limit="'.$_POST['place_xg_limit'].'" where place_id = "'.$_POST['place_id'].'"';
		$SqlHelper->pdo_query($sql12);
		$url = './storeDetail.php?storeId='.$_SESSION['storeId'];
		echo "<script>";
		echo "window.location.href='$url'";
		echo "</script>";
	}else if($_POST['place_sc']!=""){
		$sql8 = 'delete from ims_xingfit_merchant_place where place_id = "'.$_POST['place_id'].'"';
		$SqlHelper->pdo_query($sql8);
		$url = './storeDetail.php?storeId='.$_SESSION['storeId'];
		echo "<script>";
		echo "window.location.href='$url'";
		echo "</script>";
	}else if($_POST['store_xg_name']!=""){
		$path = './image/upload/'.$_SESSION['merchantId']."/".$_POST['store_xg_name'];
		$path1 = './image/upload';
		$path2 = './image/upload/'.$_SESSION['merchantId'];
		$path3 = './image/upload/'.$_SESSION['merchantId']."/".$store['store_name'];
		$path4 = './image/upload/'.$_SESSION['merchantId']."/".$_POST['store_xg_name'].'/logo';
		print_r($less);
		if ($_FILES['store_xg_logo']['name']=="") {
			if ($store['store_name']!=$_POST['store_xg_name']) {
				rename($path3, $path);
				$string = substr($store['store_logo'], $store['store_logo'].length-13,13);
				$_POST['logo_address'] = $path4."/".$string;
			}else{
				$_POST['logo_address'] = $store['store_logo'];
			}
		}else{
			$tools = new tools();

			$less = $tools->hex10to64(time()-strtotime('2013-3-21'),5);
			$filename = explode(".", $_FILES['store_xg_logo']['name']);
			if (!file_exists($path1)) {
				mkdir($path1);
			}
			if (!file_exists($path2)) {
				mkdir($path2);
			}
			if (!file_exists($path3)) {
				mkdir($path3);
			}else{
				if ($store['store_name']!=$_POST['store_xg_name']) {
					rename($path3, $path);
				}					
			}
			if (!file_exists($path4)) {
				mkdir($path4);
			}
			if ($filename[1]=="jpg") {
				$_POST['logo_address'] = $path4."/".$store['store_id'].$less.rand(100,999).".jpg";
				move_uploaded_file($_FILES["store_xg_logo"]["tmp_name"], $_POST['logo_address']);
			}else{
				$_POST['logo_address'] = $path4."/".$store['store_id'].$less.rand(100,999).".png";
				move_uploaded_file($_FILES['store_xg_logo']['tmp_name'], $_POST['logo_address']);
			}
		}
		$sql9 = 'update ims_xingfit_merchant_store set store_name="'.$_POST['store_xg_name'].'",store_phone="'.$_POST['store_xg_phone'].'",store_location="'.$_POST['store_xg_location'].'",store_mail="'.$_POST['store_xg_mail'].'",store_logo="'.$_POST['logo_address'].'",wechat="'.$_POST['store_xg_wechat'].'" where store_id="'.$_SESSION['storeId'].'"';
		$SqlHelper->pdo_query($sql9);
		$sql10 = 'delete from ims_xingfit_merchant_store_timewhere store_id = "'.$_SESSION['storeId'].'"';
		$SqlHelper->pdo_query($sql10);
		$sql11 = 'insert into ims_xingfit_merchant_store_timevalues ("'.$_SESSION['storeId'].'","'.$_POST['mon_xg_sh'].'","'.$_POST['mon_xg_sm'].'","'.$_POST['mon_xg_eh'].'","'.$_POST['mon_xg_em'].'","'.$_POST['tue_xg_sh'].'","'.$_POST['tue_xg_sm'].'","'.$_POST['tue_xg_eh'].'","'.$_POST['tue_xg_em'].'","'.$_POST['wed_xg_sh'].'","'.$_POST['wed_xg_sm'].'","'.$_POST['wed_xg_eh'].'","'.$_POST['wed_xg_em'].'","'.$_POST['tur_xg_sh'].'","'.$_POST['tur_xg_sm'].'","'.$_POST['tur_xg_eh'].'","'.$_POST['tur_xg_em'].'","'.$_POST['fri_xg_sh'].'","'.$_POST['fri_xg_sm'].'","'.$_POST['fri_xg_eh'].'","'.$_POST['fri_xg_em'].'","'.$_POST['sat_xg_sh'].'","'.$_POST['sat_xg_sm'].'","'.$_POST['sat_xg_eh'].'","'.$_POST['sat_xg_em'].'","'.$_POST['sun_xg_sh'].'","'.$_POST['sun_xg_sm'].'","'.$_POST['sun_xg_eh'].'","'.$_POST['sun_xg_em'].'")';
		$SqlHelper->pdo_query($sql11,$params11);
		$url = './storeDetail.php?storeId='.$_SESSION['storeId'];
		echo "<script>";
		echo "window.location.href='$url'";
		echo "</script>";
	}
	else{
		include 'storeDetail.html';
	}
	$SqlHelper->close_connect();
}
?>