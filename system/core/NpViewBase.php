<?php
class NpViewBase {
	private $variables=array();

	public function assign($name,$value) {
		$this->variables[$name]=$value;
	}

	public function getVariables($vars=null) {
		if(!is_null($vars) && is_array($vars))
			$variables=array_merge($this->variables, $vars);
		else
			$variables=$this->variables;
		return $variables;
	}
}
?>