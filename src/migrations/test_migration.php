<?php

use yii\db\Migration;

class test_migration extends Migration
{
	/**
	 * {@inheritdoc}
	 */
	public function safeUp()
	{
		echo 'Hello, test migration!';
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown()
	{
		echo 'Bye, test migration!';
	}
}
