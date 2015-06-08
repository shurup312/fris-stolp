<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "prices".
 *
 * @property integer $id
 * @property integer $offer_id
 * @property string  $country_id
 * @property string  $amount
 *
 * @property Offers  $offer
 */
class Prices extends ActiveRecord {

	/**
	 * @inheritdoc
	 */
	public static function tableName () {
		return 'prices';
	}

	/**
	 * @inheritdoc
	 */
	public function rules () {
		return [
		  	[['offer_id'], 'integer'],
		  	[['country_id', 'amount'], 'required'],
		  	[['amount'], 'number'],
		  	[['country_id'], 'string', 'max' => 255]
	  	];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels () {
		return [
			'id'         => 'ID',
			'offer_id'   => 'ID оффера',
			'country_id' => 'ID страны',
			'amount'     => 'Стоимость лида',
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getOffer () {
		return $this->hasOne(Offers::className(), ['id' => 'offer_id']);
	}
}
