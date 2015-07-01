<?php
	class Connection {
		
		function create($conf){
			
			require_once($conf['includes_dir'].'adodb5/adodb.inc.php');
			
			$conn = ADONewConnection($conf['db_driver']);
			$conn->Connect($conf['db_host'], $conf['db_username'], $conf['db_password'], $conf['db_dbname']);
			$conn->SetFetchMode(ADODB_FETCH_ASSOC);
			
			return $conn;
		}
	}
?>