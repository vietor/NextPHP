<?php
interface NpCache {
	public function get($key);
	public function set($key,$value,$timeout);
	public function delete($key);
}
?>