<?php
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


?>
