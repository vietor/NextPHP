<?php
abstract class NpViewFace
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

	abstract public function display();
}

class NpTempleteView extends NpViewFace
{
	private $template;

	public function __construct($template)
	{
		$this->template=$template;
	}

	public function fetch()
	{
		extract($this->getVariables());
		ob_start();
		@include($this->template);
		return ob_get_clean();
	}

	public function display()
	{
		extract($this->getVariables());
		@include($this->template);
	}
}

class NpVariableView extends NpViewFace
{
	public function display()
	{
		foreach($this->getVariables() as $k=>$v) {
			$GLOBALS[$k] = $v;
		}
	}
}

class NpOutputView extends NpViewFace
{
	public function display()
	{
		echo json_encode($this->getVariables());
	}
}

class NpView
{
	public static function load($name='')
	{
		if($name=='')
			return new NpVariableView();
		else if($name=='output')
			return new NpOutputView();
		return new NpTempleteView(NP_APP_PATH.'view/'.$name.'.php');
	}
}
?>