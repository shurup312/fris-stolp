<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "advertisers".
 *
 * @property integer $id
 * @property string  $company_name
 * @property string  $date_created
 * @property boolean $is_active
 * @property integer $hold
 *
 * @property Softs[] $softs
 */
class Advertisers extends ActiveRecord {

	/**
	 * @inheritdoc
	 */
	public static function tableName () {
		return 'advertisers';
	}

	/**
	 * @inheritdoc
	 */
	public function rules () {
		return [
			[['company_name', 'is_active', 'hold'], 'required'],
			[['date_created'], 'safe'],
			[['is_active'], 'boolean'],
			[['hold'], 'integer'],
			[['company_name'], 'string', 'max' => 255]
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels () {
		return [
			'id'           => 'ID',
			'company_name' => 'Company Name',
			'date_created' => 'Дата создания',
			'is_active'    => 'Активен ли рекламодатель',
			'hold'         => 'Холдовый период',
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getSofts () {
		return $this->hasMany(Softs::className(), ['advertiser_id' => 'id']);
	}
}
