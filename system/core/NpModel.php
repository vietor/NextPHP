<?php
class NpModelException extends Exception
{
	public function __construct($code=0)
	{
		parent::__construct('',$code);
	}
}
 
//! The parent class for MVC's MODEL
/*!
 * @note MODEL is a NpModel derived class
 * <pre>
 * MODEL's filename is same of it's class name and must store in path application/model
 * example:
 *   MODEL name: UserModel
 *   file  name: UserModel.php
 * </pre>
 */
abstract class NpModel
{
	/*!
	 * @breif Terminate MODEL process
	 * @param[in] code an integer status
	 */
	protected function terminate($code)
	{
		throw new NpModelException($code);
	}

	private static $_models=array();

	/*!
	 * @breif Get a MODEL object
	 * @param[in] name MODEL name
	 */
	public static function load($name)
	{
		if(!isset(self::$_models[$name])) {
			require_once(NP_APP_PATH.'model/'.$name.'.php');
			self::$_models[$name]=new $name();
		}
		return self::$_models[$name];
	}
}
?>