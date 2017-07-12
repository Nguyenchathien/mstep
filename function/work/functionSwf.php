<?php

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
?>