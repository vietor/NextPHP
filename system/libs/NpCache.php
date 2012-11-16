<?php
interface NpCache
{
	public function inc($key, $value);
	public function dec($key, $value);
	public function get($key);
	public function set($key,$value,$timeout);
	public function delete($key);
}
?>