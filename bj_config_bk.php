<?php

/*  ----------------------------------------------------------------------------------
AQUA Framework customed cake.1.2
(C)BANEXJAPAN 2006-2009 All Rights Reserved. http://banex.jp
--------------------------------------------------------------------------------------  */

// ----------------------------------------------------------------------------------
// 共通関数をセットする為に、または、汎用的な変数をセットする為に最初に呼び出し
// ----------------------------------------------------------------------------------
//

/**
* フレームワーク外で呼び出された時、CAKEで提供するdefineを用意する
*/

if(!defined("ROOT")){

		define("ROOT", dirname(__FILE__));
}
if (!defined('DS')) {

		define('DS', DIRECTORY_SEPARATOR);
}

/**
 * functionディレクトリ
 */
define("FUNCTION_DIR", dirname(ROOT). DS . "function" . DS);

// functionディレクトリにあるファイルは自動的にロードされます
foreach (glob(FUNCTION_DIR."*.php") as $k=>$v){
	require_once($v);
}

// ----------------------------------------------------------------------------------
// プロジェクト毎の設定
// ----------------------------------------------------------------------------------

$hostmap["dev"]  ="mstep.spcvn";
$hostmap["web"]  ="mstep.localhost";
$hostmap["local"]="mstep.localhost";

define("DEVELOP_MODE",getDevelopMode());
/*
* データベースの設定
* もしlocal開発を行っていない場合、hogea開発を行っていない場合は、片方を除去してしまっても構いません
* functionServer.php上の、getServerType()でハンドリングされます
*/

#■local
$mysqls["local"]["master"]["host"]="127.0.0.1";
$mysqls["local"]["master"]["login"]="root";
$mysqls["local"]["master"]["password"]="";
$mysqls["local"]["master"]["database"]="spc_mstep_master";
$mysqls["local"]["master"]["unix_socket"]="/tmp/mysql.sock";
$mysqls["local"]["master"]["password_file"]='';

#■Staging
$mysqls["dev"]["master"]["host"]="localhost";
$mysqls["dev"]["master"]["login"]="root";
$mysqls["dev"]["master"]["password"]="kaido1651";
$mysqls["dev"]["master"]["database"]="spc_mstep_master";
$mysqls["dev"]["master"]["unix_socket"]="";
$mysqls["dev"]["master"]["password_file"]=dirname(__FILE__).DS."mysql_dev.cnf";

#■本番
$mysqls["web"]["master"]["host"]="";
$mysqls["web"]["master"]["login"]="";
$mysqls["web"]["master"]["password"]="";
$mysqls["web"]["master"]["database"]="";
$mysqls["web"]["master"]["unix_socket"]="";
$mysqls["web"]["master"]["password_file"]=dirname(__FILE__).DS."mysql_web.cnf";

// client databse config
$client_db_conf["host"]="";
$client_db_conf["login"]="";
$client_db_conf["password"]="";
$client_db_conf["database"]="";
$client_db_conf["unix_socket"]="";
$client_db_conf["password_file"]="";

$http_host = '';
$client='';
// Checking for run on command line or web browser
if(PHP_SAPI == 'cli') {
	$http_host = $hostmap[DEVELOP_MODE];
	if(isset($_SERVER['argv'][5])){ // Detect to run command line with client
		$client=$_SERVER['argv'][5];
	}
}
else {
	$http_host = $_SERVER['HTTP_HOST'];
	//v($http_host);
	preg_match('/^(.*)\.'.$hostmap[DEVELOP_MODE].'/',$http_host,$client);
	if(isset($client[1])) {
		$client = $client[1];
		//v($client);
	} else {
		$client = "m1";
		//v($client);
		//include('404.html'); // it will be landing page
		//die;
	}
}
//
define("SITE_TITLE","メガステップ");
define("DOMAIN",$http_host);
//define("DOMAIN",$hostmap[DEVELOP_MODE]);
define("ROOT_DOMAIN","http://".DOMAIN);
define("WEB_BASE_URL",ROOT_DOMAIN);
define("ASSETS_URL",WEB_BASE_URL.DS."assets".DS);
define("WEATHER_IMG_DIR",ASSETS_URL."img".DS."weather".DS);

/**
 * MASTER_DATA
 */
define("MASTER_DATA", dirname(ROOT).DS."master_data".DS);
define("TSV", MASTER_DATA."tsv" . DS);

// API Version
define("API_CURRENT_VERSION",1);

$client_data_session=array();
// Get Client's Database config
if($client) {
	$db_basic=new BasicDatabase($mysqls[DEVELOP_MODE]['master']);
	$client_result=$db_basic->query("SELECT * FROM `clients` AS c LEFT JOIN `client_profile` AS cf ON (c.id=cf.id) WHERE c.`short_name`='".$client."' and c.del_flg=0");
	if(!$client_result) {
		include('404.html');
		die;
	}
	
	// make data for Client session
	$client_field_map=tsv('master_client_info.tsv');
	if($client_field_map) {
		foreach ($client_field_map as $key => $title) {
			if (isset($client_result[0][$key])) {
				$client_data_session[$key] = $client_result[0][$key];
			}
		}
	}
	
	// client databse config
	$client_db_conf["host"]=$client_result[0]['db_host'];
	$client_db_conf["login"]=$client_result[0]['db_user'];
	$client_db_conf["password"]=$client_result[0]['db_password'];
	$client_db_conf["database"]=$client_result[0]['db_name'];
	$client_db_conf["unix_socket"]="";
	$client_db_conf["password_file"]="";
}

define('CLIENT_DATA_SESSION', $client_data_session);

define("CLIENT",$client);

// Define constant Default database config
define("MYSQL_DEFAULT_LOGIN",$client_db_conf['login']);
define("MYSQL_DEFAULT_PASS" ,$client_db_conf['password']);
define("MYSQL_DEFAULT_HOST" ,$client_db_conf['host']);
define("MYSQL_DEFAULT_DB"   ,$client_db_conf['database']);
define("MYSQL_DEFAULT_UNIXSOCKET",$client_db_conf['unix_socket']);

// Define constant Master database config
define("MYSQL_MASTER_LOGIN",$mysqls[DEVELOP_MODE]["master"]["login"]);
define("MYSQL_MASTER_PASS" ,$mysqls[DEVELOP_MODE]["master"]["password"]);
define("MYSQL_MASTER_HOST" ,$mysqls[DEVELOP_MODE]["master"]["host"]);
define("MYSQL_MASTER_DB"   ,$mysqls[DEVELOP_MODE]["master"]["database"]);
define("MYSQL_MASTER_UNIXSOCKET",$mysqls[DEVELOP_MODE]["master"]["unix_socket"]);

# End of client database connection

$options["local"]["EDIT_EFFECTIVE_SECOND"]=300;
$options["local"]["REMARKS_SCHEDULE_MAX_LENGTH"]=10;
$options["local"]["TIME_KEY"]="EDIT_LOG";
$options["local"]["SCHEDULE_BLOCK_NUM"]=50;
$options["local"]["SEND_GIRD_APIKEY"]="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";
$options["local"]["GOOGLE_API_KEY"]  ="AIzaSyANHl8-nxwdHyv3FWD6YBEWXEAGLkNwse0";
$options["dev"]["EDIT_EFFECTIVE_SECOND"]=300;
$options["dev"]["REMARKS_SCHEDULE_MAX_LENGTH"]=10;
$options["dev"]["TIME_KEY"]="EDIT_LOG";
$options["dev"]["SCHEDULE_BLOCK_NUM"]=50;
$options["dev"]["SEND_GIRD_APIKEY"]="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";
$options["dev"]["GOOGLE_API_KEY"]  ="AIzaSyANHl8-nxwdHyv3FWD6YBEWXEAGLkNwse0";
$options["web"]["EDIT_EFFECTIVE_SECOND"]=300;
$options["web"]["REMARKS_SCHEDULE_MAX_LENGTH"]=10;
$options["web"]["TIME_KEY"]="EDIT_LOG";
$options["web"]["SCHEDULE_BLOCK_NUM"]=50;
$options["web"]["SEND_GIRD_APIKEY"]="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";
$options["web"]["GOOGLE_API_KEY"]  ="AIzaSyANHl8-nxwdHyv3FWD6YBEWXEAGLkNwse0";

// edit limited time.
define("EDIT_EFFECTIVE_SECOND",$options[DEVELOP_MODE]["EDIT_EFFECTIVE_SECOND"]);
define("REMARKS_SCHEDULE_MAX_LENGTH",$options[DEVELOP_MODE]["REMARKS_SCHEDULE_MAX_LENGTH"]);
define("TIME_KEY","EDIT_LOG",$options[DEVELOP_MODE]["TIME_KEY"]);
define("SCHEDULE_BLOCK_NUM",$options[DEVELOP_MODE]["SCHEDULE_BLOCK_NUM"]);
define("SEND_GIRD_APIKEY",$options[DEVELOP_MODE]["SEND_GIRD_APIKEY"]);
define("GOOGLE_API_KEY",$options[DEVELOP_MODE]["GOOGLE_API_KEY"]);

class DATABASE_CONFIG {

		var $default = array(
		
				'datasource' => 'Database/MysqlLog',		
				'driver' => 'mysql',
				'persistent' => false,
				'host' => MYSQL_DEFAULT_HOST,
				'login' => MYSQL_DEFAULT_LOGIN,
				'password' => MYSQL_DEFAULT_PASS,
				'database' => MYSQL_DEFAULT_DB,
				'encoding' => 'utf8',
				'unix_socket' => MYSQL_DEFAULT_UNIXSOCKET
		);

	var $master = array(

		'datasource' => 'Database/MysqlLog',
		'driver'     => 'mysql',
		'persistent' => false,
		'host'       => MYSQL_MASTER_HOST,
		'login'      => MYSQL_MASTER_LOGIN,
		'password' => MYSQL_MASTER_PASS,
		'database' => MYSQL_MASTER_DB,
		'encoding' => 'utf8',
		'unix_socket' => MYSQL_MASTER_UNIXSOCKET
	);
}

/**
 * E-mail configuration
 *
 * Override E-mail configuration
 * @author Edward <duc.nguyen@spc-vn.com>
 * @date 2016-12-21
 */

#Local
$email['local']['host'] = 'smtp.sendgrid.net';
$email['local']['port'] = 587;
$email['local']['username']='sgwvrb7h@kke.com';
$email['local']['password']='kk1031river';
$email['local']['from']='info@dandori-taro.com';
$email['local']['transport']='smtp';
$email['local']['encryption']='ssl';
$email['local']['auth_mode']='login';
$email['local']['timeout']=30;
$email['local']['charset_iso_2022_jp']=false;
$email['local']['returnPath']="kiyosawa-n@spc-jpn.co.jp";
$email['local']['to']="info.spcvn@gmail.com";

#dev
$email['dev']['host'] = 'smtp.sendgrid.net';
$email['dev']['port'] = 587;
$email['dev']['username']='sgwvrb7h@kke.com';
$email['dev']['password']='kk1031river';
$email['dev']['from']='info@dandori-taro.com';
$email['dev']['transport']='smtp';
$email['dev']['encryption']='ssl';
$email['dev']['auth_mode']='login';
$email['dev']['timeout']=30;
$email['dev']['charset_iso_2022_jp']=false;
$email['dev']['returnPath']="kiyosawa-n@spc-jpn.co.jp";
$email['dev']['to']="info.spcvn@gmail.com";

#web
$email['web']['host'] = 'smtp.sendgrid.net';
$email['web']['port'] = 587;
$email['web']['username']='sgwvrb7h@kke.com';
$email['web']['password']='kk1031river';
$email['web']['from']='info@dandori-taro.com';
$email['web']['transport']='smtp';
$email['web']['encryption']='ssl';
$email['web']['auth_mode']='login';
$email['web']['timeout']=30;
$email['web']['charset_iso_2022_jp']=false;
$email['web']['returnPath']="kiyosawa-n@spc-jpn.co.jp";
$email['web']['to']="info.spcvn@gmail.com";

define('EMAIL_HOST',$email[DEVELOP_MODE]['host']);
define('EMAIL_PORT',$email[DEVELOP_MODE]['port']);
define('EMAIL_USER',$email[DEVELOP_MODE]['username']);
define('EMAIL_PASS',$email[DEVELOP_MODE]['password']);
define('EMAIL_FROM',$email[DEVELOP_MODE]['from']);
define('EMAIL_TRANSPORT',$email[DEVELOP_MODE]['transport']);
define('EMAIL_ENCRYPTION',$email[DEVELOP_MODE]['encryption']);
define('EMAIL_AUTH_MODE',$email[DEVELOP_MODE]['auth_mode']);
define('EMAIL_TIMEOUT',$email[DEVELOP_MODE]['timeout']);
define('EMAIL_CHARSET_ISO',$email[DEVELOP_MODE]['charset_iso_2022_jp']);
define("EMAIL_RETURN_PATH",$email[DEVELOP_MODE]['returnPath']);
define("EMAIL_TO",$email[DEVELOP_MODE]['to']);

class EmailConfig{

	public $scheduleReport=array(

		'host' => EMAIL_HOST,
		'port' => EMAIL_PORT,
		'username' => EMAIL_USER,
		'password' => EMAIL_PASS,
		'from' => EMAIL_FROM,
		'transport' => EMAIL_TRANSPORT,
		'encryption' => EMAIL_ENCRYPTION,
		'auth_mode' => EMAIL_AUTH_MODE,
		'timeout' => EMAIL_TIMEOUT,
		'charset_iso_2022_jp' => EMAIL_CHARSET_ISO
	);
	
	public $contact=array(
		'host' => EMAIL_HOST,
		'port' => EMAIL_PORT,
		'username' => EMAIL_USER,
		'password' => EMAIL_PASS,
		'to' => EMAIL_TO,
		'returnPath' => EMAIL_RETURN_PATH,
		'from' => EMAIL_FROM,
		'transport' => EMAIL_TRANSPORT,
		'encryption' => EMAIL_ENCRYPTION,
		'auth_mode' => EMAIL_AUTH_MODE,
		'timeout' => EMAIL_TIMEOUT,
		'charset_iso_2022_jp' => EMAIL_CHARSET_ISO
	);

	public $default=array(
		'host' => EMAIL_HOST,
		'port' => EMAIL_PORT,
		'username' => EMAIL_USER,
		'password' => EMAIL_PASS,
		'from' => EMAIL_FROM,
		'transport' => EMAIL_TRANSPORT,
		'encryption' => EMAIL_ENCRYPTION,
		'auth_mode' => EMAIL_AUTH_MODE,
		'timeout' => EMAIL_TIMEOUT,
		'charset_iso_2022_jp' => EMAIL_CHARSET_ISO
	);

}

# libs にパスを通す
$path = APP."Lib";
set_include_path(get_include_path().PATH_SEPARATOR.$path);

?>
