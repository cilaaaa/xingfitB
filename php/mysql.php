<?php
      //这是 工具类,对数据库的 操作。
header("Content-Type: text/html; charset=utf-8");
      class SqlHelper{
      	
      	public $conn;
      	public $dbname="xingfit";
      	public $username="admin";
      	public $password="Welcome1";
      	public $host="localhost";
      	
      	public function __construct(){
      		
      		$this->conn=mysql_connect($this->host,$this->username,$this->password);
      		
      		if(!$this->conn)
      		{
      			
      			die("连接失败！".mysql_errno());
      		}
      
      		mysql_select_db($this->dbname);
      		mysql_query('set names utf8');
      	}
      	
      	
      	//执行dql语句
      	public function pdo_fetch($sql){
      		$res=mysql_query($sql,$this->conn) or die(mysql_error());
      		$res = mysql_fetch_assoc($res);
      	    return $res;
      	}
      	
      	//执行dql语句，返回的是数组
      	public function pdo_fetchall($sql){
      		$arr=array();
      		$res=mysql_query($sql,$this->conn) or die(mysql_error());
      		//吧res的数据集导入到数组里面去	
      		while($row=mysql_fetch_assoc($res)){
      			$arr[]=$row;
      		}
      		mysql_free_result($res);
      		return $arr;
      	}
      	
      	//分页公共查询语句
      	//$sql1=""select * from ... where ... limit 0,6";
      	//$sql2="select count(id) from 表";
      	public function execute_dql_fenye($sql1,$sql2,$fenyePage){
      		 //这里查询到了要显示的分页数据
      		  $res=mysql_query($sql1) or die(mysql_error());
      		  //$ress=>array();
      		  $arr=array();
      		  while ($row=mysql_fetch_assoc($res)){
      		  	$arr[]=$row;
      		  }
      		  //把$arr付给$fenyePage
      		  $fenyePage->res_array=$arr;
      		  mysql_free_result($res);
      		  $res2=mysql_query($sql2,$this->conn) or die(mysql_error());
      		  
      		  if($row=mysql_fetch_row($res2)){
      		  	$fenyePage->pageCount=ceil($row[0]/$fenyePage->pageSize);
      		  	$fenyePage->rowCount=$row[0];
      		  }
      		  
               //navigate分页信息开始：
      		  $page_to=10;
      		  if($fenyePage->pageNow  >  $fenyePage->pageCount - 10 && $fenyePage->pageNow  <=  $fenyePage->pageCount)
      		  	$page_to=$fenyePage->pageCount - $fenyePage->pageNow;
      		  else if($fenyePage->pageNow  >  $fenyePage->pageCount)
      		  	$fenyePage->pageNow=$fenyePage->pageCount;
      		  if($fenyePage->pageNow > 1){
      		  	$prepage = $fenyePage->pageNow - 1;
      		  	$fenyePage->navigate="<a href='?pageNow=$prepage'>上一页</a>&nbsp";
      		  }
      		  if($fenyePage->pageNow>10){
      		  	//向上翻十页
      		  	$_big_a=(floor(($fenyePage->pageNow-1)/10)-1)*10+1;
      		$fenyePage->navigate.="<a href='?pageNow=$_big_a'><<</a>";
      		  }
      		  
      		  	$start=floor(($fenyePage->pageNow-1)/10)*10+1;
      		  	$index = $start;
      		  	for(;$start<$index+$page_to;$start++){
      		  		$fenyePage->navigate.="<a href='?pageNow=$start'>[$start]</a>";
      		  	}
      		  	if($fenyePage->pageNow < $fenyePage->pageCount){
      		  		//向下翻十页
      		  		$_big_b=(floor(($fenyePage->pageNow-1)/10)+1)*10+1;
      		  		$fenyePage->navigate.="<a href='?pageNow=$_big_b'>>></a>";
      		  	}
      		  
      		  if($fenyePage->pageNow < $fenyePage->pageCount){
      		  			$prepage = $fenyePage->pageNow + 1;
      		  			$fenyePage->navigate.="<a href='?pageNow=".$prepage."'>下一页</a>";
      		  }
      		  //navigate分页信息开结束
      		  			
      		  
      		  
      	}
      	
      	//执行dml语句
      	public function pdo_query($sql){
      		
      		$dml_sql = mysql_query($sql,$this->conn);
      	    if(!$dml_sql){
      	    	return 0;
      	    }else{
      	    	if(mysql_affected_rows($this->conn)>0){
      	    		return 1;//表示执行成功！
      	    	}else{
      	    		return 2;//表示没有行受到影响
      	    	}
      	    }
      	
      	}
      	
      	
      	
      	public function close_connect(){
      		if(!empty($this->conn)){
      			mysql_close($this->conn);
      		}
      	}
      	
   }