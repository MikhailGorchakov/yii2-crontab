<?php namespace djiney\crontab\components;

use yii\base\Component;

class Configuration extends Component
{
	public $tasks = [];

	/** @var CronTask[] */
	private $_tasks = false;

	private function initTasks()
	{
		$this->_tasks = [];
		foreach ($this->tasks as $name => $data) {

			if (!isset($data['name'])) {
				$data['name'] = $name;
			}

			$this->_tasks[] = new CronTask($data);
		}
	}

	public function getTasks()
	{
		if ($this->_tasks === false) {
			$this->initTasks();
		}

		return $this->_tasks;
	}
}