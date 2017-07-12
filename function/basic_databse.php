<?php
/**
 * Created by PhpStorm.
 * User: Edward
 * Date: 12/29/16
 * Time: 11:16 AM
 */

class BasicDatabase{
	var $host='127.0.0.1';
	var $login='';
	var $password='';
	var $database='';
	var $port='3306';
	var $driver='mysqli';
	var $persistent=false;
	var $db_conn='';
	var $error;

	function __construct($option=[]){
		if($option) {
			$this->setOption($option);
		}
		$this->db_conn=new mysqli();
		$this->__connect();
	}

	public function setOption($option) {
		foreach($option as $key=>$val) {
			$this->{$key}=$val;
		}
	}

	function __connect(){
		switch($this->driver){
			default: // mysqli
				$this->db_conn->connect($this->host,$this->login,$this->password,$this->database,$this->port);
				$this->db_conn->set_charset('UTF-8');
				break;
		}
	}

	function query($query){
		$result_set=$this->db_conn->query($query);
		if(preg_match('/^(SELECT)/',mb_strtoupper(trim($query))) and $result_set){
			$data=[];
			while ($row=mysqli_fetch_assoc($result_set)){
				$data[]=$row;
			}
			return $data;
		}

		return $result_set;
	}

	function execute($query){
		try{
			$run=$this->db_conn->query($query);
			return $run;
		} catch (Exception $e){
			$this->error=$e->getMessage();
			return false;
		}
	}

	function close(){}
	function getClient($short_name) {
		if (!$short_name) return false;

	}
}