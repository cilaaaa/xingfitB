<?php
class tools
{
	const KeyCode = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ_$';
	public static function getNonceStr($length = 32) 
	{
		$chars = "abcdefghijklmnopqrstuvwxyz0123456789";  
		$str ="";
		for ( $i = 0; $i < $length; $i++ )  {  
			$str .= substr($chars, mt_rand(0, strlen($chars)-1), 1);  
		} 
		return $str;
	}

	public function Getaccess_token()
	{
		$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=wxb7651791cdd505cd&secret=38b0344b515bf38bd133f1b872626c54";
    //初始化curl
		$ch = curl_init();
    //设置超时
		curl_setopt($ch, CURLOP_TIMEOUT, 30);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    //运行curl，结果以jason形式返回
		$res = curl_exec($ch);
		curl_close($ch);
    //取出openid
		$data = json_decode($res,true);
		$access_token2 = $data['access_token'];
		return $access_token2;
	}

	public function Getjs_api()
	{
		$url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token=".$_SESSION['access_token']."&type=jsapi";
    //初始化curl
		$ch = curl_init();
    //设置超时
		curl_setopt($ch, CURLOP_TIMEOUT, 30);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    //运行curl，结果以jason形式返回
		$res = curl_exec($ch);
		curl_close($ch);
    //取出openid
		$data2 = json_decode($res,true);
		$js_api = $data2['ticket'];
		return $js_api;
	}

	/**
     * 将10进制的数字字符串转为64进制的数字字符串
     * @param $m string 10进制的数字字符串
     * @param $len integer 返回字符串长度，如果长度不够用0填充，0为不填充
     * @return string
     * @author 野马
     */
    function hex10to64($m, $len = 0) {
        $KeyCode = self::KeyCode;
        $hex2 = decbin($m);
        $hex2 = $this->str_rsplit($hex2, 6);
        $hex64 = array();
        foreach($hex2 as $one) {
            $t = bindec($one);
            $hex64[] = $KeyCode[$t];
        }
        $return = preg_replace('/^0*/', '', implode('', $hex64));
        if($len) {
            $clen = strlen($return);
            if($clen >= $len) {
                return $return;
            }
            else {
                return str_pad($return, $len, '0', STR_PAD_LEFT);
            }
        }
        return $return;
    }

    /**
     * 功能和PHP原生函数str_split接近，只是从尾部开始计数切割
     * @param $str string 需要切割的字符串
     * @param $len integer 每段字符串的长度
     * @return array
     * @author 野马
     */
    protected function str_rsplit($str, $len = 1) {
        if($str == null || $str == false || $str == '') return false;
        $strlen = strlen($str);
        if($strlen <= $len) {
            return array($str);
        }
        $headlen = $strlen % $len;
        if($headlen == 0) {
            return str_split($str, $len);
        }
        $return = array(substr($str, 0, $headlen));
        return array_merge($return, str_split(substr($str, $headlen), $len));
    }
}
?>