<?php
require_once('DbDataReader.php');

class DbCommand {
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
	
	public function prepare($statement) {
		$this->pdoStmt=$this->pdo->prepare($statement);
		return $this;
	}
	
	public function bindParam ($parameter, &$variable, $dataType=null) {
		if(is_null($dataType))
			$this->pdoStmt->bindParam($parameter, $variable, self::getPdoType(gettype($variable)));
		else
			$this->pdoStmt->bindParam($parameter, $variable, $dataType);
		return $this;
	}
	
	public function bindValue ($parameter, $variable, $dataType=null) {
		if(is_null($dataType))
			$this->pdoStmt->bindValue($parameter, $variable, self::getPdoType(gettype($variable)));
		else
			$this->pdoStmt->bindValue($parameter, $variable, $dataType);
		return $this;
	}
	
	public function execute($variables=null) {
		if(!is_null($dataType)) {
			foreach($variables as $name=>$value)
				$this->pdoStmt->bindValue($name,$value,self::getPdoType(gettype($value)));
		}
		$this->pdoStmt->execute();
		return $this->pdoStmt->rowCount();
	}
	
	public function query($variables=null) {
		return $this->queryInternal('',0,$variables);
	}
	
	public function queryRow($fetchAssociative=false,$variables=null) {
		return $this->queryInternal('fetch',$fetchAssociative ? PDO::FETCH_ASSOC : PDO::FETCH_NUM, $variables);
	}
	
	public function queryAll($fetchAssociative=false,$variables=null) {
		return $this->queryInternal('fetchAll',$fetchAssociative ? PDO::FETCH_ASSOC : PDO::FETCH_NUM, $variables);
	}
	
	private function queryInternal($method,$mode,$variables=null) {
		if(!is_null($dataType)) {
			foreach($variables as $name=>$value)
				$this->pdoStmt->bindValue($name,$value,self::getPdoType(gettype($value)));
		}
		$this->pdoStmt->execute();
		if($method==='')
			$result=new CDbDataReader($this->pdoStmt);
		else {
			$mode=(array)$mode;
			$result=call_user_func_array(array($this->pdoStmt, $method), $mode);
			$this->pdoStmt->closeCursor();
		}
		return $result;
	}
	
	public function lastInsertId() {
		return $this->pdo->lastInsertId();
	}
}
?>