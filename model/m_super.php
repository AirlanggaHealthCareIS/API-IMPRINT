<?php
	//super model
	class mSuper{// extends PHPUnit_Framework_TestCase{ //<-- use this when do TDD cycle
		public $schema 	= '';
		public $table 	= '';
		public $order 	= ''; // separated by comma (,)
		public $key 		= ''; // separated by comma (,) , but value separated by vert.line
		public $label 	= '';
		public $sequence	= '';
		
		
		public function table(){
			return $this->schema.".".$this->table;
		}
		
		public function sequence() {
			return $this->schema.'.'.$this->sequence;
		}
		
		public function simpleQuery(){
			$sql = "select * from ".$this->table();
			
			return $sql;
			
		}
		
		public function dataQuery($key, $colkey = '', $filter = '', $sql = ''){
			
			if(empty($sql))
				$sql = $this->simpleQuery();
				
			$sql .= " where ".$this->getCondition($key, $colkey, $filter);
			
			return $sql;
			
		}
		
		public function getCondition($valkey = '', $colkey = '', $filter = ''){
			
			if(empty($filter)){
				if($colkey == '')
					$colkey = $this->key;
				
				$colkey = explode(',',$colkey);
				$valkey = explode('|',$valkey);
				
				$cond = Array();
				foreach($colkey as $i => $col){
					$cond[] = $col." = '".$valkey[$i]."'";
				}
				
				$final =  implode(' and ',$cond);
				
			}else{
			
				$final =  $this->getListConditions($filter);
				
			}	
			
			return $final;
		}
		
		public function getListConditions($filter = ''){
			
			$sql = '';
			
			if($filter != ''){
			
				if(is_array($filter))
					$sql .= implode(' and ',$filter);
				else
					$sql .= $filter;
			}
			
			return $sql;
		}
		
		public function listQuery($filter = '', $sql = ''){
			
			if(empty($sql))
				$sql = $this->simpleQuery();
			
			if(!empty($filter) && $filter != ''){
			
				$sql .= " where ".$this->getListConditions($filter);
								
			}
			
			return $sql;
		}
				
		public function getData($conn, $key, $colkey = '', $filter = '', $sql = ''){
			
			$sql = $this->dataQuery($key, $colkey, $filter, $sql);
			
			return $conn->GetRow($sql);
		}
		
		public function getList($conn, $filter = '', $order = '', $limit = '', $offset = '', $sql = ''){
		
			$sql = $this->listQuery($filter, $sql);
			
			if($order != '')
				$sql .= " ORDER BY ".$order;
			if($limit != '')
				$sql .= " LIMIT ".$limit;
			if($offset != '')
				$sql .= " OFFSET ".$offset;
			
			$data	= $conn -> GetArray($sql);
			$err	= $conn -> ErrorNo();
			$msg	= $conn -> ErrorMsg();
			
			if(empty($msg))	$msg = "Transaksi berhasil";
			
			return	array($data, $err, $msg);
		}
		
		public function lastInsert($conn){
			$sql = 'select last_insert_id() from '.$this->table();
			
			return $conn->GetOne($sql);
		}
		
		public function getRecordKey($record){
			$key = explode(',',$this->key);
			
			$res = array();
			foreach($record as $k => $value){
				if(in_array($k,$key))
					$res[] = $value;
			}
			
			return implode($res,'|');			
		}
		
		public function setLog(&$record){
		
			$record['T_UPDATETIME'] = date('Y-m-d H:i:s');
			
		}
		
		public function insert($conn, $record = '', &$key = ''){
			
			$sql = "SELECT * FROM ".$this->table();
			$rs = $conn->SelectLimit($sql,1); 
			
			$this->setLog($record);
			
			$insertSQL = $conn->GetInsertSQL($rs, $record);
			$conn->Execute($insertSQL);
			
			$err	= $conn->ErrorNo();
			$msg	= $conn->ErrorMsg();
			if(empty($msg)) $msg = "Transaksi berhasil.";
			
			$key	= $this->lastInsert($conn);
			
			return array($err, $msg);
		}
		
		public function update($conn, $record = '', $key = '', $colkey = '', $filter = ''){
			
			$sql = "SELECT * FROM ".$this->table()." WHERE ".$this->getCondition($key, $colkey, $filter);
			$rs = $conn->Execute($sql); 
			
			$this->setLog($record);
			
			$updateSQL = $conn->GetUpdateSQL($rs, $record);
			$conn->Execute($updateSQL);
			
			$msg = $conn->ErrorMsg();
			if(empty($msg)) $msg = "Transaksi berhasil.";
			
			return array($conn->ErrorNo(), $msg );
		}
		
		public function delete($conn, $key = '', $colkey = '', $filter = ''){
			
			$sql = "DELETE FROM ".$this->table()." WHERE ".$this->getCondition($key, $colkey, $filter);
			$rs = $conn->Execute($sql); 
			
			$err	= $conn->ErrorNo();
			$msg	= $conn->ErrorMsg();
			if(empty($msg)) $msg = "Transaksi berhasil.";
			
			return array($err, $msg);
		}
	}
?>