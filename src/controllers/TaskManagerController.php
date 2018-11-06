<?php namespace djiney\crontab\controllers;

use djiney\crontab\components\Configuration;
use djiney\crontab\components\CronTask;
use djiney\crontab\components\traits\LogTrait;
use djiney\crontab\models\Task;
use yii\console\Controller;
use yii\helpers\ArrayHelper;

class TaskManagerController extends Controller
{
	use LogTrait;

	const SLEEP_INTERVAL = 60;

	/** @var Configuration */
	private $_config;

	private function getConfig() : Configuration
	{
		if ($this->_config === null) {
			/** @noinspection PhpUndefinedFieldInspection */
			$this->_config = \Yii::$app->cron;
		}

		return $this->_config;
	}

	/**
	 * Daemon mode
	 * php yii task-manager/daemon
	 */
	public function actionDaemon()
	{
		set_time_limit(0);

		while (true) {
			$this->createTasks();
			$this->startTasks();

			self::log('Sleep for: ' . self::SLEEP_INTERVAL . ' seconds');
			sleep(self::SLEEP_INTERVAL);
		}
	}

	/**
	 * php yii task-manager/start-tasks
	 */
	public function actionStartTasks()
	{
		$this->startTasks();
	}

	/**
	 * php yii task-manager/create-tasks
	 */
	public function actionCreateTasks()
	{
		$this->createTasks();
	}

	/**
	 * All tasks with specified name are gonna be deleted
	 * In case there is no name - all tasks
	 *
	 * @param string $name
	 * php yii task-manager/reset-tasks [name]
	 */
	public function actionResetTasks($name = '')
	{
		if ($name == '') {
			self::log('Removing tasks');
			$count = Task::deleteAll();
		} else {
			self::log('Removing tasks with name: ' . $name);
			$count = Task::deleteAll([
				'name' => $name
			]);
		}

		self::log('Tasks deleted: ' . $count);
	}

	/**
	 * Showing tasks with specified name (or all tasks)
	 *
	 * @param string $name
	 * php yii task-manager/show-tasks [name]
	 */
	public function actionShowTasks($name = null)
	{
		$list = Task::getList($name);

		self::log('Total count: ' . $list->count());

		/** @var Task $task */
		foreach ($list->each() as $task) {
			self::log($task->name . ' ' . $task->date);
		}
	}

	protected function createTasks()
	{
		foreach ($this->getConfig()->getTasks() as $task) {

			self::log('Task creation: ' . $task->name);

			$expression = $task->getExpression();
			$start = $task->getLastDate();

			$required = $task->required();
			for ($i = 0; $i < $required; $i++) {
				$date = $expression->getNextRunDate($start)->format('Y-m-d H:i:s');
				Task::create([
					'name' => $task->name,
					'date' => $date
				]);

				$start = $date;
			}

			self::log(' -- created: ' . $required);
		}
	}

	protected function startTasks()
	{
		self::log('Launching tasks');

		$tasks = $this->getConfig()->getTasks();

		$queue = [];

		/** @var Task $task */
		foreach (Task::getNewTasks()->each() as $task) {

			/** @var CronTask $cronTask */
			$cronTask = ArrayHelper::getValue($tasks, $task->name);
			if ($cronTask === null) {
				self::log('Configuration for a task is missing: ' . $task->name);
				$task->remove();
				continue;
			}

			if (!$task->claim()) {
				self::log('Failed claiming');
				continue;
			}

			self::log('Launching: ' . $cronTask->command);

			if ($cronTask->queue === false) {
				if (in_array($task->name, $queue)) {
					$task->remove();
					continue;
				}

				$queue[] = $task->name;
			}

			$this->executeTask($cronTask);
		}
	}

	private function executeTask(CronTask $task) : bool
	{
		if (substr(php_uname(), 0, 7) == 'Windows'){
			self::log('Windows is not supported');
			return false;
		}

		self::log('Launched - ' . $task->getCommand());
		exec($task->getCommand());
		return true;
	}
}