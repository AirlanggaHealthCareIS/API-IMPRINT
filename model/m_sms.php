<?php
	
	require_once($conf['model_dir'].'m_super.php');
	
	class mSMS extends mSuper {
		public $schema 	= 'public';
		public $table 	= 'outbox';
		public $order 	= 'ID';
		public $key 	= 'ID';
		public $label 	= 'SMS';

		function kirim($conn, $notujuan, $text){
			
			$record							= array();
			$record['"DestinationNumber"']	= $notujuan;
			$record['"TextDecoded"']		= $text;
			$record['"CreatorID"']			= "imprint";
			
			$col = $val = array();
			foreach($record as $k => $v){
				$col[] = $k;
				$val[] = $v;
			}
			$sql = "INSERT INTO ".$this->table()." (".implode(',',$col).") VALUES ('".implode("','",$val)."')";
			$conn->Execute($sql);
			
			$err	= $conn->ErrorNo();
			$msg	= $conn->ErrorMsg();
			
			return array($err, $msg);
		}
	}
?>