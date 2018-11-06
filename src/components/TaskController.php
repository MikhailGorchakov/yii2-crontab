<?php namespace djiney\crontab\components;

use djiney\crontab\components\traits\LogTrait;
use djiney\crontab\models\Task;
use Yii;
use yii\console\Controller;

/**
 * @deprecated DON'T USE THIS CLASS ANYMORE
 */
class TaskController extends Controller
{
	use LogTrait;

	/** @var Task */
	private $_task = false;

	/** @var bool */
	public $test = false;

	/**
	 * If this option is set, only actions presented in controlTasks() would be checked as tasks
	 * Otherwise - all actions would be launched throw tasks
	 * @var bool
	 */
	public $strictActions = false;

	/**
	 * Set all task-activated actions as an array. Only works with $strictActions = true
	 * return ['index', 'process'];
	 * @return array
	 */
	public function controlTasks() : array
	{
		return [];
	}

	/**
	 * Set all task-skipped actions as an array. Only works with $strictActions = false
	 * return ['index', 'process'];
	 * @return array
	 */
	public function skipTasks() : array
	{
		return [];
	}

	/**
	 * {@inheritdoc}
	 */
	public function options($actionID)
	{
		return array_merge(
			parent::options($actionID),
			['test']
		);
	}

	public function beforeAction($action)
	{
		if (!parent::beforeAction($action)) {
			return false;
		}

		if ($this->test) {
			self::log('Test mode');
			return true;
		}

		$actionID = $action->id;
		if ($this->strictActions && !in_array($actionID, $this->controlTasks())) {
			self::log('Allowed as uncontrolled');
			return true;
		}

		if (!$this->strictActions && in_array($actionID, $this->skipTasks())) {
			self::log('Allowed as skipped');
			return true;
		}

		$this->_task = Task::get(Yii::$app->controller->route);

		if ($this->_task === false) {
			self::log('Task does not exist, process terminated');
			return false;
		}

		self::log('Launched task #' . $this->_task->id);
		return true;
	}

	public function __destruct()
	{
		if ($this->_task !== false) {
			self::log('Task #' . $this->_task->id . ' is completed');
		}
	}
}