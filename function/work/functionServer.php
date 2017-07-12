<?php

/**
* ƒT[ƒo‚ÌŽí—Þ‚ð“Á’è‚·‚é

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
?>