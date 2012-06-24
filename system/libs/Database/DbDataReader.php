<?php
class DbDataReader {
	private $pdoStmt;
	private $closed=false;
	
	public function __construct($pdoStmt) {
		$this->pdoStmt=$pdoStmt;
	}
	
	public function close() {
		if(!$this->closed) {
			$this->pdoStmt->closeCursor();
			$this->closed=true;
		}
	}
	
	public function rowCount() {
		return $this->pdoStmt->rowCount();
	}
	
	public function read($fetchAssociative=false) {
		$row=$this->pdoStmt->fetch($fetchAssociative ? PDO::FETCH_ASSOC : PDO::FETCH_NUM);
		if($row===false)
			$this->close();
		return $row;
	}
	
	public function readObject($className=null) {
		$object=$this->pdoStmt->fetchObject(is_null($className)?$className:'stdClass');
		if($object===false)
			$this->close();
		return $object;
	}	
}
?>