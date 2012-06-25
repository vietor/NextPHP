<?php
require_once('DbCommand.php');

class DbConnection {	
	private $pdo;
	
	public function __construct($dsn='',$username='',$password='') {
		$this->pdo=new PDO($dsn,$username,$password);
		$this->pdo->setAttribute(PDO::ATTR_PERSISTENT,true);
	}
	
	public function close() {
		$this->pdo=null;
	}
	
	public function beginTransaction() {
		$this->pdo->beginTransaction();
	}
	
	public function inTransaction() {
		$this->pdo->inTransaction();
	}
	
	public function endTransaction($rollBack=false) {
		if($rollBack)
			$this->pdo->rollBack();
		else
			$this->pdo->commit();
	}
	
	public function command() {
		return new DbCommand($this->pdo);
	}
	
	public function prepare($statement) {
		return new DbCommand($this->pdo, $statement);
	}
}
?>