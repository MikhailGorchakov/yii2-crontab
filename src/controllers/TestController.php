<?php namespace djiney\crontab\controllers;

use yii\console\Controller;

class TestController extends Controller
{
	public function actionIndex()
	{
		echo 'Hello, test index!';
	}
}