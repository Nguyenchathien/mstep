<?php

	# 
	# @author Akabane 
	# @date 2011/07/22 14:44:26
	function time_limit($date , $if_long_echo = false , $pre = "" , $foot = ""){
		
		$gap = strtotime($date) - strtotime("NOW");
		$ago = timetostr($gap);
		
		# 一週間以上期間があるなら
		if(!empty($ago["day"]) AND $ago["day"] >= 7 ){
			if($if_long_echo){
				return $pre . dd("n月d日H時まで" , $date) . $foot;
			}else{
				return null;
			}
		}
		# 7日未満なら
		if(!empty($ago["day"]) AND $ago["day"] < 7 AND $ago["day"] > 2){
			
			return "<span style='color:red;'>{$pre}あと{$ago["day"]}日で終了{$foot}</span>";
		}
		# 3日未満
		elseif(!empty($ago["day"])){
			$hour = $ago["day"] * 24 + $ago["hour"];
			return "<span style='color:red;'>{$pre}あと{$hour}時間{$ago["minute"]}分で終了{$foot}</span>";
		}

		# 1日未満
		elseif(!empty($ago["hour"]) and $ago["hour"] > 0){
			return "<span style='color:red;'>{$pre}あと{$ago["hour"]}時間{$ago["minute"]}分で終了{$foot}</span>";
		}
		# 1時間未満
		elseif(!empty($ago["minute"]) and $ago["minute"] > 0){
			return "<span style='color:red;'>{$pre}あと{$ago["minute"]}分{$ago["second"]}秒で終了{$foot}</span>";
		}
		# 1分未満
		elseif(isset($ago["second"])){
			$ago["second"]++;
			return "<span style='color:red;'>{$pre}あと{$ago["second"]}秒で終了{$foot}</span>";
		}
	}

	function getUserAvaPath($user_id , $type="normal" , $output="web"){
		$dir = substr($user_id , -3,3);
		return "mount/user_ava/{$dir}/{$user_id}_{$type}.gif";
		
	}

	function getUserCoodiPath($user_id , $type="normal" , $output="web"){
		$dir = substr($user_id , -3,3);
		return "mount/coodi/{$dir}/{$user_id}_{$type}.gif";
		
	}

	# 
	# @author Akabane 
	# @date 2010/12/21 10:53:05
	function out($str){
		echo $str;
		echo "<br>\n";
	}

	/**
	 * 画像ファイル名か判別
	 * @author Akabane 
	 * @date 2009/06/27 19:45:33 
	 */
	function is_img($fname){
		$img = array("gif" , "png" , "jps" , "jpeg" , "bmp");
		$ext = get_ext($fname);
		return (in_array($ext , $img));
	}

	//カウンター
	//2009/06/25 13:39:58 
	function total_count($int , $key){
		static $buf  = array();
		
		if(is_null($int)){
			return (@$buf[$key]) ? $buf[$key] : 0;
		}else{
		
			if(empty($buf[$key])){
				$buf[$key] = $int;
			}else{
				$buf[$key] += $int;
			}
		}
		return $buf[$key];
	}


	/**
	 * 最大値に対するセットした値の割合(30%など)をグラフにして返します
	 *
	 * $target_num : 対象となる数字 (int)
	 * $max        : 比較対象となる最大値 (int)
	 * $rate_num   : 何段階評価か (int)
	 * $color      : メータに使用する色 (str)
	 *
	 * @author Akabane 
	 * @date 2009/06/17 13:32:25 
	 */
	function toGraph( $target_num , $max , $rate_num=10 , $color="blue" , $space=false ){
		@$rate = $target_num / $max * 100;
		$per = 100 / $rate_num;
	
		$return = "";
		$c = 0;
		for($i=0;$i<$rate_num;$i++){
			$c += $per;
			if($rate < $c){
				//四捨五入
				$up   = ($rate - $c ) * -1;
				$down = $rate - $c  + $per;
				if($up <= $down AND !isset($end_flg)){
					$return .= "<span style='color:{$color};'>■</span>";
					$end_flg = true;
					$i++;
				}
				if(!$space){
					break;
				}else{
					$return .= "<span style='color:{$space};'>■</span>";
					continue;
				}
			}
			$return .= "<span style='color:{$color};'>■</span>";
		}//for
		return $return;
	}
	/**
	 * http://x.jp/hoge.html → http://x.jp/
	 * @author Akabane 
	 * @date 2009/06/17 11:32:56 
	 */
	function toDomain($url){
		$list = explode("/" , $url);
		for($i=0;$i<count($list);$i++){
			if($i==3) break;
			$res[] = $list[$i];
		}//for
		$return = implode("/" , $res);
		return $return;
	}

	/**
	 * カンマ区切りの配列データを、データ配列と結合して表示
	 * @author Akabane 
	 * @date 
	 */
	function printAryStr($str_ary , $data_ary){
		$ary = explode("," , $str_ary);
		$return = "";
		
		foreach($ary as $k=>$v){
			$return .= $data_ary[$v];
			if(end($ary)!=$v)
				$return .="/";
		}//foreach
		return $return;
	}


	function if_echo($str){
		if(is_bj()){
			$data = debug_backtrace();
			echo basename($data[0]["file"]) .  "{$data[0]["line"]}行目 <font color=red>";
			echo $str . "</font><br>\n";
		}
	}

	/**
	 * 最大値/最小値を取得する
	 * 
	 * $return されるまでに挿入された key の最大値/最小値を保有し、 return された段階で最大値/最小値を返します
	 * 
	 * @author Akabane 
	 * @date 2009/04/27 21:35:06 
	 */
	function getLimit($key , $data , $type="MAX" , $return = false){
		static $buffer;
		
		if($return){
			return (isset($buffer[$key])) ? $buffer[$key] : 0;
		}
		
		if(!isset($buffer[$key])){
			$buffer[$key] = $data;
		}
		else{
			if(up($type)=="MAX"){
				if( $data  > $buffer[$key] ){
					$buffer[$key] = $data;
				}
			}
			elseif(up($type)=="MIN"){
				if( $data  < $buffer[$key] ){
					$buffer[$key] = $data;
				}
			}
			return true;
		}
	}

	/**
	 * DB保存
	 * @author Akabane 
	 * @date 2009/04/25 12:12:51 
	 */
	function vx(){
		$db = ClassRegistry::init('Bj');
		$data = debug_backtrace();

		switch(1){
			//コントローラ
			case (eregi("controller" , $data[0]["file"])):
				$insert["type"] = "CONTROLLER";
				break;
			//モデル
			case (eregi("model" , $data[0]["file"])):
				$insert["type"] = "MODEL";
				break;
			//ビュー
			case (eregi("view" , $data[0]["file"])):
				$insert["type"] = "VIEW";
				break;
			//デフォルト
			default:
				$insert["type"] = "OTHER";
				break;
		}

		$insert["file"] = r( APP , "" , $data[0]["file"]);
		$insert["line"] = $data[0]["line"];
		$insert["data"] = print_r($data[0]["args"][0] , 1);
		
		array_shift($data);
		$v = array_shift($data);
		@$insert["class"] = $v["class"];
		
		$insert["function"] = "";
		if($insert["type"] !="VIEW"){
			@$insert["function"] = $v["function"];
		}
		$db->insertVx($insert);
	}

	/**
	 * number_formatのラッパー
	 * @author Akabane 
	 * @date 2009/03/17 01:10:23 
	 */
	function no($str , $per=0){
		if($str < 0){
			return "<span class='s-red'>" . number_format($str) . "</span>";
		}else{
			return number_format($str,$per);
		}
	}
	
	/**
	 * 絵文字でのNoを返す
	 *
	 * $nameが空の場合
	 * [1]→[2]→[3]
	 *
	 * @author Akabane 
	 * @date 
	 */
	function emoji_no($name=""){
		static $roop_name = "";
		static $put_number = 0;
		
		if($name){
			if($roop_name!=$name){
				$put_number = 0;
			}
			$roop_name = $name;
		}
		$array = array(
			"[%180%]",
			"[%181%]",
			"[%182%]",
			"[%183%]",
			"[%184%]",
			"[%185%]",
			"[%186%]",
			"[%187%]",
			"[%188%]",
		);
		
		$return = $array[$put_number];
		$put_number++;
		return $return;
	}


	//改行区切りで配列展開
	//2009/01/19 17:57:47 
	function nlexplode($data){
		$return = ( explode("\n" , r("\r" , "" , $data))); //改行区切りで配列に展開
		return $return;
	}

	/**
	 * 同一文字コードの時はエンコーディングしない
	 * @author Akabane 
	 * @date 
	 */
	function enc($str , $to_encode = "UTF-8", $encode = null){
		mb_language("Japanese");
		if(!$encode) $encode = mb_detect_encoding($str);
		if($encode != $to_encode){
			$str =  mb_convert_encoding($str , $to_encode , $encode);
		}
		return $str;
	}
	
	
	/**
	 * 弊社のIPならtrue
	 */
	function is_bj($ip=null){
		if(DEVELOP_MODE != "hogea"){
			return false;
		}
		if(!$ip) $ip = @$_SERVER['REMOTE_ADDR'];
		
		if($ip == "210.248.145.250" or $ip == "203.196.21.120" or preg_match("#192.168|127.0.0#" , $ip) ){
			return true;
		}
		return false;
	}

	/**
	 * 弊社のIPならtrue
	 */
	function is_preview($ip=null){
		
		if(!$ip)  $ip = @$_SERVER['REMOTE_ADDR'];

		if($ip == "203.196.21.120" AND isset($_GET["preview"]) ){
			return true;
		}
		return false;
	}

	/**
	 * var_dump拡張変数出力用デバッカー
	 *
	 * $date 出力するデータ
	 * $type 0:出力してexit 1:出力してexitしない　2: tmp/logs/v.txtに書き込み
	 * $fname $typeが2の時、ファイル名を指定して出力
	 * 3 IPに関係無く強制終了
	 */
	if(defined("LOGS")){
		define("OUTPUT_FILE_PATH" , LOGS );
	}else{
		define("OUTPUT_FILE_PATH" , dirname(__FILE__) . "/" );
	}
	function v($_data , $type=0 , $fname="v.log" , $str_code = "Shift_jis"){
		if(DEVELOP_MODE == "web"){
			return;
		}
		
		$data = debug_backtrace();
		switch($type){
			//■出力 exitする
			case 0:
			//■出力 exitしない
			case 1:
				#if(is_bj()){
				if (!headers_sent()) header("Content-Type: text/html; charset={$str_code}");
				ob_start();
				echo '<html><head><meta http-equiv="content-type" content="text/html; charset='.$str_code.'"><body>';
				echo str_repeat("\n" , 5);

				// 呼び出しもと出力
				echo "<div style=\"color:green;font-weight:bold;width:1000px\">\n";
				echo "{$data[0]["file"]} {$data[0]["line"]}行目\n";
				echo "</div>";
	
				// 引数出力
				echo "<pre style=\"background:#DDFFDD;padding:5px;width:1000px;\">";
				
				ob_start();
				var_dump($data[0]["args"][0]);
				$o = ob_get_clean();
				echo str_replace("\n" , "<br>" , $o);
				
				echo "</pre>\n";
	
				unset($data[0]);

				//呼び出しもとのコール元一覧を出力
				echo "<div style=\"color:green;font-weight:bold;width:1000px\">\n";
				foreach($data as $k=>$v){
					if(!empty($v["file"]) and !empty($v["line"]))
					echo "{$v["file"]} {$v["line"]}行目<br>\n";
				}//foreach
				echo "</div>\n";
				echo str_repeat("\n" , 5);

				echo "</body></head></html>";
	
				$o = ob_get_clean();
				$o = mb_convert_encoding($o , "SJIS" , "UTF-8");
				echo $o;
				
				// 1の時はexitしない
				if($type===0){
					exit;
				}
				#}
				if($type===3){
					exit;
				}
				break;

			//■ログ出力
			case 2:
				//「■2009/01/03 20:16;18 --- C:\xampp\htdocs\CAKEPROJECT\aqua\mb\app_model.php at line 8 ---------------」
				$pre = "\n■" . date("Y/m/d H:i;s") ." ". str_repeat("-" , 3) . " ";
				$debug_info = "{$data[0]["file"]} at line {$data[0]["line"]} " .str_repeat("-" , 15) . "\n\n";
				$output = $pre . $debug_info .  var_export($data[0]["args"][0] , 1);
				file_put_contents(OUTPUT_FILE_PATH . $fname , $output , FILE_APPEND | LOCK_EX);
		
		}//switch
	}//v


	/*
	 * @author seki
	 * ベーシック認証をかけます。
	 */
	if(!function_exists("basic_auth")){
		function basic_auth($id = "" , $pass = ""){
			if(isset($_SERVER["PHP_AUTH_USER"]) and $_SERVER["PHP_AUTH_PW"] == $pass and $_SERVER["PHP_AUTH_USER"] == $id){
				return true;
			}
			header("WWW-Authenticate: Basic realm=\"Please Enter Your Password\"");
			header("HTTP/1.0 401 Unauthorized");
			exit;
		}
	}

	/**
	 *
	 * @author seki
	 * @date 2008/04/09
	 *
	 * 配列とか見やすく出力
	 *
	 */
	function table($arr){
		echo "<table class='sheet'>\n";
		__Roop($arr);
		echo "</table>";
	}
	function __Roop($arr){
		foreach($arr as $k=>$v){
			if(is_array($v) or is_object($v)){
				$row = __Row($v);
				echo "<tr valign=\"top\">\n";
				echo "\t<th rowspan=\"{$row}\" >[{$k}]</td>\n";
				echo "</tr>\n";
				__Roop($v);
			}
			else{
				echo "<tr>\n";
				echo "\t<td>[{$k}]</td>\n";
				echo "\t<td>{$v}</td>\n";
				echo "</tr>\n";
			}
		}//foreach
	}
	function __Row($arr, &$row=null){
		if(!isset($row)){
			$row = 1;
		}
		$row = $row + count($arr);
		foreach($arr as $k=>$v){
			if(is_array($v) or is_object($v)){
				__Row($v,$row);
			}
		}//foreach
		return $row;
	}

	# 拡張子取得
	# @ editor bane 2008/08/05 18:58:02 
	if(!function_exists("get_ext")){
		function get_ext($str){
			$ary = explode("." , $str);
			return strtolower(end($ary));
		}
	}

	/**
	 * ファイルをダウンロードさせる
	 */
	function download($filepath=""){
		header('Content-Disposition: attachment; filename="'.basename($filepath).'"');
		header('Content-Type: application/octet-stream');
		header('Content-Transfer-Encoding: binary');
		header('Content-Length: '.filesize($filepath));
		exit;
	}

	/**
	 * ファイルダウンロード
	 */
	function _download($filepath=""){
		header("Cache-Control: public");
    	header("Pragma: public");
		header('Content-Disposition: attachment; filename="'.basename($filepath).'"');
		header('Content-Type: application/octet-stream');
		header('Content-Transfer-Encoding: binary');
		//header('Content-Length: '.filesize($filepath));
	}

	//エラー表示
	function err($msg){
		$html = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">' . "\n";
		$html .= '<html lang="ja">'. "\n";
		$html .= '<head>'. "\n";
		$html .= '<meta http-equiv="Content-Type" content="text/html; charset=Shift_jis">'. "\n";
		$html .= '<meta http-equiv="Content-Language" content="ja" />'. "\n";
		$html .= '<body><pre style="font-size:xx-small;">'. "\n";
		$html .= print_r($msg,1);
		$html .= '</body>'. "\n";
		$html .= '</html>'. "\n";
		echo enc($html , "SJIS");
		exit;
	}


	# 
	# @author Akabane 
	# @date 2011/05/24 16:28:27
	function if_link($str , $link , $if,$accesskey=""){
	
		$ac_key_str = "";

		if(!empty($accesskey)){
			$ac_key_str = "accesskey=\"{$accesskey}\"";
		}//if
	
		if($if){
			echo "<a href='" . lnk($link , true) . "' {$ac_key_str}>{$str}</a>";
		}else{
			echo $str;
		}
	}

	# 
	# @author Akabane 
	# @date 2011/05/24 16:28:27
	function if_e($true , $false , $if){
		if($if){
			echo $true;
		}else{
			echo $false;
		}
	}

	
	# tr、/tr を動的に出力する
	# @author Ikehata 
	# @date 2011/08/23 14:40:16
	function echo_tr($col_num=3){
		
		static $no = 1;
		
		if($no == 1){
			echo "<tr>";
		}//if
		
		if($no == $col_num*2){
			echo "</tr>";
			$no = 1;
		}else{
			$no ++;
		}
	}




	/**
	 * テーブルを格子状に出力する
	 *
	 */
	function roop_tr($unique_id=1 , $bgcolor = "#f4f4f4" , $onmouserOverColor = "#ddffdd"){
		static $no;
		
		if(!isset($no[$unique_id])){
			$no[$unique_id] = 0;
		}else{
			$no[$unique_id]++;
		}
		if($no[$unique_id] % 2 == 1){
			$res =  "<tr style=\"background:{$bgcolor};\" ";
		}
		else{
			$bgcolor= "#ffffff";
			$res =  "<tr ";
		}
		if($onmouserOverColor){
			$res .= "onmouseover=\"this.style.backgroundColor='{$onmouserOverColor}'\"  onmouseout=\"this.style.backgroundColor='{$bgcolor}'\"";
		}
		return $res.">";
	}

	/**
	 * もし$target == $str だったら、echo "style='backgrouned:{$bgColor}'";
	 */
	function if_bg( $target , $str , $bgColor="pink"){
		if($target==$str){
			echo " style=\"background-color:{$bgColor}\" ";
		}
	}
	/**
	 * もし$target == $match_str だったら、色づけして返す
	 * todo 配列対応してもイイかも2008/12/30 04:59:01 
	 */
	function if_fontColor( $target , $match_str , $str ,$color="#FF9D00"){
//////////////////////////////
		return $str;
		if($target==$match_str){
			$str = "<font color=\"{$color}\">{$str}</font>";
		}
		return $str;
	}

	/**
	 * もし$target == $match_str だったら、色づけして返す
	 * todo 配列対応してもイイかも2008/12/30 04:59:01 
	 */
	function ifEcho( $target , $match_str , $str ){
		if(is_array($target)){
			foreach($target as $k=>$v){
				if($v == $match_str){
					echo $str;
				}
			}//foreach
		}else{
			if($target == $match_str){
				echo $str;
			}
		}
	}

	/**
	 * 全角スペース、半角スペースで区切られたものを配列にして返す
	 * フリーワード検索等で使う
	 * @param string $str : 検索ワード <ex> 「晴れ　東京」
	 */
		function space_to_array($str){
			//大文字を小文字に
			$str = str_replace("　"," ",$str);
			$trim = trim($str);
			if($trim){
				//半角スペース多数を１つに
				$preg = preg_replace('/\s{2,}/'," ",$trim);
				$arr = explode(" ", $preg);
				return $arr;
			}
			else{
				return false;
			}
		}


#───────────────────────────────────────────────
#	function_array
#───────────────────────────────────────────────
/**
 * 多次元配列の合計を、条件を動的に指定して参照する
 * @author Akabane 
 * @date 2009/06/17 14:02:02 
 * 例> array_total($ary , "{n}.User.id");
 */
function array_total( $ary , $str="" , $first=true ){
	static $c = 0;
	static $sum = 0;
	
	if($first){
		$c = 0;
		$sum = 0;
	}

	$pattern = explode("." , $str);
	if($pattern[$c] == "{n}"){
		#v($ary , false);
		foreach($ary as $k=>$v){
			$params_str = "\$v";
			for($i=$c+1;$i<count($pattern) ;$i++){
				$params_str .= "[\"{$pattern[$i]}\"]";
			}//for
			$eval = ("\$param = ({$params_str}) ? {$params_str} : 0;");
			eval($eval);
			$sum += $param;
		}//foreach
	}
	else{
		$c++;
		array_total($ary[$pattern[$c-1]] , $str , false);
	}
	return $sum;
}

/**
 * 多次元配列の平均値を、条件を動的に指定して参照する
 * @author Akabane 
 * @date 2009/06/17 14:02:02 
 * 例> array_total($ary , "{n}.User.id");
 */
function array_avg( $ary , $str="" , $first=true , $isNotCountZero=false){
	static $key_num = 0;
	static $c = 0;
	static $sum = 0;
	
	if($first){
		$c = 0;
		$sum = 0;
		$key_num = 0;
	}

	$pattern = explode("." , $str);
	if($pattern[$c] == "{n}"){
		#v($ary , false);
		foreach($ary as $k=>$v){
			$params_str = "\$v";
			for($i=$c+1;$i<count($pattern) ;$i++){
				$params_str .= "[\"{$pattern[$i]}\"]";
			}//for
			$eval = ("\$param = ({$params_str}) ? {$params_str} : 0;");
			eval($eval);
			if($param){
				$key_num++;
				$sum += $param;
			}
		}//foreach
	}
	else{
		$c++;
		array_avg($ary[$pattern[$c-1]] , $str , false);
	}
	return @($sum / $key_num);
}

/**
 * 多次元配列の最小値を、条件を動的に指定して参照する
 * @author Akabane 
 * @date 2009/06/17 14:02:02 
 * 例> array_total($ary , "{n}.User.id");
 */
function array_min( $ary , $str="" , $first=true ){
	static $c = 0;
	static $min_key;
	static $min_num = false;
	
	if($first){
		$c = 0;
		$min_key = false;
		$min_num = 0;
	}

	$pattern = explode("." , $str);
	if($pattern[$c] == "{n}"){
		#v($ary , false);
		foreach($ary as $k=>$v){
			$params_str = "\$v";
			for($i=$c+1;$i<count($pattern) ;$i++){
				$params_str .= "[\"{$pattern[$i]}\"]";
			}//for
			$eval = ("\$param = ({$params_str}) ? {$params_str} : 0;");
			eval($eval);
			if(FALSE==$min_num OR $param < $min_num){
				$min_key = $k;
				$min_num = $param;
			}
		}//foreach
	}else{
		$c++;
		array_min($ary[$pattern[$c-1]] , $str , false);
	}
	$return = array("min_num"=>$min_num , "min_key"=> $min_key);
	return $return;
}

/**
 * 多次元配列の最大値を、条件を動的に指定して参照する
 * @author Akabane 
 * @date 2009/06/17 14:02:02 
 * 例> array_total($ary , "{n}.User.id");
 */
function array_max( $ary , $str="" , $first=true ){
	static $c = 0;
	static $max_key;
	static $max_num = 0;
	
	if($first){
		$c = 0;
		$max_key = false;
		$max_num = 0;
	}

	$pattern = explode("." , $str);
	if($pattern[$c] == "{n}"){
		#v($ary , false);
		foreach($ary as $k=>$v){
			$params_str = "\$v";
			for($i=$c+1;$i<count($pattern) ;$i++){
				$params_str .= "[\"{$pattern[$i]}\"]";
			}//for
			$eval = ("\$param = ({$params_str}) ? {$params_str} : 0;");
			eval($eval);
			if($param > $max_num){
				$max_key = $k;
				$max_num = $param;
			}
		}//foreach
	}else{
		$c++;
		array_max($ary[$pattern[$c-1]] , $str , false);
	}
	$return = array("max_num"=>$max_num , "max_key"=> $max_key);
	return $return;
}

/**
 * 多次元配列の合計を、条件を動的に指定してソートする
 * @author Akabane 
 * @date 2009/10/15 18:30:07 
 * 例> array_sort($ary , "{n}.User.id");
 */
function array_sort( $ary , $str="" , $flg="DESC" , $first=true ,$key=null){
	static $global_ary = null;
	static $c = 0;
	static $static_ary = null;
	
	if($first){
		$c = 0;
		$global_ary = $ary;
		$static_ary = null;
	}

	$pattern = explode("." , $str);
	if($pattern[$c] == "{n}"){
		foreach($ary as $k=>$v){
			$params_str = "\$v";
			for($i=$c+1;$i<count($pattern) ;$i++){
				$params_str .= "[\"{$pattern[$i]}\"]";
			}//for
			$eval = ("\$param = (isset({$params_str})) ? {$params_str} : 0;");
			eval($eval);
			$static_ary[$k] = $param;
			#v($eval);
		}//foreach
	}
	else{
		$c++;
		array_sort($ary[$pattern[$c-1]] , $str , $flg , false);
	}
	if($flg=="DESC"){
		array_multisort($static_ary , SORT_DESC , $global_ary);
	}else{
		array_multisort($static_ary , SORT_ASC , $global_ary);
	}
	
	return $global_ary;
}


#───────────────────────────────────────────────
#	function_mobile
#───────────────────────────────────────────────
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
		$mobileRegex = "(?:($docomoRegex)|($jphoneRegex)|($ezwebRegex)|($airhphoneRegex))";
		$c_id = "pc";
		if( preg_match("!$mobileRegex!", $agent, $matches)) {
		    $c_id = @$matches[1] ? 'i' :
		           (@$matches[2] ? 's' :
		           (@$matches[7] ? 'e' : 'w'));
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


#───────────────────────────────────────────────
#	function_project
#───────────────────────────────────────────────
	function createSwfPath($subject,$id){
		$dir = '';
		for($i=0;$i<4;$i++){
			$dir .=substr($id,$i*2,2).DS;
		}
		$swf_path = COMMON_SWF.$subject.DS.$dir.$id.'.swf';
		return $swf_path;
	}

	function make_user_dir($id){
		$fp = get_user_dir($id);
		if(!file_exists($fp)){
			mkdir($fp);
			chmod($fp,0777);
		}
	}

	# ID からディレクトリを取得
	# @author hoshiba
	# @date 2011/05/06
	function get_user_dir($id){
		/*
		$dir = substr($id,-2);
		$dir = str_pad($dir,2,0,STR_PAD_LEFT);
		# 2011/05/07 13:42:35 Akabane あっているかな？
		$fp = AVA_DIR . $dir . DS . $id;
		return $fp;
		*/
		$dir = getMultiDirPath($id);
		return AVA_DIR . $dir . DS;
	}


	# ID からディレクトリを取得
	# @author Akabane 
	# @date 2010/12/20 10:59:43
	function getMultiDirPath($id=0){
		$foot = substr($id , -3 , 3);
		$return = sprintf("%03d" , $foot);
		return $return;
	}

	# プリトモディレクトリ取得
	function getPhotoDirPath($id=0){
		$max = ceil($id / 1000 ) * 1000;
		$start = $max - 999;
		return "{$start}-{$max}";
	}


	# キャッシュディレクトリ作成
	function make_cache_dir(){
		
		$base = AVA_DIR ;
		
		for($i=0;$i<1000;$i++){
			$d = sprintf("%03d" , $i);
			$p = "{$base}{$d}/";
			mkdir($p);
			chmod($p,0777);
		}//for
	}

	# @author hoshiba 
	# @date 2011/03/17 
	function head($str , $bgcolor , $fcolor,$border=false){
		$o  = null;
		$career = Configure::read("career");
		
		if($career == 'e'){ 
			$o  = '<table width="100%" cellpadding="0" cellspacing="0" bgcolor="'.$bgcolor.'">';
            if($border){
			$o .= '<tr><td><img src="'.IMG_DOMAIN.'/common_img/web/line_'.$border.'.gif" height="1" width="100%"></td></tr>';
            }
			$o .= '<tr><td>';
			$o .= '<div style="font-size:x-small;color:'.$fcolor.';text-align:center;">';
			$o .= $str;
			$o .= '</div>';
			$o .= '</td></tr>';
            if($border){
			$o .= '<tr><td><img src="'.IMG_DOMAIN.'/common_img/web/line_'.$border.'.gif" height="1" width="100%"></td></tr>';
            } 
			$o .= '</table>';
		}else{
			$o .= '<div style="font-size:x-small;color:'.$fcolor.';text-align:center;background-color:'.$bgcolor.';">';
            if($border){
            $o .= '<img src="'.IMG_DOMAIN.'/common_img/web/line_'.$border.'.gif" height="1" width="100%" /><br />';
            }
			$o .= $str.'<br />';
            if($border){
            $o .= '<img src="'.IMG_DOMAIN.'/common_img/web/line_'.$border.'.gif"  height="1" width="100%" /><br />';
            } 
			$o .= '</div>';
		}
		echo $o;
	}




?>