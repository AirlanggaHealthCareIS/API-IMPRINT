<?php
	
	require_once($conf['model_dir'].'m_super.php');
	
	class mNotifikasi extends mSuper {
		public $schema 	= 'imprint';
		public $table 	= 'ms_notifikasi';
		public $order 	= 'idnotifikasi';
		public $key 	= 'idnotifikasi';
		public $label 	= 'notifikasi';
		
	}
?>