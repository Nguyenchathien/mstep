<?php


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
