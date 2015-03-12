<?php
	class Connection {
		
		function create($conf){
			
			require_once($conf['includes_dir'].'adodb5/adodb.inc.php');
			
			$strconn = 'host=' . $conf['db_host'] . ' dbname='  . $conf['db_dbname'] . ' user=' . $conf['db_username'] . ' password=' . $conf['db_password'];
			if($conf['db_port'] != '')
			$strconn .= ' port=' . $conf['db_port'];

			$conn = ADONewConnection($conf['db_driver']);
			$conn->Connect($strconn);
			$conn->SetFetchMode(ADODB_FETCH_ASSOC);
			
			return $conn;
		}
	}
?>