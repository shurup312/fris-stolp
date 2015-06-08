<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%antiviruses}}".
 *
 * @property integer              $id
 * @property string               $name
 *
 * @property LandingAntiviruses[] $landingAntiviruses
 */
class Antiviruses extends \yii\db\ActiveRecord {

	/**
	 * @inheritdoc
	 */
	public static function tableName () {
		return '{{%antiviruses}}';
	}

	/**
	 * @inheritdoc
	 */
	public function rules () {
		return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 255]
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels () {
		return [
			'id'   => 'ID',
			'name' => 'Название антивируса',
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getLandingAntiviruses () {
		return $this->hasMany(LandingAntiviruses::className(), ['antivirus_id' => 'id']);
	}
}
