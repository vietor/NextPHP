<?php
class NpDbCommand
{	
	const VAR_INT=PDO::PARAM_INT;
	const VAR_STR=PDO::PARAM_STR;
	const VAR_BOOL=PDO::PARAM_BOOL;
	const VAR_NULL=PDO::PARAM_NULL;

	private static function getPdoType($type)
	{
		static $map=array(
			'boolean'=>PDO::PARAM_BOOL,
			'integer'=>PDO::PARAM_INT,
			'string'=>PDO::PARAM_STR,
			'NULL'=>PDO::PARAM_NULL,
		);
		return isset($map[$type])?$map[$type]:PDO::PARAM_STR;
	}

	private $pdoStmt;
	private $isExecuted;

	public function __construct($pdoStmt)
	{
		$this->pdoStmt=$pdoStmt;
		$this->isExecuted=false;
	}

	public function __destruct()
	{
		if($this->isExecuted)
			$this->pdoStmt->closeCursor();
		$this->pdoStmt=null;
	}

	public function execute($variables=null)
	{
		if($this->isExecuted) {
			$this->isExecuted=false;
			$this->pdoStmt->closeCursor();
		}
		if($variables!==null) {
			foreach($variables as $name=>&$value) {
				if(is_int($name))
					$name=$name+1;
				$this->pdoStmt->bindParam($name,$value,self::getPdoType(gettype($value)));
			}
		}
		if($this->pdoStmt->execute())
			$this->isExecuted=true;
		return $this->isExecuted;
	}
	
	public function query($variables=null)
	{
		$this->execute($variables);
		return $this;
	}

	public function rowCount()
	{
		if(!$this->isExecuted)
			return 0;
		return $this->pdoStmt->rowCount();
	}

	public function fetch($fetchAssociative=true, $className='')
	{
		if(!$this->isExecuted)
			return false;
		if(!$fetchAssociative&&!empty($className))
			$result=$this->pdoStmt->fetchObject($className);
		else
			$result=$this->pdoStmt->fetch($fetchAssociative?PDO::FETCH_ASSOC:PDO::FETCH_NUM);
		return $result;
	}

	public function fetchObject($className='stdClass')
	{
		return $this->fetch(false,$className);
	}

	public function fetchAll($fetchAssociative=true, $className='')
	{
		if(!$this->isExecuted)
			return false;
		if(!$fetchAssociative&&!empty($className))
			$result=$this->pdoStmt->fetchAll(PDO::FETCH_CLASS, $className);
		else
			$result=$this->pdoStmt->fetchAll($fetchAssociative?PDO::FETCH_ASSOC:PDO::FETCH_NUM);
		return $result;
	}

	public function fetchAllObject($className='stdClass')
	{
		return $this->fetchAll(false,$className);
	}
}
?>