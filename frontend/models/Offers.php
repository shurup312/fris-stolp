<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "offers".
 *
 * @property integer  $id
 * @property string   $name
 * @property boolean  $is_fix_countries
 * @property boolean  $is_partial_install
 *
 * @property Prices[] $prices
 */
class Offers extends ActiveRecord {

	/**
	 * @inheritdoc
	 */
	public static function tableName () {
		return 'offers';
	}

	/**
	 * @inheritdoc
	 */
	public function rules () {
		return [
		  	[['name', 'is_fix_countries', 'is_partial_install'], 'required'],
		  	[['is_fix_countries', 'is_partial_install'], 'boolean'],
		  	[['name'], 'string', 'max' => 255]
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels () {
		return [
			'id'                 => 'ID',
			'name'               => 'Имя',
			'is_fix_countries'   => 'Только для фиксированных стран',
			'is_partial_install' => 'Оплачивается ли частичная уcтановка?',
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getPrices () {
		return $this->hasMany(Prices::className(), ['offer_id' => 'id']);
	}
}
