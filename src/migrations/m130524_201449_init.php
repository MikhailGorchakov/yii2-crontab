<?php

use yii\db\Migration;

class m130524_201449_init extends Migration
{
	public function up()
	{
		$this->createTable('{{%tasks}}', [
			'id'   => $this->primaryKey(),
			'name' => $this->string(70)->notNull(),
			'date' => $this->dateTime()->notNull(),
		]);
	}

	public function down()
	{
		$this->dropTable('{{%tasks}}');
	}
}
