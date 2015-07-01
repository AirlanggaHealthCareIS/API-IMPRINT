<?php
	
	require_once($conf['model_dir'].'m_super.php');
	
	class mSMS extends mSuper {
		public $schema 	= 'imprint';
		public $table 	= 'outbox';
		public $order 	= 'ID';
		public $key 	= 'ID';
		public $label 	= 'SMS';

		function kirim($conn, $notujuan, $text){
			
			$record							= array();
			$record['DestinationNumber']	= $notujuan;
			$record['TextDecoded']		= $text;
			$record['CreatorID']			= "imprint";
			
			list($err, $msg) = $this->insert($conn, $record);
			
			return array($err, $msg);
		}
	}
?>