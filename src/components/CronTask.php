<?php namespace djiney\crontab\components;

use Cron\CronExpression;
use djiney\crontab\models\Task;
use yii\base\Model;

/**
 * @property string  $name
 * @property string  $command
 * @property string  $log
 * @property string  $description
 * @property boolean $queue
 * @property integer $taskForward
 * @property array   $interval
 */
class CronTask extends Model
{
	public $name;
	public $command;
	public $log;
	public $description;
	public $queue = false;
	public $taskForward = 10;
	public $interval = [];

	public function rules()
	{
		return [
			[['name', 'command', 'log', 'description'], 'string'],
			[['queue'], 'boolean'],
			[['taskForward'], 'integer'],
			[['interval'], 'safe'],
		];
	}

	public function getExpression() : CronExpression
	{
		$interval = [
			'minute'  => '*',
			'hour'    => '*',
			'day'     => '*',
			'month'   => '*',
			'weekDay' => '*',
		];

		foreach ($interval as $key => $value) {
			if (isset($this->interval[$key])) {
				$interval[$key] = $this->interval[$key];
			}
		}

		return CronExpression::factory(implode(' ', $interval));
	}

	public function required()
	{
		return $this->taskForward - Task::getCount($this->name);
	}

	public function getLastDate()
	{
		return Task::getLastDate($this->name);
	}
}