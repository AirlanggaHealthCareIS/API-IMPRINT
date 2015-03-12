<?php
	
	require_once($conf['model_dir'].'m_super.php');
	
	class mPasien extends mSuper {
		public $schema 	= 'imprint';
		public $table 	= 'ms_pasien';
		public $order 	= 'idpasien';
		public $key 	= 'idpasien';
		public $label 	= 'pasien';
		
	}
?>