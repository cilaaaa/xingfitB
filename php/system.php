<?php
include("./mysql.php");
include("./message.php");
include("./tools.php");
session_start();
if ($_SESSION['merchantId']=="") {
	message('登录超时，请重新登录',"./login.html",'error');
}else{
	if ($_REQUEST['category']=="") {
		$_REQUEST['category'] = 0;
	}
	$_POST['category']=$_REQUEST['category'];
	$SqlHelper = new SqlHelper();
	if ($_REQUEST['category']==0) {
		$sql = 'select * from ims_xingfit_merchant_info where merchantId = "'.$_SESSION['merchantId'].'"';
		$res = $SqlHelper->pdo_fetch($sql);
		$sql2 = 'select * from ims_xingfit_merchant_store where merchantId = "'.$_SESSION['merchantId'].'"';
		$stores = $SqlHelper->pdo_fetchall($sql2);
		if ($_POST['store_name']!="") {
			$sql3 = 'select * from ims_xingfit_merchant_store where store_name = "'.$_POST['store_name'].'"';
			$res3 = $SqlHelper->pdo_fetch($sql3);
			if ($res3['store_name']=="") {
				if ($_FILES['store_logo']['name']=="") {
					$_POST['logo_address'] = $res['merchantLogo'];
				}else{
					$tools = new tools();
					$less = $tools->hex10to64(time()-strtotime('2013-3-21'),5);
					$filename = explode(".", $_FILES['store_logo']['name']);
					$path1 = './image/upload';
					$path2 = './image/upload/'.$_SESSION['merchantId'];
					$path3 = './image/upload/'.$_SESSION['merchantId']."/".$_POST['store_name'];
					$path4 = './image/upload/'.$_SESSION['merchantId']."/".$_POST['store_name'].'/logo';
					if (!file_exists($path1)) {
						mkdir($path1);
					}
					if (!file_exists($path2)) {
						mkdir($path2);
					}
					if (!file_exists($path3)) {
						mkdir($path3);
					}
					if (!file_exists($path4)) {
						mkdir($path4);
					}
					if ($filename[1]=="jpg") {
						$_POST['logo_address'] = $path4."/".$store['store_id'].$less.rand(100,999).".jpg";
						move_uploaded_file($_FILES["store_logo"]["tmp_name"], $_POST['logo_address']);
					}else{
						$_POST['logo_address'] = $path4."/".$store['store_id'].$less.rand(100,999).".png";
						move_uploaded_file($_FILES['store_logo']['tmp_name'], $_POST['logo_address']);
					}
				}
				$sql4 = 'insert into ims_xingfit_merchant_store (store_name,store_phone,store_location,store_mail,merchantId,store_logo) values("'.$_POST['store_name'].'","'.$_POST['store_phone'].'","'.$_POST['store_location'].'","'.$_POST['store_mail'].'","'.$_SESSION['merchantId'].'","'.$_POST['logo_address'].'")';
				$SqlHelper->pdo_query($sql4);
				$url = './system.php';
				echo "<script>";
				echo "window.location.href='$url'";
				echo "</script>";
			}else{
				message("已存在该门店",'','error');
			}
		}else{
			include "./system.html";
		}
		$SqlHelper->close_connect();
	}else if ($_REQUEST['category']==2) {
		include "./system.html";
		$SqlHelper->close_connect();
	}else if ($_REQUEST['category']==1) {
		$sql = 'select * from ims_xingfit_merchant_sms_cate';
		$sms = $SqlHelper->pdo_fetchall($sql);
		$sql2 = 'select * from ims_xingfit_merchant_sms where merchantId = "'.$_SESSION['merchantId'].'"'; 
		$smstmp = $SqlHelper->pdo_fetch($sql2);
		$smsinfo = array_values($smstmp);
		if ($smstmp['merchantId']=="") {
			$sql3 = 'insert into ims_xingfit_merchant_sms values ("'.$_SESSION['merchantId'].'","240","-1","240","-1","-1","-1","-1","-1","-1","-1","-1","60","120","-1","-1")';
			$SqlHelper->pdo_query($sql3);
			$sql4 = 'select * from ims_xingfit_merchant_sms where merchantId = "'.$_SESSION['merchantId'].'"';
			$smsinfo = array_values($SqlHelper->pdo_fetch($sql4));
		}
		if ($_POST['sms_sz']!=""){
			for($i=0;$i<=14;$i++){
				if ($_POST['status'.$i]==""){
					$_POST['status'.$i] = 0;
				}
			}
			$sql5 = 'update ims_xingfit_merchant_sms set vip_openclass = "'.$_POST['status0'].'",insufficient_number="'.$_POST['status1'].'",insufficient_number_time="'.$_POST['status2'].'",new_tuanke_sj="'.$_POST['status3'].'",quxiao_tuanke_sj="'.$_POST['status4'].'",new_tuanke_hy="'.$_POST['status5'].'",quxiao_tuanke_hy="'.$_POST['status6'].'",new_sijiao_yuyue_sj="'.$_POST['status7'].'",quxiao_sijiao_sj="'.$_POST['status8'].'",new_sijiao_yuyue_hy="'.$_POST['status9'].'",quxiao_sijiao_hy="'.$_POST['status10'].'",cannt_yuyue="'.$_POST['status11'].'",quxiao_yuyue="'.$_POST['status12'].'",send_code="'.$_POST['status13'].'",chongzhi_hyk="'.$_POST['status14'].'" where merchantId ="'.$_SESSION['merchantId'].'"';
			$SqlHelper->pdo_query($sql5);
			$url = './system.php?category=2';
			echo "<script>";
			echo "window.location.href='$url'";
			echo "</script>";
		}else{
			include "./system.html";
		}
		$SqlHelper->close_connect();
	}
	
}
?>