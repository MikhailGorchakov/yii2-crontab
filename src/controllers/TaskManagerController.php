<?php namespace djiney\crontab\controllers;

use djiney\crontab\components\traits\LogTrait;
use yii\console\Controller;

class TaskManagerController extends Controller
{
	use LogTrait;

	public function actionIndex()
	{
		self::log(123);
	}




//	const SLEEP_INTERVAL = 60;
//	const TASK_FORWARD = 10;
//
//	public $tasks;
//	public $weights;
//
//	/**
//	 * Запуск демона задач
//	 * php yii task-daemon/daemon
//	 */
//	public function actionDaemon()
//	{
//		self::log('Инициализация демона');
//
//		set_time_limit(0);
//
//		$this->tasks = self::getTasks();
//
//		$this->weights = [
//			'minute' => 60,
//			'hour' => 24,
//			'day' => (int) date('t'),
//			'month' => 12,
//			'year' => 100000
//		];
//
//		while (true) {
//
//			$this->createTasks();
//
//			$this->startTasks();
//
//			self::log('Пауза: '.self::SLEEP_INTERVAL.' сек');
//
//			sleep(self::SLEEP_INTERVAL);
//		}
//	}
//
//	private function checkTasks()
//	{
//		/** @var Task $task */
//		$tasks = Task::find()
//			->orderBy(['time' => SORT_ASC])
//			->all();
//
//		$queue = [];
//
//		foreach ($tasks as $task) {
//
//			if (empty($queue[$task->name])) {
//				$queue[$task->name] = [$task->time];
//				continue;
//			}
//
//			if (in_array($task->time, $queue[$task->name])) {
//				$task->delete();
//				continue;
//			} else {
//				$queue[$task->name][] = $task->time;
//			}
//		}
//	}
//
//	/**
//	 * Вывод в удобном виде всех задач с указанием времени выполнения, или же задач с определённым именем
//	 * @param string $name
//	 * php yii task-daemon/show-tasks [name]
//	 */
//	public function actionShowTasks($name = '')
//	{
//		while (true) {
//			$tasks = Task::find()
//				->orderBy(['time' => SORT_ASC]);
//
//			if (!empty($name)) {
//				$tasks->where(['name' => $name]);
//			}
//
//			$tasks = $tasks->all();
//
//			/** @var Task $task */
//			foreach ($tasks as $task) {
//				self::log($task->name.' '.date('Y-m-d H:i:s',$task->time));
//			}
//
//			if (empty($tasks)) {
//				self::log('Задач по этому имени не обнаружено');
//			}
//
//			sleep(10);
//			echo PHP_EOL.PHP_EOL;
//		}
//	}
//
//
//	/**
//	 * Удаление всех задач, или же задач с определённым именем
//	 * @param string $name
//	 * php yii task-daemon/reset-tasks [name]
//	 */
//	public function actionResetTasks($name = '')
//	{
//		if ($name == '') {
//			self::log('Удаление всех задач');
//			Task::deleteAll();
//		} else {
//			self::log('Удаление задач c именем: '.$name);
//			Task::deleteAll([
//				'name' => $name
//			]);
//		}
//	}
//
//	/**
//	 * Добавление задач
//	 * php yii task-daemon/create-tasks
//	 */
//	public function actionCreateTasks()
//	{
//		self::log('Инициализация менеджера задач');
//
//		set_time_limit(0);
//
//		$this->tasks = self::getTasks();
//
//		$this->weights = [
//			'minute' => 60,
//			'hour' => 24,
//			'day' => (int) date('t'),
//			'month' => 12,
//			'year' => 100000
//		];
//
//		$this->createTasks();
//
//		self::log('Генерация задач завершена');
//	}
//
//	/**
//	 * Запуск задач, для которых пришло время
//	 * php yii task-daemon/start-tasks
//	 */
//	public function actionStartTasks()
//	{
//		self::log('Инициализация распределителя задач');
//
//		set_time_limit(0);
//
//		$this->tasks = self::getTasks();
//
//		$this->startTasks();
//
//		self::log('Запуск задач завершен');
//	}
//
//	public function startTasks()
//	{
//		self::log('Выполнение задач');
//
//		$this->tasks = self::getTasks();
//
//		$this->checkTasks();
//
//		$tasks = Task::getNewTasks();
//
//		$queue = []; // Очередь задач
//
//		/** @var Task $task */
//		foreach ($tasks as $task) {
//
//			if (!isset($this->tasks[$task->name])) {
//				$task->delete();
//				self::log('Команда отсутствует в конфиге: ' . $task->name);
//				continue;
//			}
//
//			$data = $this->tasks[$task->name];
//			self::log('Запуск команды: ' . $data['command']);
//
//			if (isset($data['queue']) && $data['queue'] == false) {
//				if (in_array($task->name, $queue)) {
//					$task->delete();
//					continue;
//				}
//
//				$queue[] = $task->name;
//			}
//
//			if (!empty($data['description'])) {
//				self::log('Задача: '.$data['description']);
//			}
//
//			$this->execInBackground($data);
//		}
//	}
//
//	/**
//	 * Запуск для теста задачи с определённым именем
//	 * @param string $name
//	 * php yii task-daemon/start-task [name]
//	 */
//	public function actionStartTask($name)
//	{
//		$this->execInBackground(['command' => $name . ' test']);
//	}
//
//	public function createTasks()
//	{
//		self::log('Добавление задач');
//
//		foreach ($this->tasks as $task => $data) {
//
//			$forward = (int) (empty($data['task_forward']) ? self::TASK_FORWARD : $data['task_forward']);
//			$count = $forward - Task::count($task);
//
//			if ($count <= 0) {
//				continue;
//			}
//
//			$start = Task::time($task);
//
//			for ($i = 0; $i < $count; $i++) {
//
//				$start = $this->countInterval($task, $start);
//
//				Task::create([
//					'name' => $task,
//					'time' => $start
//				]);
//			}
//		}
//	}
//
//	private function countInterval($task, $start)
//	{
//		$data = $this->tasks[$task];
//
//		$interval = (empty($data['interval']) ? [] : $data['interval']);
//
//		$interval = $this->checkInterval($interval);
//
//		$last_time = [
//			'year' => date('Y', $start),
//			'month' => date('n', $start),
//			'day' => date('j', $start),
//			'hour' => date('H', $start),
//			'minute' => date('i', $start),
//			//'week_day' => false, // Отдельная тема, не работает
//		];
//
//		$time = $last_time;
//
//		$mod = false;
//
//		foreach ($time as $key => $value) {
//			if ($key === 'minute' && $interval[$key] == '*') {
//				$time[$key]++;
//			} else {
//				$time[$key] = $this->parseIntervalValue($value, $interval[$key], $this->weights[$key], $mod);
//			}
//
//			if ($time[$key] != $last_time[$key]) {
//				$mod = true;
//			}
//		}
//
//		return mktime($time['hour'], $time['minute'], 0, $time['month'], $time['day'], $time['year']);
//	}
//
//	private function parseIntervalValue($value, $interval, $weight, $mod)
//	{
//		// *
//		if ($interval === '*') {
//			return $value;
//		}
//
//		// */4
//		if (strpos($interval, '*/') === 0) {
//			return $value + (int) str_replace('*/', '', $interval);
//		}
//
//		// 10
//		if ($interval == (string)((int)$interval)) {
//			if ($interval > $value || $mod) {
//				return $interval;
//			} else {
//				return $weight + $interval;
//			}
//		}
//
//		// 10, 40
//		if (strpos($interval, ',') !== false) {
//			$marks = explode(',', $interval);
//			$closest = $this->getClosest($marks, $value);
//
//			if ($closest > $value || $mod) {
//				return $closest;
//			} else {
//				return $weight + $closest;
//			}
//		}
//
//		return $value;
//	}
//
//	private function getClosest($array, $value)
//	{
//		asort($array);
//		$min = $array[0];
//
//		foreach ($array as $item) {
//			if ($value < $item) {
//				return $item;
//			}
//		}
//
//		return $min;
//	}
//
//	private function checkInterval($interval)
//	{
//		$default = [
//			'minute' => '*',
//			'hour' => '*',
//			'day' => '*',
//			'month' => '*',
//			'week_day' => '*',
//			'year' => '*'
//		];
//
//		foreach ($default as $key => $value) {
//
//			if (!isset($interval[$key])) {
//				$interval[$key] = $value;
//			}
//
//		}
//
//		return $interval;
//	}
//
//	public static function getTasks()
//	{
//		return Yii::$app->params['cron'];
//	}
//
//	private function execInBackground($data) {
//
//		$command = $data['command'];
//		$log = empty($data['log']) ? '/dev/null' : $data['log'];
//
//		if (substr(php_uname(), 0, 7) == 'Windows'){
//			self::log('Запущено - '.'start /B '. $command);
//			pclose(popen('start /B '. $command, 'r')); // Не работает, win 7
//		} else {
//			self::log('Запущено - '.$command . ' > '.$log.' &');
//			exec($command . ' > '.$log.' &');
//		}
//	}
}