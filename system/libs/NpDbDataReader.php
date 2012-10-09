<?php
class NpDbDataReader {
	private $pdoStmt;

	public function __construct($pdoStmt) {
		$this->pdoStmt=$pdoStmt;
	}

	public function __destruct() {
		close();
	}

	private function close() {
		if(is_null($this->pdoStmt))
			return false;
		$this->pdoStmt->closeCursor();
		$this->pdoStmt=null;
		$this->pdoStmt=null;
	}

	public function rowCount() {
		if(is_null($this->pdoStmt))
			return false;
		return $this->pdoStmt->rowCount();
	}

	public function read($fetchAssociative=false) {
		if(is_null($this->pdoStmt))
			return false;
		$row=$this->pdoStmt->fetch($fetchAssociative ? PDO::FETCH_ASSOC : PDO::FETCH_NUM);
		if($row===false)
			$this->close();
		return $row;
	}

	public function readObject($className=null) {
		if(is_null($this->pdoStmt))
			return false;
		$object=$this->pdoStmt->fetchObject(is_null($className)?$className:'stdClass');
		if($object===false)
			$this->close();
		return $object;
	}
}
?>