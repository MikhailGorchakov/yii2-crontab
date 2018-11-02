<?php namespace djiney\crontab\models;

use yii\db\ActiveRecord;

class TestModel extends ActiveRecord
{
	/**
	 * {@inheritdoc}
	 */
	public static function tableName()
	{
		return 'projects';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules()
	{
		return [
			[['user_id', 'count'], 'default', 'value' => null],
			[['user_id', 'count'], 'integer'],
			[['date'], 'safe'],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels()
	{
		return [
			'id'      => 'ID',
			'user_id' => 'User ID',
			'date'    => 'Date',
			'count'   => 'Count',
		];
	}
}