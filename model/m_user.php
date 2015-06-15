<?php
	
	require_once($conf['model_dir'].'m_super.php');
	
	class mUser extends mSuper {
		public $schema 		= 'imprint';
		public $table 		= 'ms_user';
		public $order 		= 'userid';
		public $key 		= 'userid';
		public $label 		= 'pengguna';
		public $sequence	= 'ms_user_userid_seq';
		
		public function  simpleQuery(){
		
			$sql = "select * from ".$this->schema.".v_user";
			
			return $sql;	
		}
		
		public function  generateToken(){
			$string	= date("s:I:h d-m-y")."imprint";
			
			return  md5($string);
		}
		
		public function  cekToken($conn, $token, $userid, $regid){
			
			$cek = $this->getData($conn, $userid);
			
			if(empty($cek)){
				$err = 1;
				$msg = "Pengguna tidak ditemukan";
				$res = null;
			}
			else{
				if($token != $cek['token']){
					$err = 1;
					$msg = "Anda tidak memiliki hak akses atas user ini.";
					$res = null;
				}
				else if($regid != $cek['gcmregid']){
					$err = 2;
					$msg = "Perangkat tidak sesuai";
					$res = null;
				}
				else{
					$err = 0;
					$msg = "Otentikasi berhasil";
					$res = $cek;
				}
			}
			return array($err, $msg, $res);
		}
	}
?>