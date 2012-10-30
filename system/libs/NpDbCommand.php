<?php
require_once('NpDbDataReader.php');

class NpDbCommand {
	const VAR_INT=PDO::PARAM_INT;
	const VAR_STR=PDO::PARAM_STR;
	const VAR_BOOL=PDO::PARAM_BOOL;
	const VAR_NULL=PDO::PARAM_NULL;

	public static function getPdoType($type) {
		static $map=array(
				'boolean'=>PDO::PARAM_BOOL,
				'integer'=>PDO::PARAM_INT,
				'string'=>PDO::PARAM_STR,
				'NULL'=>PDO::PARAM_NULL,
		);
		return isset($map[$type]) ? $map[$type] : PDO::PARAM_STR;
	}

	private $pdo;
	private $pdoStmt;

	public function __construct($pdo, $statement=null) {
		$this->pdo=$pdo;
		if(!is_null($statement))
			$this->prepare($statement);
	}

	public function __destruct() {
		$this->pdo=null;
		$this->pdoStmt=null;
	}

	public function prepare($statement) {
		if(!is_null($this->pdoStmt))
			$this->pdoStmt=null;
		$this->pdoStmt=$this->pdo->prepare($statement);
		return $this;
	}

	public function bindParam ($parameter, &$variable, $dataType=null) {
		if(is_null($this->pdoStmt))
			return false;
		if(is_null($dataType))
			$this->pdoStmt->bindParam($parameter, $variable, self::getPdoType(gettype($variable)));
		else
			$this->pdoStmt->bindParam($parameter, $variable, $dataType);
		return $this;
	}

	public function bindValue ($parameter, $variable, $dataType=null) {
		if(is_null($this->pdoStmt))
			return false;
		if(is_null($dataType))
			$this->pdoStmt->bindValue($parameter, $variable, self::getPdoType(gettype($variable)));
		else
			$this->pdoStmt->bindValue($parameter, $variable, $dataType);
		return $this;
	}

	private function bindValues($variables) {
		foreach($variables as $name=>$value) {
			if(is_int($name))
				$name=$name+1;
			$this->pdoStmt->bindValue($name,$value,self::getPdoType(gettype($value)));
		}
	}

	public function execute($variables=null) {
		return $this->queryExecute($variables);
	}

	public function rowCount() {
		return $this->pdoStmt->rowCount();
	}

	public function lastInsertId() {
		if(is_null($this->pdo))
			return false;
		return $this->pdo->lastInsertId();
	}

	private function queryExecute($variables=null) {
		if(is_null($this->pdoStmt))
			return false;
		if(!is_null($variables))
			$this->bindValues($variables);
		return $this->pdoStmt->execute();
	}

	public function queryReader($variables=null) {
		if(!$this->queryExecute($variables))
			return false;
		return new NpDbDataReader($this->pdoStmt);
	}

	public function queryRow($variables=null,$fetchAssociative=true, $className='') {
		if(!$this->queryExecute($variables))
			return false;
		if(!$fetchAssociative&&!empty($className))
			$result=$this->pdoStmt->fetchObject($className);
		else
			$result=$this->pdoStmt->fetch($fetchAssociative?PDO::FETCH_ASSOC:PDO::FETCH_NUM);
		$this->pdoStmt->closeCursor();
		return $result;
	}

	public function queryObject($variables=null,$className='stdClass') {
		return $this->queryRow($variables,false,$className);
	}

	public function queryAll($variables=null,$fetchAssociative=true, $className='') {
		if(!$this->queryExecute($variables))
			return false;
		if(!$fetchAssociative&&!empty($className))
			$result=$this->pdoStmt->fetchAll(PDO::FETCH_CLASS, $className);
		else
			$result=$this->pdoStmt->fetchAll($fetchAssociative?PDO::FETCH_ASSOC:PDO::FETCH_NUM);
		$this->pdoStmt->closeCursor();
		return $result;
	}

	public function queryObjectAll($variables=null,$className='stdClass') {
		return $this->queryAll($variables,false,$className);
	}
}
?>