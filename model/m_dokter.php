<?php
	
	require_once($conf['model_dir'].'m_super.php');
	
	class mDokter extends mSuper {
		public $schema 	= 'imprint';
		public $table 	= 'ms_dokter';
		public $order 	= 'nip';
		public $key 	= 'nip';
		public $label 	= 'dokter';
		
		function simpleQuery(){
		
			$sql = "select * from ".$this->schema.".v_dokteroperasional";
			
			return $sql;	
		}
		
	}
?>