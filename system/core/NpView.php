<?php
//! The interface of MVC's VIEW
/*!
 * @note VIEW is NpViewFace derived class
 */
abstract class NpViewFace
{
	private $variables=array();

	//! Set a value of a key
	public function assign($name,$value)
	{
		$this->variables[$name]=$value;
	}

	protected function getVariables()
	{
		return $this->variables;
	}

	//! Display VIEW content
	abstract public function display();
}

//! The class for a templete style VIEW
/*!
 * @note templete VIEW's filename is same of it's class name and must store in path application/view
 */
class NpTempleteView extends NpViewFace
{
	private $template;

	public function __construct($template)
	{
		$this->template=$template;
	}

	//! Fetch VIEW content as text string
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

//! The class for a variable style VIEW
class NpVariableView extends NpViewFace
{
	public function display()
	{
		foreach($this->getVariables() as $k=>$v) {
			$GLOBALS[$k] = $v;
		}
	}
}

//! The class for a JSON output style VIEW
class NpOutputView extends NpViewFace
{
	public function display()
	{
		echo json_encode($this->getVariables());
	}
}

//! The class for VIEW creation
class NpView
{
	const OUTPUT='*output*'; //!< output style VIEW name
	const VARIABLE='*variable*'; //!< variable style VIEW name

	/*!
	 * @breif Get a VIEW object
	 * @param[in] name VIEW's name
	 */
	public static function load($name=NpView::VARIABLE)
	{
		if($name==self::OUTPUT)
			return new NpOutputView();
		else if($name==self::VARIABLE)
			return new NpVariableView();
		return new NpTempleteView(NP_APP_PATH.'view/'.$name.'.php');
	}
}
?>