<?php
	/**
	 * XHTML出力用
	 */
	function xhtml_output(){
		header("Content-type: application/xhtml+xml");
	}

	// 携帯用文字列カット関数
	// 絵文字中間コードを含む文字列の長さを計り、カットします。
	//
	// @author  bane 2008/08/13 15:23:11
	// @param   $str      元の文字列
	// @param   $size     カットする文字長さ
	// @param   $rtrim    カットした語尾に付加する文字列（カットされた場合のみ付加されます）
	// @param   $encode   文字エンコーディング
	// @param   $tmp_code 一時コード※絶対に$strに含まれない必要があります
	function ktai_str_cut($str , $size , $rtrim = "…" , $encode="UTF-8" , $tmp_code="`"){
		$tmp_str = $str;

		//絵文字中間コード覧を取得、配列に格納
		preg_match_all( '/(\[%.*?%\]|<img.*?>)/is' , $tmp_str , $match);
		if($match[0]){
			//中間コードを「(1文字扱い)一時コード」に変換
			$tmp_str = str_replace($match[0] , $tmp_code, $str );
		}
		//指定文字長さでカット
		$tmp_str_cut =  mb_substr($tmp_str , 0 , $size  , $encode);

		$tmp_replace_str = $tmp_str_cut;
		$i = 0;

		//「一時コード」を順番に中間コードに戻す（「一時コード：絵文字中間コード」は「１：多」の為、str_replaceでは不可）
		while(true){
			if(!preg_match("/" . preg_quote($tmp_code) . "/is" , $tmp_replace_str)){
				break;
			}
			$tmp_replace_str = preg_replace('/'. preg_quote($tmp_code) .'/is' , $match[0][$i] , $tmp_replace_str , 1);
			$i++;
		}
		//もし文字がカットされていたら、語尾に$rtrimを付加する
		$tmp_replace_str .= (strlen($tmp_str_cut) != strlen($tmp_str)) ? $rtrim : "" ;

		return $tmp_replace_str;
	}

	#
	# @author Akabane 
	# @date 
	function if_mobile($str){
		$c = getCareerByAddr();
		if($c != "pc"){
			echo $str;
		}
	}
	/**
	 * IPアドレスからキャリアを取得する
	 *
	 */
	function getCareerByAddr($ip=null){
		if(!$ip) $ip = $_SERVER["REMOTE_ADDR"];
		$hostname = gethostbyaddr($ip);	// IPの場合はget Host By Addrする
		
		//Docomo docomo.ne.jp
		if(preg_match('/\.docomo\.ne\.jp$/', $hostname)){
			return 'i';
		}
		//Vodafone Softbank jp-t.ne.jp
		elseif(preg_match('/\.jp.*?\.ne\.jp$/', $hostname)){
			return 's';
		}
		//au  ezweb.ne.jp | ido.ne.jp
		elseif(preg_match('/\.(ido|ezweb)\.ne\.jp$/', $hostname)){
			return 'e';
		}
		//WillCom DDI Pocket
		elseif(preg_match('/pdxcgw\.pdx\.ne\.jp$/', $hostname)){
			return 'w';
		}
		//L-mode
		elseif(preg_match('/\.pipopa\.ne\.jp$/', $hostname)){
			return 'l';
		}
		else{
			/*	if(__DEVTYPE_ACCEPT_MOBILE_CRAWLER__ && $this->isMobileCrawler()){
				return 'i';	// DoCoMo (携帯クローラはimodeに成りすますことが多いため)
			}	*/
			return 'pc';	// others
		}
	}

	/**
	 * ユーザーエージェントからキャリアを調べる
	 * 
	 * @param	string	$agent	ユーザーエージェントの文字列
	 * @return	string	キャリアの文字列( i/s/e/h 携帯でなければ"pc" )
	 */
	function getCareerByAgent($agent=null){
		if(!$agent) $agent = @$_SERVER["HTTP_USER_AGENT"];
		$docomoRegex    = "^DoCoMo/\d\.\d[ /]";
		$jphoneRegex    = "^(J-PHONE/\d\.\d)|(Vodafone/\d\.\d)|(MOT-)|(SoftBank/\d\.\d)";
		$ezwebRegex     = "(?:KDDI-[A-Z]+\d+ )?UP\.Browser\/";
		$airhphoneRegex = "^Mozilla/3\.0\(DDIPOCKET'";
		# 2011/06/13 Ogura スマートフォン対応
		$androidRegex = "Android";
		$iphoneRegex = "iPhone";
		$mobileRegex = "(?:($docomoRegex)|($jphoneRegex)|($ezwebRegex)|($airhphoneRegex)|($androidRegex)|($iphoneRegex))";
		$c_id = "pc";
		if( preg_match("!$mobileRegex!", $agent, $matches)) {
		    $c_id = @$matches[1] ? 'i' :
		           (@$matches[2] ? 's' :
		           (@$matches[7] ? 'e' : 'w'));
			   
		   # 2011/06/13 Ogura スマートフォン対応
		   if (@$matches[9]) {
		   	$c_id = "an";
		   } else if (@$matches[10]) {
		   	$c_id = "ip";
		   }
		}
		return $c_id;
	}

	/**
	 * ユーザーエージェントとキャリア識別子から機種判別
	 *
	 * @param string $agent ユーザエージェント
	 * @param string $c_id キャリアの文字列(i/s/e/h )
	 * @param stirng $dir キャリアの種類(i/ix/ex/e/s/h/pc)
	 */
	function getBrwTypeByAgent($agent=null,$c_id){
		if(!$agent) $agent = $_SERVER["HTTP_USER_AGENT"];
		
		//DoCoMoで
		if($c_id == "i"){
			//FOMAだったら
			if(strstr($agent,'DoCoMo/2.0')){
				$dir = "ix";
			//FOMA以外だったら
			}else{
				$dir = "i";
			}
		//auで
		}else if($c_id == "e"){
			//WINだったら
			if(strstr($agent,'KDDI-')){
				$dir = "ex";
			//WIN以外だったら
			}else{
				$dir = "e";
			}
		//SoftBankだったら
		}else if($c_id == "s"){
			$dir = "s";
		//willcom
		}else if($c_id == "w"){
			$dir = "w";
		//携帯じゃなさげだったら
		}else{
			$dir = "pc";
		}
		return $dir;
	}

	/**
	 * メールアドレスからキャリアを調べる
	 * 
	 * @access	private
	 * @param	String	$address	メールアドレス
	 * @return	String	キャリアの文字列( i/e/s 携帯でなければ"pc" )
	 */
	function getCareerByEmail($address)
	{
		list($domainName) = explode("@" , $address);
		
		//docomo
		if(preg_match("/docomo/", $domainName) != 0){
			$c_id = 'i';
		}
		//ezweb
		if(preg_match("/ezweb/", $domainName) != 0){
			$c_id = 'e';
		}
		//softbank (disney含む)
		if(preg_match("/vodafone|softbank|jp-*\.ne\.jp/", $domainName) != 0 ){
			$c_id = 's';
		}
		if($c_id == ""){
			$c_id = 'pc';
		}
		return $c_id;
	}

	/**
	 * 個体識別番号を取得する
	 * 
 	 * ■DoCoMo
	 *（例）個体識別情報
 	 *    DoCoMo/2.0 N2001(c10;serXXXXXXXXXXXXXXX; iccxxxxxxxxxxxxxxxxxxxx)
 	 *   （ser：固定、***********：製造番号）
	 *
	 * ■Softbank
 	 *（例）端末シリアル番号（製造番号）
 	 *  固体識別番号 : J-PHONE/4.0/J-SH51/SNxxxxxxxx	 
	 *
	 *  ■au
 	 *（例）サブスクライバID
 	 *    xxxxxxxxxx_xx.ezweb.ne.jp
	 */
	function getMobileKey($debug=0){
		if($debug AND is_bj()){
			return "BJ";
		}//if
		
		$UA = $_SERVER['HTTP_USER_AGENT'];
		$HostName = @gethostbyaddr($_SERVER['REMOTE_ADDR']);
		
		
		//■DoCoMo
		if ( preg_match("/.docomo.ne.jp/", $HostName) ) {
			
			//iモードID
			if (!empty($_SERVER['HTTP_X_DCMGUID'])) {
				$MobileInfo = $_SERVER['HTTP_X_DCMGUID'];
			}
			//個体識別番号
			else{
				preg_match("/ser([a-zA-Z0-9]+)/",$UA, $dprg);
				if ( strlen($dprg[1]) === 11 ) {
					$MobileInfo = $dprg[1];
				} elseif ( strlen($dprg[1]) === 15 ) {
					$MobileInfo = $dprg[1];
					preg_match("/icc([a-zA-Z0-9]+)/",$UA, $dpeg);
					if ( strlen($dpeg[1]) === 20 ) {
						$MobileInfo = $dpeg[1];
					} else {
						$MobileInfo = false;
					}
				} else {
					$MobileInfo = null;
				}
			}
		}
		//■SoftBank
		elseif(	preg_match('/^J-PHONE.*/', $UA) or 
				preg_match('/^Vodafone.*/', $UA) or 
				preg_match('/^SoftBank.*/', $UA)
				){
					return $_SERVER["HTTP_X_JPHONE_UID"];
					
					/*
					if(preg_match("/SN([a-zA-Z0-9]+)/",$UA,$vprg)){
					        $MobileInfo = $vprg[1];
					}else{
						$MobileInfo = null;
			        }
					*/
	    }
		//■au
		elseif ( preg_match("/.ezweb.ne.jp/", $HostName) ) {
	        $MobileInfo = $_SERVER['HTTP_X_UP_SUBNO'];

		//■others
	    }else{
			return null;
		}
	    return $MobileInfo;
	}


	if(!function_exists("agent_to_device")){
	function agent_to_device($agent=null){
		if(empty($agent)){
			$agent = $_SERVER['HTTP_USER_AGENT'];
		}
		$career = getCareerByAddr();
		
		switch ($career){
		case "i":
			# ドコモ
			if(strpos($agent, "DoCoMo/1.0") >= 0 && strpos($agent, "/", 11) >= 0){
				$device = substr($agent, 11, (strpos($agent, "/", 11) - 11));
			}elseif(strpos($agent, "DoCoMo/2.0") >= 0 && strpos($agent, "(", 11) >= 0){
				$device = substr($agent, 11, (strpos($agent, "(", 11) - 11));
			}else{
				$device = substr($agent, 11);
			}
			break;
		case "e":
			# au（エージェントは、2タイプとも取得できる）
			$device = substr($agent, (strpos($agent, "-") + 1), (strpos($agent, " ") - strpos($agent, "-") - 1));
			break;
		case "s":
			# ソフトバンク（x-jphone-msnameで機種名だけ取得できる）
			$device = $_SERVER["HTTP_X_JPHONE_MSNAME"];
			if(empty($device)){
				$device = "softbak";
			}
			break;
		default:
			$device = "";
			break;
		}// switch
		return $device;
	}//function


	# スマートフォン判定
	# @author Ogura 
	# @date 2011/06/14 10:06:59
	function isSmartphone($car = null){
		if ($car == null) {
			$car = getCareerByAgent();
		}
		
		if ($car == "an" || $car == "ip") {
			return true;
		}
		return false;
	}

	# 
	# @author Ikehata 
	# @date 2011/07/26 16:55:03
	function is_vga($career){
		$is_vga = false;
		if($career == "s"){ 
			$display_size = $_SERVER['HTTP_X_JPHONE_DISPLAY'];
			$display_size = explode('*', $display_size);
	
			if($display_size[0]>400){
				$is_vga = true;
			}else{
				$is_vga = false;
			}
		}
		return $is_vga;
	}
}
?>