<?php
interface Cache {
	public function get($key);
	public function set($key,$value);
	public function set($key,$value,$timeout);
	public function delete($key);
	public function close();
}
?>