<?php
require_once 'NpDbCommand.php';

class NpDbConnection
{
	private $pdo;

	public function __construct($dsn='',$username='',$password='')
	{
		$this->pdo=new PDO($dsn,$username,$password);
		$this->pdo->setAttribute(PDO::ATTR_PERSISTENT,true);
	}

	public function __destruct()
	{
		$this->pdo=null;
	}

	public function beginTransaction()
	{
		$this->pdo->beginTransaction();
	}

	public function inTransaction()
	{
		$this->pdo->inTransaction();
	}

	public function endTransaction($rollBack=false)
	{
		if($rollBack)
			$this->pdo->rollBack();
		else
			$this->pdo->commit();
	}

	public function command()
	{
		return new NpDbCommand($this->pdo);
	}

	public function prepare($statement)
	{
		return new NpDbCommand($this->pdo, $statement);
	}

	public function execute($statement, $variables=null)
	{
		return $this->prepare($statement)->execute($variables);
	}
}
?>