<?php
//! The class for database's statement
class NpDatabaseStatement
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

	/*!
	 * @brief Executes the SQL statement
	 * @param[in] variables the parameters array, indexed by number
	 * @return TRUE on success or FALSE on failure
	 */
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

	/*!
	 * @brief Executes the SQL statement
	 * @param[in] variables the parameters array, indexed by number
	 * @return current NpDatabaseStatement object
	 */
	public function query($variables=null)
	{
		$this->execute($variables);
		return $this;
	}

	//！ Returns the number of rows affected by the SQL statement
	public function rowCount()
	{
		if(!$this->isExecuted)
			return 0;
		return $this->pdoStmt->rowCount();
	}
	
	private function dealFetch($indexByName=true, $className=null)
	{
		if(!$this->isExecuted)
			return false;
		if(!$indexByName&&!empty($className))
			$result=$this->pdoStmt->fetchObject($className);
		else
			$result=$this->pdoStmt->fetch($indexByName?PDO::FETCH_ASSOC:PDO::FETCH_NUM);
		return $result;
	}
	
	/*!
	 * @brief Fetches the next row from a result set
	 * @param[in] indexByName array indexed by column name or number
	 */
	public function fetch($indexByName=true)
	{
		return $this->dealFetch($indexByName);
	}

	/*!
	 * @brief Fetches the next object from a result set
	 * @param[in] className requested class name
	 */
	public function fetchObject($className='stdClass')
	{
		return $this->dealFetch(false, $className);
	}
	
	private function dealFetchAll($indexByName=true, $className=null)
	{
		if(!$this->isExecuted)
			return false;
		if(!$indexByName&&!empty($className))
			$result=$this->pdoStmt->fetchAll(PDO::FETCH_CLASS, $className);
		else
			$result=$this->pdoStmt->fetchAll($indexByName?PDO::FETCH_ASSOC:PDO::FETCH_NUM);
		return $result;
	}

	/*!
	 * @brief Returns an array containing all of the result set rows
	 * @param[in] indexByName array indexed by column name or number
	 */
	public function fetchAll($indexByName=true)
	{
		return $this->dealFetchAll($indexByName);
	}

	/*!
	 * @brief Returns an object array containing all of the result set rows
	 * @param[in] className requested class name
	 */
	public function fetchAllObject($className='stdClass')
	{
		return $this->dealFetchAll(false, $className);
	}

	//! Fetch the SQLSTATE associated with the last operation
	public function errorCode()
	{
		return $this->pdoStmt->errorCode();
	}

	//! Fetch extended error information associated with the last operation
	public function errorInfo()
	{
		return $this->pdoStmt->errorInfo();
	}
}

//! The class for database
class NpDatabase
{
	private $pdo;

	public function __construct($dsn,$username,$password,$persistent)
	{
		$this->pdo=new PDO($dsn,$username,$password,array(PDO::ATTR_PERSISTENT=>$persistent));
	}

	public function __destruct()
	{
		$this->pdo=null;
	}

	//! Initiates a transaction
	public function beginTransaction()
	{
		return $this->pdo->beginTransaction();
	}

	//! Determine if inside a transaction
	public function inTransaction()
	{
		return $this->pdo->inTransaction();
	}

	//! Commit or Roll Back a transaction
	public function endTransaction($commit=true)
	{
		if($commit)
			$this->pdo->commit();
		else
			$this->pdo->rollBack();
	}

	/*!
	 * @brief Returns a NpDatabaseStatement object for execute
	 * @param[in] statement the SQL statement
	 */
	public function prepare($statement)
	{
		return new NpDatabaseStatement($this->pdo->prepare($statement));
	}

	//! Returns the ID of the last inserted row or sequence value
	public function lastInsertId()
	{
		return $this->pdo->lastInsertId();
	}

	//! Fetch the SQLSTATE associated with the last operation
	public function errorCode()
	{
		return $this->pdo->errorCode();
	}

	//! Fetch extended error information associated with the last operation
	public function errorInfo()
	{
		return $this->pdo->errorInfo();
	}
}
?>