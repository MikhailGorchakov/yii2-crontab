<?php namespace djiney\crontab\components;

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
	public function rules()
	{
		return [
			[['name', 'command', 'log', 'description'], 'string'],
			[['queue'], 'boolean'],
			[['taskForward'], 'integer'],
			[['interval'], 'safe'],
		];
	}
}