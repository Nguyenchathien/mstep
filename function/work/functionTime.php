<?php

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
		$lastday = date("d",mktime(0,0,0,$month+1,0,$year));

		//カレンダー生成処理
		for($i=0;$i<$lastday;$i++)
		{
			//for文で生成したカレンダー日付
			$roop_date = str_pad($i+1,2,"0",STR_PAD_LEFT);
		#	$calender["$year-$month-$roop_date"]["date"] = "$year-$month-$roop_date";
			$calender["$year-$month-$roop_date"]["youbi"] = youbi("$year-$month-$roop_date");
		
		}
		return $calender;
	}
	/**
	 * 曜日を返す
	 *
	 */
	function youbi($date)
	{
	    $sday = strtotime($date);
	    $res = date("w", $sday);
	    $day = array("<font color=red><b>日</b></font>", "月", "火", "水", "木", "金", "<font color=blue><b>土</b></font>");
	    return $day[$res];
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

?>
