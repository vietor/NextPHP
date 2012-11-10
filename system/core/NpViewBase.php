<?php
class NpViewBase
{
	private $variables=array();

	public function assign($name,$value)
	{
		$this->variables[$name]=$value;
	}

	protected function getVariables()
	{
		return $this->variables;
	}
	
	public function display()
	{
		foreach($this->variables as $k=>$v) {
			$GLOBALS[$k] = $v;
		}
	}
}
?>