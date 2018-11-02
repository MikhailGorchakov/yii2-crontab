<?php namespace djiney\crontab\controllers;

use yii\console\Controller;

// php yii migrate/up --migrationPath=@vendor/djiney/yii2-crontab/src/migrations/
class TaskController extends Controller
{
	public function actionIndex()
	{
		echo 'Hello, task index!';
	}
}