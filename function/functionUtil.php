<?php
	
	
	#
	# @author Kiyosawa 
	# @date 
	function getDevelopMode(){

			$develop_mode="web";
			$hostname=strtolower(gethostname());
			if((preg_match("#spc#",$hostname)))   $develop_mode="dev";
			if((preg_match("#edwards#",$hostname)))   $develop_mode="local";
			if((preg_match("#hayahide#",$hostname))) $develop_mode="local";
			if((preg_match("#desktop-tk9b3rc#",$hostname))) $develop_mode="local";
			return $develop_mode;
	}

	/*
	function array_values_recursive($array,$values=array()){

			$data=array_shift($array);
			if(!is_array($data)){

					$values[]=$data;

					if(count($array)>0){
					
							return array_values_recursive($array,$values);
					}

					return $values;
			}

			$__values=array();
			foreach($data as $k=>$v){

					if(is_array($v)){

							//$__data=array_values_recursive($v,$values);
							//$__values=array_merge($__data,$__values);
							continue;
					} 

					$__values[]=$v;
			}

			$values=array_merge($values,$__values);
			return array_values_recursive($array,$values);
	}
	*/

	function day_diff($date1, $date2) {
	 
	    // 日付をUNIXタイムスタンプに変換
	    $timestamp1 = strtotime($date1);
	    $timestamp2 = strtotime($date2);
		//$timestamp1 = $date1;
	    //$timestamp2 = $date2;
	 
	    // 何秒離れているかを計算
	    $seconddiff = abs($timestamp2 - $timestamp1);
	 
	    // 日数に変換
	    $daydiff = $seconddiff / (60 * 60 * 24);
	 
	    // 戻り値
	    return $daydiff;
	}

	function getLatestFile($dir){

			if(!$files=glob($dir."*")) return false;

			$i_m_time=0;
			$latest_path="";
			foreach($files as $k=>$v){

					$_mtime=filemtime($v);
					if(max($_mtime,$i_m_time)!=$_mtime) continue;

					$latest_path=$v;
					$i_m_time=$_mtime;
			}

			return $latest_path;
	}

	function graphNumberFormat($value=0){

			return (String)$value;
	
			/*
			$value=(String)$value;
			if(count(explode(".",$value))>1) return (String)round($value,1);
			return $value.".0";
			 */
	}

	function mb_charset($str){
	
			foreach(array('UTF-8','SJIS','EUC-JP','ASCII','JIS') as $charcode){
	
					if(mb_convert_encoding($str,$charcode,$charcode)!=$str) continue;
					return $charcode;
			}
			
			return mb_detect_encoding($str);
	}

	function consoleWait($str=""){
	
			$yes=array("y","Y","yes","YES");
			$no =array("n","N","no","NO");

			while(1){

					echo "import file-------------------\n";
					if(!empty($str)) echo $str."\n";
					echo "[yes or no]\n";
					$line=trim(fgets(STDIN));
					if(in_array($line,$yes))  return true;
					if(in_array($line,$no))   return false;
			}
	}

	function make_seed()
	{
	  list($usec, $sec) = explode(' ', microtime());
	  return (float) $sec + ((float) $usec * 100000);
	}
	# @author Kiyosawa 
	# @date 
	function getBaseNum($num){

			return ("1".str_repeat("0",strlen($num)-1));
	}

	# ■マッチ時に使用する値
	# @author Kiyosawa 
	# @date 
	function langValues($num){
			
			#0,1		
			if(2>$num) return array($num);
			
			$values=array();
			$base=getBaseNum($num);				
			while(true){

					$values[]=(Int)$base;
					$n=($num%$base);
					if(1>$n) break;
					$base=getBaseNum($n);
			}
			
			return $values;
	}	

	# 文字をサイドに追加
	# @author Kiyosawa 
	# @date 
	function addStringSide($base,$str){
			
			$base=trim($base,$str);
			return "{$str}{$base}{$str}";
	}

	# ■誕生日を年齢に変換
	# @author Kiyosawa 
	# @date 
	function birthDayToAge($birthday){

			$now=date("Ymd");
			$birthday=str_replace("-","",$birthday);
			return ((Int)floor(($now-$birthday)/10000));		
	}

	# ■Viewに表示するフィールドを生成
	# @author Kiyosawa 
	# @date 
	function arraySprintf($format,$fields,$s="%s",$string=''){
		
		$field=array_shift($fields);
		$end_index=mb_strpos($format,$s)+mb_strlen($s);
		
		# 置換対象
		$change=mb_substr($format,0,$end_index);
		
		# 置換後
		$format=mb_substr($format,$end_index);
		$string.=str_replace($s,$field,$change);
		
		if(!empty($fields)){
			$string=arraySprintf($format,$fields,$s,$string);
		}
		
		if(!is_numeric(strpos($format,$s))){
			$string.=$format;
		}
		return $string;
	}

	function getIndex($path){
	
			$pathinfo=pathinfo($path);
			$file=$pathinfo["filename"];
			$e=explode("_",$file);
			return $e[1];
	}
	
	function isort($a,$b){
			
			$a_index=getIndex($a);
			$b_index=getIndex($b);
			return $a_index>$b_index;
	}
	
	//■絶対パスを並び替える
	function fileSort($list){
		
		uasort($list,"isort");
		return $list;
	}

	function checkDir($dir){
		$dir_ary = explode("/" , $dir);
		$new_ary = array();
		foreach($dir_ary as $k=>$v){
			if($v){
				$new_ary[] = $v;
				# cakePHP用。変なディレクトリを生成しないように
				$tmp = "/" . implode("/" , $new_ary) ;
				if(eregi(ROOT.DS , $tmp )){
					if(!file_exists($tmp)){
						mkdir($tmp);
						chmod($tmp , 0777);
					}
				}
			}
		}//foreach	
	}

	function dirClear($dir){
			
		$files=glob($dir."*");
		foreach($files as $k=>$file){

				if(!file_exists($file)) continue;
				unlink($file);
		} 
	}

	function array_remove( $val, &$array ) {

		$indexs=array();
	    foreach ($array as $i=>$v) {
	        if ($v!=$val) continue;
	        array_splice( $array, $i, 1 );
			$indexs[]=$i;
	    }
		return $indexs;
	}

	# 
	# @author Akabane 
	# @date 2011/07/22 14:44:26
	function time_limit($date , $if_long_echo = false , $pre = "あと" , $foot = "で終了" , $gap="" , $color="red"){
		
		if(!$gap){
			$gap = strtotime($date) - strtotime("NOW");
		}
		$ago = timetostr($gap);
		
		
		if(!empty($ago["after"])){
			return dd("m月d日H:i" , $date) . "まで";
		}
		
		# 一週間以上期間があるなら
		if(!empty($ago["day"]) AND $ago["day"] >= 7 ){
			if($if_long_echo){
				return $pre . dd("n月d日まで" , $date) . $foot;
			}else{
				return null;
			}
		}
		# 7日未満なら
		if(!empty($ago["day"]) AND $ago["day"] < 7 AND $ago["day"] > 2){
			
			return "<span style='color:{$color};'>{$pre}{$ago["day"]}日{$foot}</span>";
		}
		# 3日未満
		elseif(!empty($ago["day"])){

			$ary[] = "hour";
			$ary[] = "minute";
			foreach($ary as $k=>$v){
				if(empty($ago[$v])){
					$ago[$v] = "0";
				}
			}//foreach

			$hour = $ago["day"] * 24 + $ago["hour"];
			return "<span style='color:{$color};'>{$pre}{$hour}時間{$ago["minute"]}分{$foot}</span>";
		}

		# 1日未満
		elseif(!empty($ago["hour"]) and $ago["hour"] > 0){

			$ary[] = "hour";
			$ary[] = "minute";
			foreach($ary as $k=>$v){
				if(empty($ago[$v])){
					$ago[$v] = "0";
				}
			}//foreach

			return "<span style='color:{$color};'>{$pre}{$ago["hour"]}時間{$ago["minute"]}分{$foot}</span>";
		}
		# 1時間未満
		elseif(!empty($ago["minute"]) and $ago["minute"] > 0){

			$ary[] = "second";
			$ary[] = "minute";
			foreach($ary as $k=>$v){
				if(empty($ago[$v])){
					$ago[$v] = "0";
				}
			}//foreach

			return "<span style='color:{$color};'>{$pre}{$ago["minute"]}分{$ago["second"]}秒{$foot}</span>";
		}
		# 1分未満
		elseif(isset($ago["second"])){
			
			$ago["second"]++;
			return "<span style='color:{$color};'>{$pre}{$ago["second"]}秒{$foot}</span>";
		}else{
			return false;
		}
	}

	function getUserAvaPath($user_id , $type="normal" , $output="web"){
		$dir = substr($user_id , -3,3);
		
		if($type == "dl_tpl" OR $type == "my_photo"){
			return "mount/user_ava/{$dir}/{$user_id}_{$type}.jpg";
		}//if
		
		return "mount/user_ava/{$dir}/{$user_id}_{$type}.gif";
		
	}

	function getUserCoodiPath($user_id , $type="normal" , $output="web",$is_small=false){
	
		$dir = substr($user_id , -3,3);
		if($is_small){
			
			return "mount/coodi/{$dir}/{$user_id}_{$type}.gif";
		}
		
		return "mount/coodi/{$dir}/{$user_id}_{$type}.jpg";
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
			"",
			"",
			"",
			"",
			"",
			"",
			"",
			"",
			"",
		);
		
		$return = $array[$put_number];
		$put_number++;
		return $return;
	}


	function emoji($name=""){
		$array = array(
			"",
			"",
			"",
			"",
			"",
			"",
			"",
			"",
			"",
		);
		
		$return = $array[$name-1];
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
		if(DEVELOP_MODE != "hogea" AND DEVELOP_MODE != "dev"){
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


	function ak($_data , $type=0 , $fname="v.log" , $str_code = "Shift_jis"){

		if($_GET["opensocial_viewer_id"] == 55412){
			if($type == 0){
				v($GLOBALS["CAKE_SQL"], 1);
			}//if
			v($_data , $type , $fname , $str_code);
		}

	}


	function v($_data , $type=0 , $fname="v.log" , $str_code = "UTF-8"){
		$data = debug_backtrace();
		switch($type){
			//■出力 exitする
			case 0:
			//■出力 exitしない
			case 1:
				if (!headers_sent()) header("Content-Type: text/html; charset={$str_code}");
				ob_start();
				echo '<html><head><body>';
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
				#$o = mb_convert_encoding($o , "SJIS" , "UTF-8");
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

	# br を動的に出力する
	# @author Ikehata 
	# @date 2011/08/23 14:40:16
	function echo_br($num=3, $height="0"){
		static $no = 1;
		
		if($no == $num){
			echo "<br>";
			
			if(!empty($height)){
				echo "<div><img src=\"http://ava-a.mbga.jp/i/dot.gif\" width=\"1\" height=\"{$height}\" alt=\"スペース\"></div>";
			}//if
			
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

#──────────────────────────────────────────────
# function_array
#──────────────────────────────────────────────
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


#──────────────────────────────────────────────
# function_mobile
#──────────────────────────────────────────────
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

		# 2012/02/28 k.meguro スマートフォン対応 → 三国統一から流用
		$androidRegex = "Android";
		$iphoneRegex = "iPhone";
		#$mobileRegex = "(?:($docomoRegex)|($jphoneRegex)|($ezwebRegex)|($airhphoneRegex))";
		$mobileRegex = "(?:($docomoRegex)|($jphoneRegex)|($ezwebRegex)|($airhphoneRegex)|($androidRegex)|($iphoneRegex))";

		$c_id = "pc";
		if( preg_match("!$mobileRegex!", $agent, $matches)) {
		    $c_id = @$matches[1] ? 'i' :
		           (@$matches[2] ? 's' :
		           (@$matches[7] ? 'e' : 'w'));

			# 2012/02/28 k.meguro スマートフォン対応 → 三国統一から流用
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


	# スマートフォン判定 → 三国統一から流用
	# @author Meguro 
	# @date 2012/02/28 12:54:05
	function isSmartphone($car = null){
		if ($car == null) {
			$car = getCareerByAgent();
		}
		
		if ($car == "an" || $car == "ip") {
			return true;
		}
		return false;
	}
}


#──────────────────────────────────────────────
# function_project
#──────────────────────────────────────────────
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
				$o .= '<tr><td><img src="'.IMG_DOMAIN.'/common_img/web/line_'.$border.'.gif" height="2" width="240"></td></tr>';
            		}
			$o .= '<tr><td>';
			$o .= '<div style="font-size:xx-small;color:'.$fcolor.';text-align:center;">';
			$o .= $str;
			$o .= '</div>';
			$o .= '</td></tr>';
            		if($border){
				$o .= '<tr><td><img src="'.IMG_DOMAIN.'/common_img/web/line_'.$border.'.gif" height="2" width="240"></td></tr>';
            		}
			$o .= '</table>';
		}else{
			$o .= '<div style="color:'.$fcolor.';text-align:center;background-color:'.$bgcolor.';">';
			
			$o .= '<div><img src="http://ava-a.mbga.jp/i/dot.gif" width="1" height="1" alt="スペース"></div>';
			
            if($border){
            	$o .= '<img src="'.IMG_DOMAIN.'/common_img/web/line_'.$border.'.gif" height="2" width="240" /><br />';
            }
			$o .= $str.'<br />';
            if($border){
            	$o .= '<img src="'.IMG_DOMAIN.'/common_img/web/line_'.$border.'.gif"  height="2" width="240" /><br />';
            } 

			$o .= '<div><img src="http://ava-a.mbga.jp/i/dot.gif" width="1" height="1" alt="スペース"></div>';


			$o .= '</div>';
		}
		echo $o;
	}


#──────────────────────────────────────────────
# function_server
#──────────────────────────────────────────────
/**
* サーバの種類を特定する

* 
*
*/
function getServerType(){
	$uname = php_uname("n");
	$ip = @$_SERVER["REMOTE_ADDR"];
	$server_name = @$_SERVER["SERVER_NAME"];
	$document_root = @$_SERVER["DOCUMENT_ROOT"];

	//hogea
	if($uname == "hogea.net"){
		return "hogea";
	}
	elseif($server_name == "hogea.net"){
		return "hogea";
	}
	//localhost
	elseif($ip == "127.0.0.1"){
		return "local";
	}
	elseif(eregi("xampp" , $document_root)){
		return "local";
	}
	//other
	else{
		return "web";
	}
}//getServerType



#──────────────────────────────────────────────
# function_social
#──────────────────────────────────────────────
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
				$url = str_replace("/mb/" , "" , $url);
			}
			
			#if(preg_match("/#(.*)/",$url,$m)){
			# 2011/12/21 12:31:36 ikehata追記　ページネートが持ってくるURLを置き換え
			if(preg_match("#/dev/rush#",$url,$m)){
				if(!empty($m)){
					$name = $m[0];
					$url = str_replace($name,"",$url);
				}else{
					$name = null;
				}
			}//if
			$domain = SOCIAL_DOMAIN;
			
			if(isSmartPhone()){
				$url = APP_URL_SP . "?url=" . urlencode($domain . $url);
			}else{
				$url = APP_URL . "?url=" . urlencode($domain . $url);
			}
			
			if($guid){
				$url.="&guid=on";
			}
			#$url .= $name;	# 2011/12/21 12:46:37 meguro削除
		}
		
		elseif( preg_match("#banex_jp\/dev#is" , $_SERVER["SCRIPT_FILENAME"])){
			$url =  ROOT_DOMAIN . $url;
		}
		
		

		$url = str_replace("/../","/",$url);

		if($return){
			return $url;
		}else{
			echo $url;
		}
	}



	# aタグの下線の色を統一する
	# @author Ikehata 
	# @date 2012/01/31 21:33:33
	function lnk_full_color($lnk_value, $url, $color, $img_url="", $return = false){

		$img_val = null;

		# 画像リンクありなら画像表示用のタグを変数に入れる
		if(!empty($img_url)){
			$img_val = "<div><img src=\"{$img_url}\"></div>";
		}

		$r = "<a href=\"{$url}\">{$img_val}<span style=\"color:{$color}\"><u style=\"{$color}\">{$lnk_value}</u></span></a>";
		
		
		if($return){
			return $r;
		}//if
		
		echo $r;
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


#──────────────────────────────────────────────
# function_swf
#──────────────────────────────────────────────
function h32($sizeint){
	return pack("V",$sizeint);
}
function h16($sizeint){
	return pack("v",$sizeint);
}

function calctaglen($dataarray){
	$ret = 0;
	foreach($dataarray as $key => $value){
	$ret += strlen($key)+strlen($value) + 11;

	}
	return $ret+1;
}
function maketag($dataarray){
	$tag = "\x3f\x03";
	$taglen = calctaglen($dataarray);
	$tag .= h32($taglen);

	foreach($dataarray as $key => $value){
		$tag .= "\x96".h16(strlen($key)+2)."\x00".$key."\x00";
		$tag .= "\x96".h16(strlen($value)+2)."\x00".$value."\x00";
		$tag .= "\x1d";
	}
	$tag .= "\x00";
	return $tag;
}
/*
// フォームから渡ってきた変数代入文相当のアクションタグ生成
$doactiontag = maketag($HTTP_GET_VARS);

// ベースとなる SWF ファイル指定
$srcswf = "base.swf";

// 読み込み
$fr = fopen($srcswf,"rb");

// ヘッダ長さは可変なので途中まで読んでから確定させる
// 背景色設定タグよりも前に DoActionTag 挿入するとエラーでるので
// 便宜的にそいつもヘッダ扱い($headlen 計算の末尾の "+5" 部分)
$headtmp = fread($fr,9);
$rb = ord(substr($headtmp,8,1))>>3; // rectbit
$headlen = ceil(((( 8 - (($rb*4+5)&7) )&7)+ $rb*4 + 5 )/8) + 12 + 5;
$head = $headtmp.fread($fr,$headlen-9);

// 挿入によるファイルサイズ変更反映のためのヘッダ変更
$oldsize = filesize($srcswf);
$newsize = $oldsize+strlen($doactiontag);
$newhead = substr($head,0,4).h32($newsize).substr($head,8);

$tail = fread($fr, $oldsize-$headlen);
fclose($fr);

//書きだし
header("Content-Type: application/x-shockwave-flash");
print $newhead.$doactiontag.$tail;

# ファイルに書きだす場合
$destswf = "out.swf";
$fw = fopen($destswf,"wb");
fwrite($fw,$newhead);
fwrite($fw,$doactiontag);
fwrite($fw,$tail);
fclose($fw);
print "<html><head></head><body><a href=\"$destswf\">$destswf</a></body>"
*/


#──────────────────────────────────────────────
# function_time
#──────────────────────────────────────────────
	#
	# @author Akabane 
	# @date 
	function sec2min($sec,$fmt = "%d:%02d"){
		$min = floor($sec / 60);
		$sec = $sec % 60;
		$res = sprintf($fmt , $min , $sec);
		#v($res);
		return $res;
	}


	# 
	# @author Ikehata 
	# @date 2011/09/14 21:14:42
	function sec2Hour($sec,$fmt = "%2d時間%02d分%02d秒"){
		
		$hour = floor($sec / 60 / 60);
		
		
		if(empty($hour)){
			if($sec < 60){
				$res = $sec . "秒";
			}else{
				$min = floor($sec / 60);
				$sec = $sec % 60;
				$res = sprintf("%d分%02d秒" , $min , $sec);
			}
		}else{
			
			$min = ($sec - ($hour * 60 * 60)) / 60;
			
			$sec = $sec % 60;
			$res = sprintf($fmt , $hour, $min , $sec);
		}
		
		#v($res);
		return $res;
	}


	# 
	# @author Akabane 
	# @date 2011/05/24 13:20:39
	function echo_date($date , $today_format = "H:i" , $before_format = "n月d日"){
		if(dd("Y-m-d" , $date) == TODAY){
			echo dd($today_format , $date);
		}
		else{
			echo dd($before_format , $date);
		}
	}

	/**
	 * 日付を現在時刻から「何時間何分前」に変換します
	 * 一日以上前は日付で返します。
	 */
	function getTimeAgo($date , $foot="前")
	{
		
		$now = time();
		$dates = strtotime($date);
		$diff = $now - $dates;
		$ago = timetostr($diff);
		
		if(!empty($ago["day"])){
			$res = $ago["day"]  . "日{$foot}";
			return $res;
		}
		elseif(!empty($ago["hour"]) and $ago["hour"] > 0){
			$hour = (INT)($ago["hour"]);
			$res = $hour ."時間{$foot}";
			return $res;
		}elseif(!empty($ago["minute"]) and $ago["minute"] > 0){
			$res =  $ago["minute"]."分{$foot}";
			return $res;
		}
		elseif(isset($ago["second"])){
			$ago["second"]++;
			return  $ago["second"]."秒{$foot}";
		}
	}
	
    //日付整形
	function dd($date_format,$db_time){
		return date($date_format,strtotime($db_time));
	}

	function calenderInMonth($month,$year){

			$lastday=date("d",mktime(0,0,0,$month+1,0,$year));
			return $lastday;
	}

	/**
	 * カレンダー配列を返す
	 *
	 */
	function calender($month=null,$year=null){
		
		if(!$year AND preg_match("/(\d{4})-(\d{2})/" , $month , $match) ){
			$year = $match[1];
			$month = $match[2];
		}

		//月末日
		//$lastday = date("d",mktime(0,0,0,$month+1,0,$year));
		$lastday=calenderInMonth($month,$year);

		//カレンダー生成処理
		for($i=1;$i<=$lastday;$i++)
		{
			//for文で生成したカレンダー日付
			$roop_date = str_pad($i+1,2,"0",STR_PAD_LEFT);
		#	$calender["$year-$month-$roop_date"]["date"] = "$year-$month-$roop_date";
			//$calender["$year-$month-$roop_date"]["youbi"] = youbi("$year-$month-$roop_date");
			$calender[]=sprintf("%02d",$i);
		
		}
		return $calender;
	}
	/**
	 * 曜日を返す
	 *
	 */
	function youbi($date)
	{
	    $day = array("日","月","火","水","木","金","土");
		return $day[$date];
	}	

	
	function isSunday($date)
	{
	    $sday = strtotime($date);
	    $res = date("w", $sday);
		return ($res==0);
	}

	function diff_time_str($to,$from=""){
		$time = timetostr(diff_time($to,$from));
		$diff_time = "";

		if(isset($time["after"])){
			$diff_time =  (isset($time["second"]) ? "{$time["second"]}秒" : $diff_time);
			$diff_time =  (isset($time["minute"]) ? "{$time["minute"]}分" : $diff_time);
			$diff_time =  (isset($time["hour"]) ? "{$time["hour"]}時間" : $diff_time);
			$diff_time =  (isset($time["day"]) ? "{$time["day"]}日" : $diff_time);
		
		}else{
			#$diff_time =  (isset($time["second"]) ? "<font color=green>{$time["second"]}秒前" : $diff_time);
			#$diff_time =  (isset($time["minute"]) ? "<font color=red>{$time["minute"]}分前" : $diff_time);
			#$diff_time =  (isset($time["hour"]) ? "<font color=blue>{$time["hour"]}時間前" : $diff_time);
			#$diff_time =  (isset($time["day"]) ? "{$time["day"]}日前" : $diff_time);
			$diff_time = null;
		}
		return $diff_time;
	}

	function diff_time_str_full($to,$from=""){
		$time = timetostr(diff_time($to,$from));
		$diff_time = "";

		if(isset($time["after"])){
			$diff_time .=  (isset($time["day"]) ? "{$time["day"]}日" : $diff_time);
			$diff_time .=  (isset($time["hour"]) ? "{$time["hour"]}時間" : $diff_time);
			$diff_time .=  (isset($time["minute"]) ? "{$time["minute"]}分" : $diff_time);
			#$diff_time .=  (isset($time["second"]) ? "{$time["second"]}秒" : $diff_time);
		
		}else{
			#$diff_time =  (isset($time["second"]) ? "<font color=green>{$time["second"]}秒前" : $diff_time);
			#$diff_time =  (isset($time["minute"]) ? "<font color=red>{$time["minute"]}分前" : $diff_time);
			#$diff_time =  (isset($time["hour"]) ? "<font color=blue>{$time["hour"]}時間前" : $diff_time);
			#$diff_time =  (isset($time["day"]) ? "{$time["day"]}日前" : $diff_time);
			$diff_time = null;
		}
		return $diff_time;
	}

	/***
	 * 時刻の差分を[秒]で返す。デフォルトの比較対象は現時刻
	 *
	 * @param  datetime  $to 前の時間
	 * @param  datetime  $from 後の時間
	 * @return int 差分の秒数
	 *
	 */
	function diff_time($to,$from=null){
		if(!$from){
			$from = "now";
		}
		if(!is_numeric($from)){
			$from = strtotime($from);
		}//if
		if(!is_numeric($to)){
			$to = strtotime($to);
		}//if
		
		$diff= $from - $to;
		return $diff;
	}


	#
	# @author Akabane 
	# @date 
	function calc_rest_time($sec){
		$ary = timetostr($sec);
		if(@$ary["minute"]){
			return $ary["minute"]."分";
		}
		return $ary["second"]."秒";
	}
	
	/**
	 * [秒]を[日]、[時間]、[分]、[秒]に変換する関数
	 * 
	 * @param int $second 秒
	 * @return array 日/時/分/秒
	 */
	function timetostr( $second )
	{
		if($second < 0){
			$res["after"] = true;
			$second = $second * -1;
		}
		
		$second_tmp = $second;

		//設定
		$day = 24 * 60 * 60;
		$hour = 60 * 60;
		$minute = 60;
		
		//日
		if ($second_tmp > $day )
		{
			$res["day"] = floor($second_tmp / $day);
			$second_tmp   = $second_tmp % $day;
		}
		//時
		if($second_tmp > $hour){
			$res["hour"] = floor($second_tmp / $hour);
			$second_tmp = $second_tmp % $hour;
		}
		//分
		if($second_tmp > $minute)
		{
			$res["minute"] = floor($second_tmp / $minute);
			$second_tmp = $second_tmp % $minute;
		}
		//秒
		$res["second"] = $second_tmp;
		return $res;
	}



#──────────────────────────────────────────────
# function_tsv
#──────────────────────────────────────────────
// 2007/09/26

/**
 * tsv() : TSVファイルを配列にして返す関数
 * 
 * @date 2007/09/26
 * @依存 define "TSV_DIR" TSVファイルのあるディレクトリ
 * @前提 TSVファイルが2列である事
 * @author akabane
 * @param string $fname  ファイル名
 * @param string $encodee phpの内部エンコーディング
 * @return array $data
 */
function tsv($fname , $encode="UTF-8")
{
	//TSVディレクトリ指定

	if(defined('PACKAGE_TSV')){
		$fname = PACKAGE_TSV . $fname;
	}
	elseif(defined('TSV')){
		$fname = TSV . $fname;
	}
	if(!file_exists($fname)){
		return false;
	}
	$fl = file($fname);
	if($encode){
		mb_convert_variables($encode, "Shift_Jis", $fl);
	}
	foreach($fl as $k=>$v)
	{
		//行のタブを分解
		$column = explode("\t",$v);
		$key = trim($column[0]);
		$value = trim($column[1]);
		$data[$key] = $value;
	}
	return $data;
}
//2009/06/16 17:45:02 
function _tsv_parse($ary)
{
	$fl = explode("\n" , r("\r" , "" , $ary));
	foreach($fl as $k=>$v)
	{
		//行のタブを分解
		$column = explode("\t",$v);
		$key = trim($column[0]);
		$value = trim($column[1]);
		$data[$key] = $value;
	}
	return $data;
}

/**
 * tsv_r() <tsv()のはいくおりてぃ版ｗ>
 *
 * tsv()関数の上位版。この関数では、３列以上のtsvファイルを扱う時や、
 * 返す配列の形状を変更したい時に使います。
 * 
 * 
 * @date 2007/09/26
 * @依存 define "TSV_DIR" TSVファイルのあるディレクトリ
 * @author akabane
 * @param string $fname  ファイル名
 * @param vool $key_name_flg  各配列の要素名を1行目の対応する列のものにするか
 * @param vool $title_flg  各配列のkeyを各行の1列目にするか(0の時は連番)
 * @param string $encodee phpの内部エンコーディング
 * @return array $data
 */
function tsv_r($fname,$key_name_flg=1,$title_flg=1,$encode="UTF-8")
{
	if(file_exists($fname)){
		
	}elseif(defined('TSV')){
		$fname = TSV . $fname;
	}
	
	if(!file_exists($fname)){
		return false;
	}
	$fl = file($fname);
	if($encode){
		mb_convert_variables($encode, "Shift_Jis", $fl);
	}
	foreach($fl as $k=>$v)
	{
		//行のタブを分解
		$column = explode("\t",$v);
		//▼1行目特有の処理
		if($k==0)
		{
			foreach($column as $i=>$j)
			{
				//$key_name_flg がたっている時各配列の要素名を対応する行の１列目の文字列に
				//よって、１行目は、要素として加えない
				if($key_name_flg){
					$title[$i] = trim($j);
				}
				//$key_name_flg がたってなければ、当然１行目も要素として数える
				else{
					$title[$i] = trim($i);
					//$title_flg分岐
					if($title_flg){
						$key_name = trim($column[0]);
					}
					else{
						$key_name = $k;
					}
					$data[$key_name][$i] = trim($j);
				}
			}
		}
		//▼2行目からの処理
		else{
			foreach($column as $i=>$j)
			{
				//$title_flg分岐
				if($title_flg){
					$key_name = trim($column[0]);
				}
				//$key_name_flgをたてていたら、keyが必然1減る
				elseif($key_name_flg){
					$key_name = $k-1;
				}
				else{
					$key_name = $k;
				}
				$data[$key_name][$title[$i]] = trim($j);
			}
		}
	}
	return $data;
}
/**
 * TSVの対応表に基づいて置換をする関数
 *
 * 1行目⇒置換前 2列目⇒置換後
 *
 * @date 2007/09/26
 * @依存 define "TSV_DIR" TSVファイルのあるディレクトリ
 * @前提 TSVファイルが2列である事
 * @author akabane
 * @param  string $fname  ファイル名
 * @param  string  置換対象文字列
 * @param  string $encodee phpの内部エンコーディング
 */
function tsv_replace($fname,$string,$encode="UTF-8")
{
	//TSVディレクトリ指定
	if(defined('TSV')){
		$fname = TSV . $fname;
	}
	if(!file_exists($fname)){
		return false;
	}
	$fl = file($fname);
	if($encode){
		mb_convert_variables($encode, "Shift_Jis", $fl);
	}
	foreach($fl as $k=>$v){
		//行のタブを分解
		$column = explode("\t",$v);
		$before[] = trim($column[0]);
		$after[] = trim($column[1]);
	}
	$string_replaced = str_replace($before,$after,$string);
	return $string_replaced;
}

function checkIP($p_ip, $p_permit_ip){

	list($ip, $mask_bit) = explode("/", $p_permit_ip);
	$ip_long = ip2long($ip) >> (32 - $mask_bit);
	$p_ip_long = ip2long($p_ip) >> (32 - $mask_bit);
	if($p_ip_long==$ip_long) return true;
	return false;
}

function cut($str,$length=10,$tail="..."){

}

// 暗号化を行う
function cipher_encrypt($input,$key)
{
		// 指定した暗号のブロックサイズを得る
		$size = mcrypt_get_block_size(MCRYPT_BLOWFISH, MCRYPT_MODE_ECB);
		// PKCS5Padding ブロック長に満たないサイズを埋める
		$input = pkcs5_pad($input, $size);
		// 使用するアルゴリズムおよびモードのモジュールをオープンする
		$td = mcrypt_module_open(MCRYPT_BLOWFISH, '',  MCRYPT_MODE_ECB, '');
		// オープンされたアルゴリズムの IV の大きさを返す
		$ivsize = mcrypt_enc_get_iv_size($td);
		// MCRYPT_RAND の初期化を行う
		srand();
		// 乱数ソースから初期化ベクトル(IV)を生成する
		// ECB以外では復号にこのIV(初期化ベクトル)が必要です。
		// ECBではIVは使用されませんが、IVがないとエラーが出ます。
		$iv = mcrypt_create_iv($ivsize, MCRYPT_RAND);
		// 暗号化に必要な全てのバッファを初期化する
		mcrypt_generic_init($td, $key, $iv);
		// データを暗号化する
		$data = mcrypt_generic($td, $input);
		// 暗号化モジュールを終了する
		mcrypt_generic_deinit($td);
		// mcrypt モジュールを閉じる
		mcrypt_module_close($td);
		return $data;
}

// 複合化を行う
function cipher_decrypt($input,$key)
{
	 	// 指定した暗号のブロックサイズを得る
	 	$size = mcrypt_get_block_size(MCRYPT_BLOWFISH, MCRYPT_MODE_ECB);
	 	// 使用するアルゴリズムおよびモードのモジュールをオープンする
	 	$td = mcrypt_module_open(MCRYPT_BLOWFISH, '',  MCRYPT_MODE_ECB, '');
	 	// オープンされたアルゴリズムの IV の大きさを返す
	 	$ivsize = mcrypt_enc_get_iv_size($td);
	 	// MCRYPT_RAND の初期化を行う
	 	srand();
	 	// 乱数ソースから初期化ベクトル(IV)を生成する
	 	// ECB以外では暗号化に用いたIV(初期化ベクトル)が必要です。
	 	// ECBではIVは使用されませんが、IVがないとエラーが出ます。
	 	$iv = mcrypt_create_iv($ivsize, MCRYPT_RAND);
	 	// 暗号化に必要な全てのバッファを初期化する
	 	mcrypt_generic_init($td, $key, $iv);
	 	// データを復号する
	 	$data = mdecrypt_generic($td, $input);
	 	// 暗号化モジュールを終了する
	 	mcrypt_generic_deinit($td);
	 	// mcrypt モジュールを閉じる
	 	mcrypt_module_close($td);
	 	// PKCS5Padding 埋められたバイト値を除く
	 	$data = pkcs5_unpad($data, $size);
	 	return $data;
}

// PKCS5Padding
// ブロック長に満たないサイズを埋める
function pkcs5_pad($text, $blocksize)
{
		$pad = $blocksize - (strlen($text) % $blocksize);
		return $text . str_repeat(chr($pad), $pad);
}

// PKCS5Padding
// 埋められたバイト値を除く
function pkcs5_unpad($text)
{
 		$pad = ord($text{strlen($text)-1});
 		if ($pad > strlen($text)) return false;
 		if (strspn($text, chr($pad), strlen($text) - $pad) != $pad) return false;
 		return substr($text, 0, -1 * $pad);
}

function changeToBr($str=""){

		return preg_replace("/\n|\r|\r\n/","<br />",$str);
}

		function makeDatePeriod($start, $end) {

				$diff=(strtotime($end)-strtotime($start))/(60*60*24);
				for($i=0;$i<=$diff;$i++) $period[date("Ymd",strtotime($start."+".$i."days"))]=array();
				return $period;
		}

function mstepJsonEncode($value){
	
	return json_encode($value,JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);
}

function tabRemove($value){

    return preg_replace("/(\t+)/"," ",$value);
}

function Unix_GetPidArray() {

	$ret=array();
	$cmd="/bin/ps -e -o pid,args";
	$fp=popen($cmd,"r");
	while(($line=fgets($fp))!=false){

			$line   =ltrim($line);
			$pos    =strpos($line," ");
			$pid    =intval(substr($line,0,$pos));
			$args   =substr($line,$pos+1);
			if($pid <= 0) continue;
			$ret[]=$pid;
	}

	fclose($fp);
	return $ret;
}

function processWait($process_id,$sleep=3){

	$current_plist=Unix_GetPidArray();
	if(!in_array($process_id,$current_plist)) return;
	sleep($sleep);
	return processWait($process_id,$sleep);
}
?>

