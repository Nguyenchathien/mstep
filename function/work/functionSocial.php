<?php


	# @author Akabane 
	# @date 2011/01/25 00:51:41
	function getSpecialMeter($current_meter , $appear_meter){
		
		$tmp = $current_meter / $appear_meter * 10;
		
		$seisu = floor($tmp);
		
		if($tmp == $seisu){
			return $seisu;
		}else{
			return $seisu ."_5" ;
		}
		
	}

	# 
	# @author Akabane 
	# @date 2010/12/05 18:17:17
	function img_ava($type , $id){
		echo "<img src=\"". IMG_DOMAIN . getAvaPath($type , $id)  .  "\">";
	}


	# 
	# @author Akabane 
	# @date 2010/12/05 18:12:52
	function getAvaPath($type , $id){
		return "common_img/ava/{$type}/ava_{$id}.gif";
	}



	# 画像
	# @author Akabane 
	# @date 2010/12/05 17:29:04 
	function img($str , $return = false){
		if(!preg_match("#http#" , $str))
			$str = IMG_DOMAIN . $str;
		if(!$return){
			echo $str;
		}else{
			return $str;
		}
	}


	# 
	# @author Akabane 
	# @date 2010/12/31 07:41:49
	function batch_lnk($url , $return = false , $guid = true){
		if(!empty($_GET["opensocial_app_id"])){

			# ページ内リンク
			$name = null;
			if(preg_match("/#(.*)/",$url,$m)){
				if(!empty($m)){
					$name = $m[0];
					$url = str_replace($name,"",$url);
				}else{
					$name = null;
				}
			}//if
			$domain = BATCH_DOMAIN;
			$url = APP_URL . "?url=" . urlencode($domain . $url);
			if($guid){
				$url.="&guid=on";
			}
			$url .= $name;
		}
		else{
			$url = BATCH_DOMAIN . $url;
		}
		
		if($return){
			return $url;
		}else{
			echo $url;
		}
		
	}

	#
	# @author Akabane 
	# @date 
	function lnk($url , $return = false , $guid = true){
		
		if(!empty($_GET["opensocial_app_id"])){
			#$rand = $GLOBALS["SV_RAND"];
			#$no = $GLOBALS["ADDR_ARY"][$rand];
			#$domain = sprintf(SPRINT_DOMAIN,$no);
			# http://xxxx.jp?url=hgoehoge
		
			# ページ内リンク
			$name = null;
			if(preg_match("#/mb/#",$url,$m)){
				#$url = str_replace("/mb/" , "" , $url);
				
				# ディレクトリ構成が変わったので修正 Ikehata
				$url = str_replace("/dev/moeiku/mb/" , "" , $url);
			}
			
			
			if(preg_match("/#(.*)/",$url,$m)){
				if(!empty($m)){
					$name = $m[0];
					$url = str_replace($name,"",$url);
				}else{
					$name = null;
				}
			}//if
			$domain = SOCIAL_DOMAIN;
			#$url = "?url=" . urlencode($domain . $url);
			$url = APP_URL . "?url=" . urlencode($domain . $url);
			if($guid){
				$url.="&guid=on";
			}
			$url .= $name;
		}
		
		if($return){
			return $url;
		}else{
			echo $url;
		}
	}

	function getDomain(){
		$rand = $GLOBALS["SV_RAND"];
		$no = $GLOBALS["ADDR_ARY"][$rand];
		$domain = sprintf(SPRINT_DOMAIN,$no);
		sv_rand_plus();
		return $domain;
	}



	function swf($str){
		if(!preg_match("#http#" , $str))
			$str = "?url=" . urlencode(IMG_DOMAIN. $str);
		echo $str;
	}

	#
	# @author Akabane 
	# @date 
	function redirect($url){
		
		if(SOCIAL_FLG){
			$rand = $GLOBALS["SV_RAND"];
			$no = $GLOBALS["ADDR_ARY"][$rand];
			$domain = sprintf(SPRINT_DOMAIN,$no);
			$url =  SOCIAL_DOMAIN . "?url=" . urlencode($domain . "index.php?page={$url}");
			sv_rand_plus();
		}
		else{
			$url = MB_DOMAIN ."index.php?page=". $url;
		}
		
		header("location:{$url}");
		exit;
	}



	#
	# @author Akabane 
	# @date 
	function link_replace($str){
		preg_match_all("#%%(.*?)%%#is" , $str , $m,PREG_SET_ORDER);
		#err($m);
		foreach($m as $k=>$v){
			$str = str_replace($v[0] , lnk($v[1],true) ,$str);
		}//foreach
		return $str;
	}

	function getSwfRedirectUrl($page="swf/done"){
		$rand = $GLOBALS["SV_RAND"];
		$no   = $GLOBALS["ADDR_ARY"][$rand];
		$domain = sprintf(SPRINT_DOMAIN,$no);
		$after =  SOCIAL_DOMAIN . "?url=" . urlencode($domain . "index.php?page={$page}");
		return $after;
		
	}

	function sv_rand_plus(){
		$GLOBALS["SV_RAND"]++;
		if($GLOBALS["SV_RAND"] >= $GLOBALS["ADDR_ARY_COUNT"]){
			$GLOBALS["SV_RAND"] = 0;
		}
	}


	
?>