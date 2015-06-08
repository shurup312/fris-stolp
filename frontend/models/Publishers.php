<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "publishers".
 *
 * @property integer       $id
 * @property integer       $user_id
 * @property string        $first_name
 * @property string        $middle_name
 * @property string        $last_name
 * @property string        $email
 * @property string        $back_url
 *
 * @property ProfitStats[] $profitStats
 * @property Users         $user
 */
class Publishers extends ActiveRecord {

	/**
	 * @inheritdoc
	 */
	public static function tableName () {
		return 'publishers';
	}

	/**
	 * @inheritdoc
	 */
	public function rules () {
		return [
      		'id' => 'ID',
      		'user_id' => 'User ID',
      		'first_name' => 'Имя',
      		'middle_name' => 'Отчество',
      		'last_name' => 'Фамилия',
      		'email' => 'E-mail',
      		'back_url' => 'Back URL',
  		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels () {
		return [
			'id'          => 'ID',
			'user_id'     => 'User ID',
			'first_name'  => 'Имя',
			'middle_name' => 'Отчество',
			'last_name'   => 'Фамилия',
			'email'       => 'E-mail',
			'back_url'    => 'Back URL',
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getProfitStats () {
		return $this->hasMany(ProfitStats::className(), ['publisher_id' => 'id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getUser () {
		return $this->hasOne(Users::className(), ['id' => 'user_id']);
	}
}
