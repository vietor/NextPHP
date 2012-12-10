<?php
require_once 'NpDbCommand.php';

class NpDbConnection
{
	private $pdo;

	public function __construct($dsn,$username,$password,$persistent=false)
	{
		$this->pdo=new PDO($dsn,$username,$password,array(PDO::ATTR_PERSISTENT=>$persistent));
	}

	public function __destruct()
	{
		$this->pdo=null;
	}

	public function beginTransaction()
	{
		return $this->pdo->beginTransaction();
	}

	public function inTransaction()
	{
		return $this->pdo->inTransaction();
	}

	public function endTransaction($rollBack=false)
	{
		if($rollBack)
			$this->pdo->rollBack();
		else
			$this->pdo->commit();
	}

	public function prepare($statement)
	{
		return new NpDbCommand($this->pdo->prepare($statement));
	}

	public function lastInsertId()
	{
		return $this->pdo->lastInsertId();
	}

}
?>