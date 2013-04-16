<?php
class HelloWorld extends NpController
{
	public function model()
	{
		$model=NpModel::load('HelloModel');
		$model->test();
	}

	public function view()
	{
		$view=NpView::load('HelloView');
		$view->assign('value', NpRequest::getSessionId());
		return $view;
	}
	
	public function internal()
	{
		$view=NpView::load(NpView::VARIABLE);
		$view->assign('value', NpRequest::getSessionId());
		return $view;
	}

	public function memcache()
	{
		$key='demo';
		$view=NpView::load(NpView::OUTPUT);
		$cache=NpFactory::getCache();
		$cache->set($key,NpRequest::getSessionId());
		$view->assign('value', $cache->get($key));
		$cache->delete($key);
		return $view;
	}

	public function database()
	{
		$name='t_demo';
		$view=NpView::load(NpView::OUTPUT);
		$db=NpFactory::getDatabase();
		$cmd=$db->prepare('CREATE TABLE '.$name.'( id VARCHAR(255))');
		$cmd->execute();
		$cmd=$db->prepare('INSERT INTO '.$name.'(id) VALUES(?)');
		$cmd->execute(array(NpRequest::getSessionId()));
		$cmd=$db->prepare('SELECT id FROM '.$name.' LIMIT 1');
		$data=$cmd->query()->fetchObject();
		$view->assign('value', $data->id);
		$cmd=$db->prepare('DROP TABLE '.$name);
		$cmd->execute();
		return $view;
	}

	public function encryptor()
	{
		$view=NpView::load(NpView::OUTPUT);
		$encryptor=NpFactory::getEncryptor();
		$view->assign('value', $encryptor->encrypt(NpRequest::getSessionId()));
		return $view;
	}

	protected function beforeProcess($action)
	{
		echo 'before process run action:'.$action.'<br/>';
	}

	protected function afterProcess()
	{
		echo 'after process<br/>';
	}
	
	protected function handleProcessBreak($code)
	{
		echo 'handle process break code is '.$code.'<br/>';
	}

	protected function handleProcessCleanup()
	{
		echo 'handle process cleanup<br/>';
	}
}
?>